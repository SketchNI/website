<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Traits\ThrowsException;
use Illuminate\Http\Request;
use Inertia\Response;

class PageController
{
    use ThrowsException;

    public function show(Page $page): Response
    {
        $page = Page::published()->withoutTrashed()->findOrFail($page->id);

        $page = new PageResource($page)->resolve();

        return inertia('Page/Show', compact('page'));
    }

    /**
     * @throws ForbiddenException
     */
    public function preview(Request $request): Response
    {
        $this->forbidden('write blog entry');

        $page = Page::unpublished()->withoutTrashed()->find($request->get('id'));

        return inertia('Page/Preview', [
            'page' => new PageResource($page)->resolve(),
        ]);
    }
}
