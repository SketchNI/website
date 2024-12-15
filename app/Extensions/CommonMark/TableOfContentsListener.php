<?php

namespace App\Extensions\CommonMark;

use League\CommonMark\Event\DocumentPreRenderEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Block\ListData;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;

class TableOfContentsListener
{
    public function __construct(
        private ?ListData $table = null
    ) {}

    /**
     * todo: make this work
     * @param  DocumentPreRenderEvent  $event
     *
     * @return void
     */
    public function onDocumentParsed(DocumentPreRenderEvent $event): void
    {
        $document = $event->getDocument();
        $walker = $document->walker();
        while ($event = $walker->next()) {
            $node = $event->getNode();

            dump($node);
            if ($node instanceof TableOfContents || $event->isEntering()) {
                $this->table = $node->getListData();
            }

            if ($node instanceof Heading && $node->getLevel() === 1) {
                $node->insertAfter($this->table);
            }

        }
    }
}
