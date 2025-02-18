<?php

namespace App\Http\Resources;

use App\Extensions\CommonMark\SketchMark;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\MarkdownConverter;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => new UserResource($this->load('author')->author)->resolve(),
            'comment' => $this->markdownify($this->comment),
            'created_at' => $this->created_at,
        ];
    }

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
            'smartpunct' => [
                'double_quote_opener' => '“',
                'double_quote_closer' => '”',
                'single_quote_opener' => '‘',
                'single_quote_closer' => '’',
            ],
        ];

        $env = new Environment($config)
            ->addExtension(new SketchMark())
            ->addExtension(new ExternalLinkExtension())
            ->addExtension(new SmartPunctExtension());

        $converter = new MarkdownConverter($env);

        return $converter->convert($text);
    }
}
