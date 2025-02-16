<?php

namespace App\Http\Resources\Support;

use App\Extensions\CommonMark\SketchMark;
use App\Http\Resources\UserResource;
use App\Models\Support\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\MarkdownConverter;

/** @mixin Ticket */
class TicketResource extends JsonResource
{
    /**
     * @throws CommonMarkException
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user)->resolve(),
            'status' => new TicketStatusResource($this->status)->resolve(),
            'title' => $this->title,
            'content' => $this->markdownify($this->content),
            'attachments' => $this->whenNotNull($this->attachments),
            'replies' => $this->whenLoaded('replies',  TicketReplyResource::collection($this->replies)->resolve(),),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * @throws CommonMarkException
     */
    private function markdownify(?string $text): string
    {
        $config = [
            'external_link' => [
                'internal_hosts' => config('app.url'),
                'open_in_new_window' => true,
                'html_class' => 'external-link',
                'nofollow' => '',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ];

        $env = new Environment($config)
            ->addExtension(new SketchMark)
            ->addExtension(new ExternalLinkExtension);

        $converter = new MarkdownConverter($env);

        return $converter->convert($text);
    }
}
