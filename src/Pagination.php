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
    protected int $currentPage = 1;
    protected int $countElements = 0;
    protected int $countElementPerPage = 0;
    protected int $maxPages = 0;
    protected int $countPagesPairLimit = 0;
    protected int $countPagesPairAdjacent = 2;
    // endregion

    // region Properties: Links
    protected string $url = '';
    // endregion

    // region Properties: Texts
    protected string $textPrevious = 'Previous page';
    protected string $textNext = 'Next page';
    protected string $textDots = '…';
    protected string $textPage = '{{PAGE}}';
    protected string $ariaLabelLink = 'Page {{PAGE}}';
    protected string $ariaLabelCurrentLink = 'Page {{PAGE}}';
    protected string $ariaLabelNav = 'Pagination';
    protected string $ariaLabelPrevious = 'Previous page';
    protected string $ariaLabelNext = 'Next page';
    protected string $thousandsSeparator = '';
    // endregion

    // region Properties: Generation
    protected ?Link $previous = null;
    protected array $links = [];
    protected ?Link $next = null;
    protected bool $useDots = false;
    protected bool $usePrevious = false;
    protected bool $alwaysUsePrevious = false;
    protected bool $useNext = false;
    protected bool $alwaysUseNext = false;
    protected bool $showAllLinks = false;
    protected bool $usePrettyHtml = true;
    protected int $htmlInitialIndentation = 0;
    protected string $htmlTabSequence = "\t";
    protected bool $useNav = true;
    // endregion

    // region Properties: Tags Parameters
    protected string $rootTag = 'ul';
    protected string $rootAttrs = '';
    protected string $itemTag = 'li';
    protected string $itemAttrs = '';
    protected string $itemAttrsCurrent = '';
    protected string $itemNextAttrs = '';
    protected string $itemPreviousAttrs = '';
    protected string $itemDotsAttrs = '';
    protected string $linkTag = 'a';
    protected string $linkAttrs = '';
    protected string $linkAttrsCurrent = '';
    // endregion

    // region Properties: Security
    protected bool $escAttr = true;
    protected bool $escHtml = true;
    protected string $charset = 'UTF-8';
    // endregion

    // region Configuration keys
    protected array $propsString = [
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
        'item_dots_attrs'             => 'itemDotsAttrs',
        'link_tag'                    => 'linkTag',
        'link_attrs'                  => 'linkAttrs',
        'link_attrs_current'          => 'linkAttrsCurrent',
        'html_tab_sequence'           => 'htmlTabSequence',
        'aria_label_link'             => 'ariaLabelLink',
        'aria_label_current_link'     => 'ariaLabelCurrentLink',
        'aria_label_nav'              => 'ariaLabelNav',
        'aria_label_previous'         => 'ariaLabelPrevious',
        'aria_label_next'             => 'ariaLabelNext',
        'thousands_separator'         => 'thousandsSeparator',
        'charset'                     => 'charset'
    ];
    protected array $propsBool = [
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
    protected array $propsPositiveInteger = [
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
        $tagsVerification = ['root_tag', 'item_tag', 'link_tag'];

        foreach ($configuration as $key => $value) {
            if (isset($this->propsString[$key])) {
                if (\in_array($key, $tagsVerification, true)) {
                    $valid = \preg_match('/^[a-zA-Z-]+$/i', $value);
                    if (!$valid) {
                        continue;
                    }
                }
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
     * @throws \Rancoud\Security\SecurityException
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
     * @throws \Rancoud\Security\SecurityException
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

    /**
     * @throws \Rancoud\Security\SecurityException
     */
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

    /**
     * @throws \Rancoud\Security\SecurityException
     */
    protected function computePreviousLink(): void
    {
        if (!$this->alwaysUsePrevious && !$this->usePrevious) {
            return;
        }

        if (!$this->alwaysUsePrevious && $this->currentPage < 2) {
            return;
        }

        $this->previous = new Link();

        $this->previous->page = $this->currentPage - 1;
        if ($this->currentPage > $this->maxPages) {
            $this->previous->page = $this->maxPages;
        }

        $this->previous->href = ($this->previous->page === 0) ? '#' : $this->getLink($this->previous->page);
        if ($this->previous->href === '#' || $this->previous->href === '&#x23;') {
            $this->previous->isDisabled = true;
        }

        $this->previous->text = $this->textPrevious;
        if ($this->escHtml) {
            $this->previous->text = Security::escHtml($this->textPrevious, $this->charset);
        }

        $this->previous->ariaLabel = $this->ariaLabelPrevious;
        $this->previous->ariaLabel = \str_replace('{{PAGE}}', $this->previous->page, $this->previous->ariaLabel);
        if ($this->escAttr) {
            $this->previous->ariaLabel = Security::escAttr($this->previous->ariaLabel, $this->charset);
            $this->previous->href = Security::escAttr($this->previous->href, $this->charset);
        }

        $this->previous->itemAttrs = \str_replace('{{PAGE}}', $this->previous->page, $this->itemPreviousAttrs);
        $this->previous->linkAttrs = \str_replace('{{PAGE}}', $this->previous->page, $this->linkAttrs);
    }

    /**
     * @throws \Rancoud\Security\SecurityException
     */
    protected function computeNextLink(): void
    {
        if (!$this->alwaysUseNext && !$this->useNext) {
            return;
        }

        if (!$this->alwaysUseNext && $this->currentPage >= $this->maxPages) {
            return;
        }

        $this->next = new Link();

        $this->next->page = $this->currentPage + 1;
        if ($this->currentPage >= $this->maxPages) {
            $this->next->page = $this->maxPages;
        }

        $this->next->href = ($this->currentPage === $this->maxPages) ? '#' : $this->getLink($this->currentPage + 1);
        if ($this->next->href === '#' || $this->next->href === '&#x23;') {
            $this->next->isDisabled = true;
        }

        $this->next->text = $this->textNext;
        if ($this->escHtml) {
            $this->next->text = Security::escHtml($this->next->text, $this->charset);
        }

        $this->next->ariaLabel = $this->ariaLabelNext;
        $this->next->ariaLabel = \str_replace('{{PAGE}}', $this->next->page, $this->next->ariaLabel);
        if ($this->escAttr) {
            $this->next->ariaLabel = Security::escAttr($this->next->ariaLabel, $this->charset);
            $this->next->href = Security::escAttr($this->next->href, $this->charset);
        }

        $this->next->itemAttrs = \str_replace('{{PAGE}}', $this->next->page, $this->itemNextAttrs);
        $this->next->linkAttrs = \str_replace('{{PAGE}}', $this->next->page, $this->linkAttrs);
    }

    /**
     * @throws \Rancoud\Security\SecurityException
     */
    protected function computeLinks(): void
    {
        $canAddDot = true;
        for ($idxPages = 1; $idxPages <= $this->maxPages; ++$idxPages) {
            if ($idxPages === $this->currentPage) {
                $this->links[] = $this->computeLink($idxPages, true);
                $canAddDot = true;
                continue;
            }

            if ($this->showAllLinks || $this->isLimit($idxPages) || $this->isAdjacent($idxPages)) {
                $this->links[] = $this->computeLink($idxPages);
                $canAddDot = true;
                continue;
            }

            if ($this->useDots && $canAddDot) {
                $this->links[] = $this->computeLink($idxPages, false, true);
                $canAddDot = false;
            }
        }
    }

    /**
     * @param int $page
     *
     * @return bool
     */
    protected function isLimit(int $page): bool
    {
        $inLimitLeft = $page <= $this->countPagesPairLimit;
        $inLimitRight = $page > $this->maxPages - $this->countPagesPairLimit;

        return $inLimitLeft || $inLimitRight;
    }

    /**
     * @param int $page
     *
     * @return bool
     */
    protected function isAdjacent(int $page): bool
    {
        $inIntervalLeft = $page >= $this->currentPage - $this->countPagesPairAdjacent;
        $inIntervalRight = $page <= $this->currentPage + $this->countPagesPairAdjacent;

        return $inIntervalLeft && $inIntervalRight;
    }

    /**
     * @param int  $page
     * @param bool $isCurrent
     * @param bool $isDots
     *
     *@throws \Rancoud\Security\SecurityException
     *
     * @return Link
     */
    protected function computeLink(int $page, bool $isCurrent = false, bool $isDots = false): Link
    {
        $item = new Link();
        $item->isCurrent = $isCurrent;
        $item->isDots = $isDots;
        $item->page = $page;

        if ($isCurrent) {
            $item->href = '#';

            $item->itemAttrs = $this->itemAttrsCurrent;
            $item->linkAttrs = $this->linkAttrsCurrent;
            $item->ariaLabel = $this->ariaLabelCurrentLink;
        } else {
            $item->href = $this->getLink($page);

            $item->itemAttrs = $this->itemAttrs;
            $item->linkAttrs = $this->linkAttrs;
            $item->ariaLabel = $this->ariaLabelLink;
        }

        $item->itemAttrs = \str_replace('{{PAGE}}', $page, $item->itemAttrs);
        $item->linkAttrs = \str_replace('{{PAGE}}', $page, $item->linkAttrs);
        $item->ariaLabel = \str_replace('{{PAGE}}', $page, $item->ariaLabel);

        if ($isDots) {
            $item->text = $this->textDots;
            $item->itemAttrs = \str_replace('{{PAGE}}', $page, $this->itemDotsAttrs);
        } else {
            $pageFormated = \number_format($page, 0, '.', $this->thousandsSeparator);
            $item->text = \str_replace('{{PAGE}}', $pageFormated, $this->textPage);
            if ($item->text === $this->textPage) {
                $sep = '';
                if ($item->text !== '') {
                    $sep = ' ';
                }
                $item->text .= $sep . $pageFormated;
            }
        }

        if ($this->escHtml) {
            $item->text = Security::escHtml($item->text, $this->charset);
        }

        if ($this->escAttr) {
            $item->ariaLabel = Security::escAttr($item->ariaLabel, $this->charset);
            $item->href = Security::escAttr($item->href, $this->charset);
        }

        return $item;
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
     * @throws \Rancoud\Security\SecurityException
     *
     * @return string
     */
    protected function getHtml(): string
    {
        $html = '';
        $tab = $this->getTabSequence();
        $endl = $this->getEndlineSequence();

        if ($this->useNav) {
            ++$this->htmlInitialIndentation;

            $ariaLabelNav = $this->ariaLabelNav;
            if ($this->escAttr) {
                $ariaLabelNav = Security::escAttr($this->ariaLabelNav);
            }
            if ($ariaLabelNav !== '') {
                $ariaLabelNav = ' aria-label="' . $ariaLabelNav . '"';
            }

            $html .= $tab . '<nav' . $ariaLabelNav . '>' . $endl;
            $tab = $this->getTabSequence();
        }

        $html .= $tab . '<' . $this->rootTag;
        if ($this->rootAttrs !== '') {
            $html .= ' ' . $this->rootAttrs;
        }
        $html .= '>' . $endl;

        $html .= $this->generateLinkFactory($this->previous);

        foreach ($this->links as $link) {
            $html .= $this->generateLinkFactory($link);
        }

        $html .= $this->generateLinkFactory($this->next);

        $html .= $tab . '</' . $this->rootTag . '>';

        if ($this->useNav) {
            --$this->htmlInitialIndentation;
            $tab = $this->getTabSequence();
            $html .= $endl . $tab . '</nav>';
        }

        return $html;
    }

    /**
     * @param Link $link
     *
     * @return string
     */
    protected function generateLinkFactory(?Link $link): string
    {
        if ($link === null) {
            return '';
        }

        $tab1 = $this->getTabSequence(1);
        $tab2 = $this->getTabSequence(2);
        $endl = $this->getEndlineSequence();

        $openItemTag = '<' . $this->itemTag;
        $openItemTag .= $link->itemAttrs !== '' ? ' ' . $link->itemAttrs : '';
        $openItemTag .= $link->isDots ? ' aria-hidden="true">' : '>';
        $closeItemTag = '</' . $this->itemTag . '>';

        if ($link->isDots) {
            $openLinkTag = '<span';
            $openLinkTag .= '>' . $link->text;
            $closeLinkTag = '</span>';
        } else {
            $openLinkTag = '<' . $this->linkTag;
            $openLinkTag .= $link->linkAttrs !== '' ? ' ' . $link->linkAttrs : '';
            $openLinkTag .= $link->href !== '' ? ' href="' . $link->href . '"' : '';
            $openLinkTag .= $link->ariaLabel !== '' ? ' aria-label="' . $link->ariaLabel . '"' : '';
            $openLinkTag .= $link->isDisabled ? ' aria-disabled="true"' : '';
            $openLinkTag .= $link->isCurrent ? ' aria-current="page"' : '';
            $openLinkTag .= '>' . $link->text;
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
