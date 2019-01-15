<?php

declare(strict_types=1);

namespace Rancoud\Pagination;

/**
 * Class Pagination.
 */
class Pagination
{
    // region Properties: Calculations
    protected $currentPage = 1;
    protected $countElements = null;
    protected $countElementPerPage = null;
    protected $maxPages = null;
    protected $countPagesPairLimit = 0;
    protected $countPagesPairAdjacent = 2;
    // endregion

    // region Properties: Links
    protected $baseLink = '';
    protected $baseLinkAfter = '';
    // endregion

    // region Properties: Texts
    protected $textPrevious = 'Previous page';
    protected $textNext = 'Next page';
    protected $textDots = '&hellip;';
    // endregion

    // region Properties: Generation
    protected $previous = null;
    protected $links = [];
    protected $next = null;
    protected $useDots = false;
    protected $usePrevious = false;
    protected $useNext = false;
    protected $showAllLinks = false;
    // endregion

    // region Properties: Tags Parameters
    protected $rootTag = 'ul';
    protected $rootAttrs = '';
    protected $itemTag = 'li';
    protected $itemAttrs = '';
    protected $itemAttrsCurrent = '';
    protected $itemNextAttrs = '';
    protected $itemPreviousAttrs = '';
    protected $linkTag = 'a';
    protected $linkAttrs = '';
    protected $linkAttrsCurrent = '';
    // endregion

    // region Configuration keys
    protected $propsString = [
        'base_link'           => 'baseLink',
        'base_link_after'     => 'baseLinkAfter',
        'text_previous'       => 'textPrevious',
        'text_next'           => 'textNext',
        'text_dots'           => 'textDots',
        'root_tag'            => 'rootTag',
        'root_attrs'          => 'rootAttrs',
        'item_tag'            => 'itemTag',
        'item_attrs'          => 'itemAttrs',
        'item_attrs_current'  => 'itemAttrsCurrent',
        'item_next_attrs'     => 'itemNextAttrs',
        'item_previous_attrs' => 'itemPreviousAttrs',
        'link_tag'            => 'linkTag',
        'link_attrs'          => 'linkAttrs',
        'link_attrs_current'  => 'linkAttrsCurrent'
    ];
    protected $propsBool = [
        'use_dots'       => 'useDots',
        'use_previous'   => 'usePrevious',
        'use_next'       => 'useNext',
        'show_all_links' => 'showAllLinks'
    ];
    protected $propsPositiveInteger = [
        'count_pages_pair_limit'    => 'countPagesPairLimit',
        'count_pages_pair_adjacent' => 'countPagesPairAdjacent',
    ];
    // endregion

    /**
     * Pagination constructor.
     *
     * @param int   $currentPage
     * @param array $configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->setConfiguration($configuration);
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration): void
    {
        foreach ($configuration as $key => $value) {
            if (isset($this->propsString[$key])) {
                $this->{$this->propsString[$key]} = (string) $value;
            } elseif (isset($this->propsBool[$key])) {
                $this->{$this->propsBool[$key]} = (bool) $value;
            } elseif (isset($this->propsPositiveInteger[$key])) {
                $val = (int) $value;
                $this->{$this->propsPositiveInteger[$key]} = ($val < 0) ? 0 : $val;
            }
        }
    }

    /**
     * @param int $currentPage
     * @param int $countElements
     * @param int $countElementPerPage
     *
     * @return string
     */
    public function generateHtml(int $currentPage, int $countElements, int $countElementPerPage): string
    {
        $this->setPaginate($currentPage, $countElements, $countElementPerPage);

        $this->compute();

        return $this->getHtml();
    }

    /**
     * @param int $currentPage
     * @param int $countElements
     * @param int $countElementPerPage
     *
     * @return array
     */
    public function generateData(int $currentPage, int $countElements, int $countElementPerPage): array
    {
        $this->setPaginate($currentPage, $countElements, $countElementPerPage);

        $this->compute();

        $results = [];

        if ($this->usePrevious) {
            $results['previous'] = $this->previous;
        }

        $results['links'] = $this->links;

        if ($this->useNext) {
            $results['next'] = $this->next;
        }

        return $results;
    }

    /**
     * @param int $currentPage
     * @param int $countElements
     * @param int $countElementPerPage
     */
    protected function setPaginate(int $currentPage, int $countElements, int $countElementPerPage): void
    {
        $this->currentPage = $currentPage < 1 ? 1 : $currentPage;
        $this->countElements = $countElements < 0 ? 0 : $countElements;
        $this->countElementPerPage = $countElementPerPage < 1 ? 1 : $countElementPerPage;
        $this->maxPages = (int) \ceil($this->countElements / $this->countElementPerPage);
    }

    protected function compute(): void
    {
        $this->previous = null;
        $this->links = [];
        $this->next = null;

        if ($this->countElements === 0) {
            return;
        }

        $this->computePreviousLink();
        $this->computeLinks();
        $this->computeNextLink();
    }

    protected function computePreviousLink(): void
    {
        if (!$this->usePrevious) {
            return;
        }

        if ($this->currentPage < 2) {
            return;
        }

        $page = $this->currentPage - 1;
        if ($this->currentPage > $this->maxPages) {
            $page = $this->maxPages;
        }

        $this->previous = [
            'href' => $this->getLink($page),
            'text' => $this->textPrevious
        ];
    }

    protected function computeLinks(): void
    {
        if ($this->maxPages < 2) {
            return;
        }

        for ($i = 1; $i <= $this->maxPages; ++$i) {
            $href = $this->getLink($i);

            if ($i === $this->currentPage) {
                $this->links[] = $this->generateLinkData($href, $i, true);
                continue;
            }

            if ($this->showAllLinks || $this->isLimit($i) || $this->isAdjacent($i)) {
                $this->links[] = $this->generateLinkData($href, $i);
                continue;
            }

            if ($this->useDots) {
                $this->links[] = $this->generateLinkData('#', $this->textDots, false, true);
            }
        }
    }

    /**
     * @param int $i
     *
     * @return bool
     */
    protected function isLimit(int $i): bool
    {
        $inLimitLeft = $i <= $this->countPagesPairLimit;
        $inLimitRight = $i > $this->maxPages - $this->countPagesPairLimit;

        return $inLimitLeft || $inLimitRight;
    }

    /**
     * @param int $i
     *
     * @return bool
     */
    protected function isAdjacent(int $i): bool
    {
        $inIntervalLeft = $i >= $this->currentPage - $this->countPagesPairAdjacent;
        $inIntervalRight = $i <= $this->currentPage + $this->countPagesPairAdjacent;

        return $inIntervalLeft && $inIntervalRight;
    }

    protected function computeNextLink(): void
    {
        if (!$this->useNext) {
            return;
        }

        if ($this->currentPage >= $this->maxPages) {
            return;
        }

        $this->next = [
            'href' => $this->getLink($this->currentPage + 1),
            'text' => $this->textNext
        ];
    }

    /**
     * @param      $href
     * @param      $text
     * @param bool $current
     * @param bool $dots
     *
     * @return array
     */
    protected function generateLinkData($href, $text, bool $current = false, bool $dots = false): array
    {
        return [
            'dots'    => $dots,
            'current' => $current,
            'href'    => (string) $href,
            'text'    => (string) $text
        ];
    }

    /**
     * @param int $page
     *
     * @return string
     */
    protected function getLink(int $page): string
    {
        return $this->baseLink . $page . $this->baseLinkAfter;
    }

    /**
     * @return string
     */
    protected function getHtml(): string
    {
        $html = '<' . $this->rootTag . ' ' . $this->rootAttrs . '>';

        $html .= $this->generatePreviousLink();

        foreach ($this->links as $link) {
            $html .= $this->generateLink($link);
        }

        $html .= $this->generateNextLink();

        $html .= '</' . $this->rootTag . '>';

        return $html;
    }

    /**
     * @return string
     */
    protected function generatePreviousLink(): string
    {
        if ($this->previous === null) {
            return '';
        }

        return $this->generateLinkFactory([
            'itemAttrs'  => $this->itemAttrs,
            'linkAttrs'  => $this->linkAttrs,
            'href'       => $this->previous['href'],
            'text'       => $this->previous['text'],
            'aria-label' => 'aria-label="' . $this->textPrevious . '"'
        ]);
    }

    /**
     * @param array $link
     *
     * @return string
     */
    protected function generateLink(array $link): string
    {
        return $this->generateLinkFactory([
            'itemAttrs'  => (($link['current']) ? $this->itemAttrsCurrent : $this->itemAttrs),
            'linkAttrs'  => (($link['current']) ? $this->linkAttrsCurrent : $this->linkAttrs),
            'href'       => $link['href'],
            'text'       => $link['text'],
            'aria-label' => ''
        ]);
    }

    /**
     * @return string
     */
    protected function generateNextLink(): string
    {
        if ($this->previous === null) {
            return '';
        }

        return $this->generateLinkFactory([
            'itemAttrs'  => $this->itemAttrs,
            'linkAttrs'  => $this->linkAttrs,
            'href'       => $this->next['href'],
            'text'       => $this->next['text'],
            'aria-label' => 'aria-label="' . $this->textNext . '"'
        ]);
    }

    /**
     * @param array $infos
     *
     * @return string
     */
    protected function generateLinkFactory(array $infos): string
    {
        $html = '<' . $this->itemTag . ' ' . $infos['itemAttrs'] . '>';

        $html .= '<' . $this->linkTag . ' ' . $infos['linkAttrs'] . ' href="' . $infos['href'] . '" ';

        $html .= $infos['aria-label'] . ' title="' . $infos['text'] . '">' . $infos['text'];

        $html .= '</' . $this->linkTag . '>';

        $html .= '</' . $this->itemTag . '>';

        return $html;
    }
}
