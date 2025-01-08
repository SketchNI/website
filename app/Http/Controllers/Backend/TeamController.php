<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Team\CreateRequest;
use App\Http\Requests\Backend\Team\UpdateRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TeamController
{
    public function index(): Response
    {
        if (auth()->user()->cannot('viewAny', Team::class)) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        return inertia('Backend/Teams/Index', [
            'teams' => Inertia::defer(fn() => TeamResource::collection(Team::all())),
        ]);
    }

    public function create(): Response
    {
        if (auth()->user()->cannot('create', Team::class)) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        return inertia('Backend/Teams/Create');
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        if (auth()->user()->cannot('create', Team::class)) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $team = new Team;
        $team->name = $request->get('name');
        $team->role = $request->get('role');
        $team->description = $request->get('description');
        $team->website = $request->get('website');
        $team->github = $request->get('github');

        if ($request->hasFile('logo')) {
            if (!is_null($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }
            $team->logo = '/'.$request->file('logo')->storePublicly('images', ['disk' => 'public']);
        }

        if ($team->save()) {
            session()->flash('flash', ['message' => 'Team created successfully.', 'type' => 'success']);

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->withProperties(['team' => $team, 'user' => auth()->user()])
                ->on($team)
                ->log(sprintf('%s created team entry', auth()->user()->name));

            return redirect(route('backend.teams.edit', ['team' => $team->fresh()]));
        } else {
            session()->flash('flash', ['message' => 'Team entry not created.', 'type' => 'error']);

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($team)
                ->withProperties(['team' => $team, 'user' => auth()->user()])
                ->log(sprintf('%s attempted to created team entry', auth()->user()->name));

            return redirect(route('backend.blog.index'));
        }
    }

    public function edit(Team $team): Response
    {
        if (auth()->user()->cannot('edit', Team::class)) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $team = Team::find($team->id);

        return inertia('Backend/Teams/Show', [
            'team' => new TeamResource($team)->resolve(),
        ]);
    }

    public function destroy(Team $team): RedirectResponse
    {
        $team = Team::find($team->id);

        if (auth()->user()->cannot('destroy', $team)) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        if ($team->delete()) {
            if (!is_null($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }
            session()->flash('flash', ['message' => 'Team entry deleted successfully.', 'type' => 'success']);

            return redirect()->route('backend.teams.index');
        }

        session()->flash('flash', ['message' => 'Unable to delete team entry.', 'type' => 'error']);

        return redirect()->back();
    }

    public function update(UpdateRequest $request, Team $team): RedirectResponse
    {
        if (auth()->user()->cannot('update', $team)) {
            app()->abort(HttpResponse::HTTP_FORBIDDEN);
        }

        $team = Team::find($team->id);

        $team->name = $request->get('name');
        $team->description = $request->get('description');
        $team->role = $request->get('role');
        $team->github = $request->get('github');
        $team->website = $request->get('website');

        if ($request->hasFile('logo')) {
            if (!is_null($team->logo)) {
                Storage::disk('public')->delete($team->logo);
            }
            $team->logo = '/'.$request->file('logo')->storePublicly('images', ['disk' => 'public']);
        }

        if ($team->save()) {
            session()->flash('flash', ['message' => 'Team updated successfully.', 'type' => 'success']);

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->withProperties(['team' => $team, 'user' => auth()->user()])
                ->on($team)
                ->log(sprintf('%s updated team entry', auth()->user()->name));
        } else {
            session()->flash('flash', ['message' => 'Blog post not updated.', 'type' => 'error']);

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($team)
                ->withProperties(['team' => $team, 'user' => auth()->user()])
                ->log(sprintf('%s attempted to update team entry', auth()->user()->name));
        }

        return redirect()->back(303);
    }
}
