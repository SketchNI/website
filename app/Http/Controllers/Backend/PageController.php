<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Pages\CreateRequest;
use App\Http\Requests\Backend\Pages\UpdateRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class PageController extends Controller
{
    public function index(): Response
    {
        $pages = PageResource::collection(Page::paginate(15));

        return inertia('Backend/Pages/Index', compact('pages'));
    }

    public function edit(Page $page): Response
    {
        $page = new PageResource($page)->resolve();

        return inertia('Backend/Pages/Edit', compact('page'));
    }

    public function create(): Response
    {
        return inertia('Backend/Pages/Create');
    }
    public function store(CreateRequest $request): RedirectResponse
    {
        $page = new Page()
            ->setUserId(auth()->id())
            ->setSlug($request->get('slug'))
            ->setTitle($request->get('title'), !$request->has('slug') || strlen($request->get('slug')) === 0)
            ->setContent($request->get('content'))
            ->setPublishedAt($request->get('is_published') ? now() : null);

        if ($page->save()) {
            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($page)
                ->withProperties(['page' => $page, 'user' => auth()->user()])
                ->log("created page {$page->title}");

            session()->flash('flash', ['message' => 'Page created successfully.', 'type' => 'success']);

            return redirect()->route('backend.pages.edit', $page->fresh());
        }

        session()->flash('flash', ['message' => 'Page not created.', 'type' => 'error']);

        return redirect()->back();
    }

    public function update(UpdateRequest $request, Page $page): RedirectResponse
    {
        $page = Page::findOrFail($page->id);
        $page
            ->setTitle($request->get('title'), $request->get('slug') !== $page->slug)
            ->setSlug($request->get('slug'))
            ->setContent($request->get('content'))
            ->setPublishedAt($request->get('is_published') ? now() : null);

        if ($page->save()) {
            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($page)
                ->withProperties(['page' => $page, 'user' => auth()->user()])
                ->log("updated page {$page->title}");

            session()->flash('flash', ['message' => 'Page updated successfully.', 'type' => 'success']);

            return redirect()->back();
        }

        session()->flash('flash', ['message' => 'Page not updated.', 'type' => 'error']);

        return redirect()->back();
    }
}
