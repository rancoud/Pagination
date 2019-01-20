<?php

declare(strict_types=1);

namespace Rancoud\Pagination;

use Rancoud\Security\Security;

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
    protected $url = '';
    // endregion

    // region Properties: Texts
    protected $textPrevious = 'Previous page';
    protected $textNext = 'Next page';
    protected $textDots = '…';
    protected $textPage = '{{PAGE}}';
    protected $ariaLabelLink = 'Goto page {{PAGE}}';
    protected $ariaLabelCurrentLink = 'Current page, page {{PAGE}}';
    protected $ariaLabelNav = 'Pagination navigation';
    protected $thousandsSeparator = '';
    // endregion

    // region Properties: Generation
    protected $previous = null;
    protected $links = [];
    protected $next = null;
    protected $useDots = false;
    protected $usePrevious = false;
    protected $alwaysUsePrevious = false;
    protected $useNext = false;
    protected $alwaysUseNext = false;
    protected $showAllLinks = false;
    protected $usePrettyHtml = true;
    protected $htmlInitialIndentation = 0;
    protected $htmlTabSequence = "\t";
    protected $useNav = true;
    // endregion

    // region Properties: Tags Parameters
    protected $rootTag = 'ul';
    protected $rootAttrs = '';
    protected $itemTag = 'li';
    protected $itemAttrs = '';
    protected $itemAttrsCurrent = '';
    protected $itemNextAttrs = '';
    protected $itemPreviousAttrs = '';
    protected $itemDotAttrs = '';
    protected $linkTag = 'a';
    protected $linkAttrs = '';
    protected $linkAttrsCurrent = '';
    // endregion

    // region Properties: Security
    protected $escAttr = true;
    protected $escHtml = true;
    protected $charset = 'UTF-8';
    // endregion

    // region Configuration keys
    protected $propsString = [
        'url'                         => 'url',
        'text_previous'               => 'textPrevious',
        'text_next'                   => 'textNext',
        'text_dots'                   => 'textDots',
        'text_page'                   => 'textPage',
        'root_tag'                    => 'rootTag',
        'root_attrs'                  => 'rootAttrs',
        'item_tag'                    => 'itemTag',
        'item_attrs'                  => 'itemAttrs',
        'item_attrs_current'          => 'itemAttrsCurrent',
        'item_next_attrs'             => 'itemNextAttrs',
        'item_previous_attrs'         => 'itemPreviousAttrs',
        'item_dot_attrs'              => 'itemDotAttrs',
        'link_tag'                    => 'linkTag',
        'link_attrs'                  => 'linkAttrs',
        'link_attrs_current'          => 'linkAttrsCurrent',
        'html_tab_sequence'           => 'htmlTabSequence',
        'aria_label_link'             => 'ariaLabelLink',
        'aria_label_current_link'     => 'ariaLabelCurrentLink',
        'aria_label_nav'              => 'ariaLabelNav',
        'thousands_separator'         => 'thousandsSeparator',
        'charset'                     => 'charset'
    ];
    protected $propsBool = [
        'use_dots'              => 'useDots',
        'use_previous'          => 'usePrevious',
        'always_use_previous'   => 'alwaysUsePrevious',
        'use_next'              => 'useNext',
        'always_use_next'       => 'alwaysUseNext',
        'use_nav'               => 'useNav',
        'show_all_links'        => 'showAllLinks',
        'use_pretty_html'       => 'usePrettyHtml',
        'esc_attr'              => 'escAttr',
        'esc_html'              => 'escHtml'
    ];
    protected $propsPositiveInteger = [
        'count_pages_pair_limit'    => 'countPagesPairLimit',
        'count_pages_pair_adjacent' => 'countPagesPairAdjacent',
        'html_initial_indentation'  => 'htmlInitialIndentation',
    ];
    // endregion

    /**
     * Pagination constructor.
     *
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
     * @param int $countElements
     * @param int $countElementPerPage
     *
     * @return int
     */
    public static function countPages(int $countElements, int $countElementPerPage): int
    {
        $countElements = $countElements < 0 ? 0 : $countElements;
        $countElementPerPage = $countElementPerPage < 1 ? 1 : $countElementPerPage;

        return (int) \ceil($countElements / $countElementPerPage);
    }

    /**
     * @param int $countElementPerPage
     * @param int $itemIndex
     *
     * @return int
     */
    public static function locateItemInPage(int $countElementPerPage, int $itemIndex): int
    {
        $countElementPerPage = $countElementPerPage < 1 ? 1 : $countElementPerPage;
        $itemIndex = $itemIndex < 0 ? 0 : $itemIndex;

        return (int) \ceil($itemIndex / $countElementPerPage);
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

        if ($this->usePrevious || $this->alwaysUsePrevious) {
            $results['previous'] = $this->previous;
        }

        $results['links'] = $this->links;

        if ($this->useNext || $this->alwaysUseNext) {
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
        if (!$this->alwaysUsePrevious && !$this->usePrevious) {
            return;
        }

        if (!$this->alwaysUsePrevious && $this->currentPage < 2) {
            return;
        }

        $page = $this->currentPage - 1;
        if ($this->currentPage > $this->maxPages) {
            $page = $this->maxPages;
        }

        $href = ($page === 0) ? '#' : $this->getLink($page);

        $text = $this->textPrevious;
        if ($this->escHtml) {
            $text = Security::escHtml($this->textPrevious, $this->charset);
        }

        $ariaLabel = $this->textPrevious;
        if ($this->escAttr) {
            $ariaLabel = Security::escAttr($ariaLabel, $this->charset);
        }

        $itemAttrs = $this->itemPreviousAttrs;
        if ($href === '#') {
            $sep = '';
            if (!empty($this->itemPreviousAttrs)) {
                $sep = ' ';
            }
            $itemAttrs = $this->itemPreviousAttrs . $sep . 'data-disabled';
        }

        $itemAttrs = \str_replace('{{PAGE}}', $page, $itemAttrs);
        $linkAttrs = \str_replace('{{PAGE}}', $page, $this->linkAttrs);

        $this->previous = [
            'itemAttrs' => $itemAttrs,
            'linkAttrs' => $linkAttrs,
            'href'      => $href,
            'text'      => $text,
            'page'      => $page,
            'ariaLabel' => 'aria-label="' . $ariaLabel . '"'
        ];
    }

    protected function computeLinks(): void
    {
        $canAddDot = true;
        for ($i = 1; $i <= $this->maxPages; ++$i) {
            if ($i === $this->currentPage) {
                $this->links[] = $this->generateLinkData($i, true);
                $canAddDot = true;
                continue;
            }

            if ($this->showAllLinks || $this->isLimit($i) || $this->isAdjacent($i)) {
                $this->links[] = $this->generateLinkData($i);
                $canAddDot = true;
                continue;
            }

            if ($this->useDots && $canAddDot) {
                $this->links[] = $this->generateLinkData($i, false, true);
                $canAddDot = false;
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
        if (!$this->alwaysUseNext && !$this->useNext) {
            return;
        }

        if (!$this->alwaysUseNext && $this->currentPage >= $this->maxPages) {
            return;
        }

        $page = $this->currentPage + 1;
        if ($this->currentPage >= $this->maxPages) {
            $page = $this->maxPages;
        }

        $href = ($this->currentPage === $this->maxPages) ? '#' : $this->getLink($this->currentPage + 1);

        $text = $this->textNext;
        if ($this->escHtml) {
            $text = Security::escHtml($this->textNext, $this->charset);
        }

        $ariaLabel = $this->textNext;
        if ($this->escAttr) {
            $ariaLabel = Security::escAttr($ariaLabel, $this->charset);
        }

        $itemAttrs = $this->itemNextAttrs;
        if ($href === '#') {
            $sep = '';
            if (!empty($this->itemNextAttrs)) {
                $sep = ' ';
            }
            $itemAttrs = $this->itemNextAttrs . $sep . 'data-disabled';
        }

        $itemAttrs = \str_replace('{{PAGE}}', $page, $itemAttrs);
        $linkAttrs = \str_replace('{{PAGE}}', $page, $this->linkAttrs);

        $this->next = [
            'itemAttrs' => $itemAttrs,
            'linkAttrs' => $linkAttrs,
            'href'      => $href,
            'text'      => $text,
            'page'      => $page,
            'ariaLabel' => 'aria-label="' . $ariaLabel . '"'
        ];
    }

    /**
     * @param int  $page
     * @param bool $current
     * @param bool $dots
     *
     * @return array
     */
    protected function generateLinkData(int $page, bool $current = false, bool $dots = false): array
    {
        $itemAttrs = $this->itemAttrs;
        $linkAttrs = $this->linkAttrs;
        $ariaLabel = $this->ariaLabelLink;
        if ($current) {
            $itemAttrs = $this->itemAttrsCurrent;
            $linkAttrs = $this->linkAttrsCurrent;
            $ariaLabel = $this->ariaLabelCurrentLink;
        }

        $itemAttrs = \str_replace('{{PAGE}}', $page, $itemAttrs);
        $linkAttrs = \str_replace('{{PAGE}}', $page, $linkAttrs);
        $ariaLabel = \str_replace('{{PAGE}}', $page, $ariaLabel);

        $href = $this->getLink($page);
        if ($current) {
            $href = '#';
        }

        $pageFormated = \number_format($page, 0, '.', $this->thousandsSeparator);
        $text = \str_replace('{{PAGE}}', $pageFormated, $this->textPage);
        if ($text === $this->textPage) {
            $text .= ' ' . $pageFormated;
        }

        if ($dots) {
            $text = $this->textDots;
            $itemAttrs = \str_replace('{{PAGE}}', $page, $this->itemDotAttrs);
        }

        if ($this->escHtml) {
            $text = Security::escHtml($text, $this->charset);
        }

        if ($this->escAttr) {
            $ariaLabel = Security::escAttr($ariaLabel, $this->charset);
        }

        return [
            'itemAttrs'    => $itemAttrs,
            'linkAttrs'    => $linkAttrs,
            'dots'         => $dots,
            'current'      => $current,
            'href'         => $href,
            'text'         => $text,
            'page'         => $page,
            'ariaLabel'    => 'aria-label="' . $ariaLabel . '"',
            'ariaCurrent'  => $current,
        ];
    }

    /**
     * @param int $page
     *
     * @return string
     */
    protected function getLink(int $page): string
    {
        if ($this->url === '') {
            return (string) $page;
        }

        $url = \str_replace('{{PAGE}}', $page, $this->url);

        if ($url === $this->url) {
            return $url . $page;
        }

        return $url;
    }

    /**
     * @return string
     */
    protected function getHtml(): string
    {
        $html = '';
        $tab = $this->getTabSequence();
        $endl = $this->getEndlineSequence();

        if ($this->useNav) {
            ++$this->htmlInitialIndentation;
            $html .= $tab . '<nav role="navigation" aria-label="' . $this->ariaLabelNav . '">' . $endl;
            $tab = $this->getTabSequence();
        }

        $html .= $tab . '<' . $this->rootTag;
        if (!empty($this->rootAttrs)) {
            $html .= ' ' . $this->rootAttrs;
        }
        $html .= '>' . $endl;

        $html .= $this->generatePreviousLink();

        foreach ($this->links as $link) {
            $html .= $this->generateLink($link);
        }

        $html .= $this->generateNextLink();

        $html .= $tab . '</' . $this->rootTag . '>';

        if ($this->useNav) {
            --$this->htmlInitialIndentation;
            $tab = $this->getTabSequence();
            $html .= $endl . $tab . '</nav>';
        }

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
            'itemAttrs'    => $this->previous['itemAttrs'],
            'linkAttrs'    => $this->previous['linkAttrs'],
            'href'         => $this->previous['href'],
            'text'         => $this->previous['text'],
            'ariaLabel'    => $this->previous['ariaLabel'],
            'ariaCurrent'  => false,
            'dots'         => false
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
            'itemAttrs'    => $link['itemAttrs'],
            'linkAttrs'    => $link['linkAttrs'],
            'href'         => $link['href'],
            'text'         => $link['text'],
            'ariaLabel'    => $link['ariaLabel'],
            'ariaCurrent'  => $link['ariaCurrent'],
            'dots'         => $link['dots']
        ]);
    }

    /**
     * @return string
     */
    protected function generateNextLink(): string
    {
        if ($this->next === null) {
            return '';
        }

        return $this->generateLinkFactory([
            'itemAttrs'    => $this->next['itemAttrs'],
            'linkAttrs'    => $this->next['linkAttrs'],
            'href'         => $this->next['href'],
            'text'         => $this->next['text'],
            'ariaLabel'    => $this->next['ariaLabel'],
            'ariaCurrent'  => false,
            'dots'         => false
        ]);
    }

    /**
     * @param array $infos
     *
     * @return string
     */
    protected function generateLinkFactory(array $infos): string
    {
        $tab1 = $this->getTabSequence(1);
        $tab2 = $this->getTabSequence(2);
        $endl = $this->getEndlineSequence();

        $openItemTag = '<' . $this->itemTag;
        $openItemTag .= !empty($infos['itemAttrs']) ? ' ' . $infos['itemAttrs'] . '>' : '>';
        $closeItemTag = '</' . $this->itemTag . '>';

        if ($infos['dots']) {
            $openLinkTag = '<span';
            $openLinkTag .= '>' . $infos['text'];
            $closeLinkTag = '</span>';
        } else {
            $openLinkTag = '<' . $this->linkTag;
            $openLinkTag .= !empty($infos['linkAttrs']) ? ' ' . $infos['linkAttrs'] : '';
            $openLinkTag .= !empty($infos['href']) ? ' href="' . $infos['href'] . '"' : '';
            $openLinkTag .= !empty($infos['ariaLabel']) ? ' ' . $infos['ariaLabel'] : '';
            $openLinkTag .= $infos['ariaCurrent'] ? ' aria-current="true"' : '';
            $openLinkTag .= '>' . $infos['text'];
            $closeLinkTag = '</' . $this->linkTag . '>';
        }

        $html = $tab1 . $openItemTag . $endl;
        $html .= $tab2 . $openLinkTag . $closeLinkTag . $endl;
        $html .= $tab1 . $closeItemTag . $endl;

        return $html;
    }

    /**
     * @param int $currentIndent
     *
     * @return string
     */
    protected function getTabSequence(int $currentIndent = 0): string
    {
        if (!$this->usePrettyHtml) {
            return '';
        }

        return \str_repeat($this->htmlTabSequence, $this->htmlInitialIndentation + $currentIndent);
    }

    /**
     * @return string
     */
    protected function getEndlineSequence(): string
    {
        if (!$this->usePrettyHtml) {
            return '';
        }

        return PHP_EOL;
    }
}
