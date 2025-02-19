<?php

namespace App\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;

class Markdown
{
    /**
     * @throws CommonMarkException
     */
    public static function make(string $content): string
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
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => 'content',
                'apply_id_to_heading' => false,
                'heading_class' => '',
                'fragment_prefix' => 'content',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
                'symbol' => '#',
                'aria_hidden' => true,
            ],
            'smartpunct' => [
                'double_quote_opener' => '“',
                'double_quote_closer' => '”',
                'single_quote_opener' => '‘',
                'single_quote_closer' => '’',
            ],
            'table_of_contents' => [
                'html_class' => 'table-of-contents not-prose',
                'style' => 'bullet',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'normalize' => 'flat',
            ],
            'embed' => [
                'adapter' => new OscaroteroEmbedAdapter, // See the "Adapter" documentation below
                'allowed_domains' => ['youtube.com', 'twitter.com', 'github.com', 'x.com', 'bsky.app', 'opengraph.githubassets.com'],
                'fallback' => 'link',
            ],
        ];

        $env = new Environment($config)
            ->addExtension(new CommonMarkCoreExtension)
            ->addExtension(new GithubFlavoredMarkdownExtension)
            ->addExtension(new ExternalLinkExtension)
            ->addExtension(new HeadingPermalinkExtension)
            ->addExtension(new SmartPunctExtension)
            ->addExtension(new TableOfContentsExtension)
            ->addExtension(new EmbedExtension);

        $converter = new MarkdownConverter($env);

        return $converter->convert($content)->getContent();
    }
}
