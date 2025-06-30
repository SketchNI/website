<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Pages\CreateRequest;
use App\Http\Requests\Backend\Pages\UpdateRequest;
use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(Request $request): Response
    {
        $breadcrumbs = [
            [
                'route' => route('backend.pages.index'),
                'name' => 'Pages',
                'active' => request()->routeIs('backend.pages.index'),
            ],
        ];

        return inertia('Backend/Pages/Index', [
            'pages' => $this->resolvePosts($request->get('filter')),
            'counts' => Inertia::defer(fn() => [
                'pages' => Page::published()->count(),
                'unpublished' => Page::unpublished()->count(),
                'deleted' => Page::onlyTrashed()->count(),
            ], 'pages'),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function edit(Page $page): Response
    {
        $page = new PageResource($page)->resolve();

        $breadcrumbs = [
            [
                'route' => route('backend.pages.index'),
                'name' => 'Pages',
                'active' => request()->routeIs('backend.pages.index'),
            ],
            [
                'route' => route('backend.pages.edit', ['page' => $page['id']]),
                'name' => sprintf('Edit "%s"', $page['title']),
                'active' => request()->routeIs('backend.pages.edit', ['page' => $page['id']]),
            ],
        ];

        return inertia('Backend/Pages/Edit', compact('page', 'breadcrumbs'));
    }

    public function create(): Response
    {
        $breadcrumbs = [
            [
                'route' => route('backend.pages.index'),
                'name' => 'Pages',
                'active' => request()->routeIs('backend.pages.index'),
            ],
            [
                'route' => route('backend.pages.create'),
                'name' => 'Create Page',
                'active' => request()->routeIs('backend.pages.create'),
            ],
        ];

        return inertia('Backend/Pages/Create', compact('breadcrumbs'));
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

    private function resolvePosts(?string $filter): AnonymousResourceCollection
    {
        return PageResource::collection(match ($filter) {
            'deleted' => Page::onlyTrashed()->paginate(10),
            'unpublished' => Page::unpublished()->paginate(10),
            default => Page::published()->paginate(10),
        });
    }
}
