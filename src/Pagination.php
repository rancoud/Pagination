<?php

declare(strict_types=1);

namespace Rancoud\Pagination;

use Rancoud\Security\Security;
use Rancoud\Security\SecurityException;

/**
 * Class Pagination.
 */
class Pagination
{
    // region Properties: Calculations
    /** @var int Current page */
    protected int $currentPage = 1;

    /** @var int Number of "elements" */
    protected int $countElements = 0;

    /** @var int Number of "elements" per page */
    protected int $countElementPerPage = 0;

    /** @var int Maximum pages */
    protected int $maxPages = 0;

    /** @var int Number of pair pages at the limit */
    protected int $countPagesPairLimit = 0;

    /** @var int Number of pair pages next to the current page */
    protected int $countPagesPairAdjacent = 2;
    // endregion

    // region Properties: Links
    /** @var string Base URL */
    protected string $url = '';
    // endregion

    // region Properties: Texts
    /** @var string Text for previous page */
    protected string $textPrevious = 'Previous page';

    /** @var string Text for next page */
    protected string $textNext = 'Next page';

    /** @var string Text for dots */
    protected string $textDots = '…';

    /** @var string Text for page */
    protected string $textPage = '{{PAGE}}';

    /** @var string Text for aria label on link */
    protected string $ariaLabelLink = 'Page {{PAGE}}';

    /** @var string Text for aria label on current link */
    protected string $ariaLabelCurrentLink = 'Page {{PAGE}}';

    /** @var string Text for aria label on nav HTML tag */
    protected string $ariaLabelNav = 'Pagination';

    /** @var string Text for aria label on previous */
    protected string $ariaLabelPrevious = 'Previous page';

    /** @var string Text for aria label on next */
    protected string $ariaLabelNext = 'Next page';

    /** @var string Text for thousands separator */
    protected string $thousandsSeparator = '';
    // endregion

    // region Properties: Generation
    /** @var Item|null Previous item */
    protected ?Item $previous = null;

    /** @var Item[] List of items */
    protected array $items = [];

    /** @var Item|null Next item */
    protected ?Item $next = null;

    /** @var bool Is use dots */
    protected bool $useDots = false;

    /** @var bool Is use previous */
    protected bool $usePrevious = false;

    /** @var bool Is always use previous */
    protected bool $alwaysUsePrevious = false;

    /** @var bool Is use next */
    protected bool $useNext = false;

    /** @var bool Is always use next */
    protected bool $alwaysUseNext = false;

    /** @var bool Is show all links */
    protected bool $showAllLinks = false;

    /** @var bool Is use pretty HTML */
    protected bool $usePrettyHtml = true;

    /** @var int Initial indentation for HTML rendering */
    protected int $htmlInitialIndentation = 0;

    /** @var string Text for HTML tab sequence */
    protected string $htmlTabSequence = "\t";

    /** @var bool Is use nav HTML tag */
    protected bool $useNav = true;
    // endregion

    // region Properties: Tags Parameters
    /** @var string Nav HTML attributes */
    protected string $navAttrs = '';

    /** @var string Root list HTML tag */
    protected string $rootTag = 'ul';

    /** @var string Root list HTML attributes */
    protected string $rootAttrs = '';

    /** @var string Item HTML tag */
    protected string $itemTag = 'li';

    /** @var string Item HTML attributes */
    protected string $itemAttrs = '';

    /** @var string Current item HTML attributes */
    protected string $itemAttrsCurrent = '';

    /** @var string Next item HTML attributes */
    protected string $itemNextAttrs = '';

    /** @var string Next item HTML attributes when disabled */
    protected string $itemNextAttrsDisabled = '';

    /** @var string Previous item HTML attributes */
    protected string $itemPreviousAttrs = '';

    /** @var string Previous item HTML attributes when disabled */
    protected string $itemPreviousAttrsDisabled = '';

    /** @var string Item dots HTML attributes */
    protected string $itemDotsAttrs = '';

    /** @var string Link HTML tag */
    protected string $linkTag = 'a';

    /** @var string Link HTML attributes */
    protected string $linkAttrs = '';

    /** @var string Current link HTML attributes */
    protected string $linkAttrsCurrent = '';

    /** @var string Next link HTML attributes when disabled */
    protected string $linkNextAttrsDisabled = '';

    /** @var string Previous link HTML attributes when disabled */
    protected string $linkPreviousAttrsDisabled = '';

    /** @var string Dot item HTML tag */
    protected string $dotTag = 'span';

    /** @var string Dot item HTML attributes */
    protected string $dotAttrs = '';
    // endregion

    // region Properties: Security
    /** @var bool Escape attributes */
    protected bool $escAttr = true;

    /** @var bool Escape HTML */
    protected bool $escHtml = true;

    /** @var string Charset for escaping HTML and HTML attributes */
    protected string $charset = 'UTF-8';
    // endregion

    // region Configuration keys
    /** @var array List of strings values according to prop name */
    protected array $propsString = [
        'url'                          => 'url',
        'text_previous'                => 'textPrevious',
        'text_next'                    => 'textNext',
        'text_dots'                    => 'textDots',
        'text_page'                    => 'textPage',
        'nav_attrs'                    => 'navAttrs',
        'root_tag'                     => 'rootTag',
        'root_attrs'                   => 'rootAttrs',
        'item_tag'                     => 'itemTag',
        'item_attrs'                   => 'itemAttrs',
        'item_attrs_current'           => 'itemAttrsCurrent',
        'item_next_attrs'              => 'itemNextAttrs',
        'item_next_attrs_disabled'     => 'itemNextAttrsDisabled',
        'item_previous_attrs'          => 'itemPreviousAttrs',
        'item_previous_attrs_disabled' => 'itemPreviousAttrsDisabled',
        'item_dots_attrs'              => 'itemDotsAttrs',
        'link_tag'                     => 'linkTag',
        'link_attrs'                   => 'linkAttrs',
        'link_attrs_current'           => 'linkAttrsCurrent',
        'link_next_attrs_disabled'     => 'linkNextAttrsDisabled',
        'link_previous_attrs_disabled' => 'linkPreviousAttrsDisabled',
        'dot_tag'                      => 'dotTag',
        'dot_attrs'                    => 'dotAttrs',
        'html_tab_sequence'            => 'htmlTabSequence',
        'aria_label_link'              => 'ariaLabelLink',
        'aria_label_current_link'      => 'ariaLabelCurrentLink',
        'aria_label_nav'               => 'ariaLabelNav',
        'aria_label_previous'          => 'ariaLabelPrevious',
        'aria_label_next'              => 'ariaLabelNext',
        'thousands_separator'          => 'thousandsSeparator',
        'charset'                      => 'charset'
    ];

    /** @var array List of bools values according to prop name */
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

    /** @var array List of integers values according to prop name */
    protected array $propsPositiveInteger = [
        'count_pages_pair_limit'    => 'countPagesPairLimit',
        'count_pages_pair_adjacent' => 'countPagesPairAdjacent',
        'html_initial_indentation'  => 'htmlInitialIndentation',
    ];
    // endregion

    /**
     * Pagination constructor.<br>
     * It calls $this->setConfiguration.
     *
     * @param array $configuration Parameters for changing pagination behavior
     */
    public function __construct(array $configuration = [])
    {
        $this->setConfiguration($configuration);
    }

    /**
     * For changing pagination behavior.<br>
     * Checks for each valid props:
     * - when using string: force string type
     * - when using tag: use regex /^[a-zA-Z-]+$/i otherwise ignored
     * - when using bool: force bool type
     * - when using int: force int type and check if value is equal or greater than 0, otherwise use 0.
     *
     * @param array $configuration Parameters for changing pagination behavior
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
     * Computes number of pages.<br>
     * The formula is ceil($countElements / $countElementPerPage).
     */
    public static function countPages(int $countElements, int $countElementPerPage): int
    {
        $countElements = \max($countElements, 0);
        $countElementPerPage = \max($countElementPerPage, 1);

        return (int) \ceil($countElements / $countElementPerPage);
    }

    /**
     * Finds the page where the item index is located.<br>
     * The formula is ceil($itemIndex / $countElementPerPage).
     */
    public static function locateItemInPage(int $countElementPerPage, int $itemIndex): int
    {
        $countElementPerPage = \max($countElementPerPage, 1);
        $itemIndex = \max($itemIndex, 0);

        return (int) \ceil($itemIndex / $countElementPerPage);
    }

    /**
     * Generates HTML pagination.
     *
     * @throws PaginationException
     */
    public function generateHtml(int $currentPage, int $countElements, int $countElementPerPage): string
    {
        $this->setPaginate($currentPage, $countElements, $countElementPerPage);

        $this->compute();

        return $this->getHtml();
    }

    /**
     * Generates pagination and returns as array.
     *
     * @throws PaginationException
     */
    public function generateData(int $currentPage, int $countElements, int $countElementPerPage): array
    {
        $this->setPaginate($currentPage, $countElements, $countElementPerPage);

        $this->compute();

        $results = [];

        if ($this->usePrevious || $this->alwaysUsePrevious) {
            $results['previous'] = $this->previous;
        }

        $results['links'] = $this->items;

        if ($this->useNext || $this->alwaysUseNext) {
            $results['next'] = $this->next;
        }

        return $results;
    }

    /**
     * Set pagination data:
     * - $this->currentPage
     * - $this->countElements
     * - $this->countElementPerPage
     * - $this->maxPages.
     */
    protected function setPaginate(int $currentPage, int $countElements, int $countElementPerPage): void
    {
        $this->currentPage = \max($currentPage, 1);
        $this->countElements = \max($countElements, 0);
        $this->countElementPerPage = \max($countElementPerPage, 1);
        $this->maxPages = (int) \ceil($this->countElements / $this->countElementPerPage);
    }

    /**
     * Computes items:
     * - $this->previous
     * - $this->items
     * - $this->next
     *
     * @throws PaginationException
     */
    protected function compute(): void
    {
        $this->previous = null;
        $this->items = [];
        $this->next = null;

        if ($this->countElements === 0) {
            return;
        }

        $this->computePreviousItem();
        $this->computeItems();
        $this->computeNextItem();
    }

    /**
     * Computes previous item.
     *
     * @throws PaginationException
     */
    protected function computePreviousItem(): void
    {
        if (!$this->alwaysUsePrevious && !$this->usePrevious) {
            return;
        }

        if (!$this->alwaysUsePrevious && $this->currentPage < 2) {
            return;
        }

        $this->previous = new Item();
        $this->previous->itemAttrs = $this->itemPreviousAttrs;
        $this->previous->linkAttrs = $this->linkAttrs;

        $this->previous->page = $this->currentPage - 1;
        if ($this->currentPage > $this->maxPages) {
            $this->previous->page = $this->maxPages;
        }

        $this->previous->href = ($this->previous->page === 0) ? '#' : $this->getURL($this->previous->page);
        if ($this->previous->href === '#' || $this->previous->href === '&#x23;') {
            $this->previous->isDisabled = true;
            $this->previous->itemAttrs = $this->itemPreviousAttrsDisabled;
            $this->previous->linkAttrs = $this->linkPreviousAttrsDisabled;
        }

        $this->previous->text = $this->textPrevious;
        if ($this->escHtml) {
            try {
                $this->previous->text = Security::escHTML($this->textPrevious, $this->charset);
            } catch (SecurityException $e) {
                throw new PaginationException('could not escHTML "previous" text: ' . $e->getMessage());
            }
        }

        $this->previous->ariaLabel = $this->ariaLabelPrevious;
        $this->previous->ariaLabel = \str_replace('{{PAGE}}', (string) $this->previous->page, $this->previous->ariaLabel); // phpcs:ignore
        if ($this->escAttr) {
            try {
                $this->previous->ariaLabel = Security::escAttr($this->previous->ariaLabel, $this->charset);
                $this->previous->href = Security::escAttr($this->previous->href, $this->charset);
            } catch (SecurityException $e) {
                throw new PaginationException('could not escAttr "previous" aria label or "previous" href: ' . $e->getMessage()); // phpcs:ignore
            }
        }

        $this->previous->itemAttrs = \str_replace('{{PAGE}}', (string) $this->previous->page, $this->previous->itemAttrs); // phpcs:ignore
        $this->previous->linkAttrs = \str_replace('{{PAGE}}', (string) $this->previous->page, $this->previous->linkAttrs); // phpcs:ignore
    }

    /**
     * Computes next item.
     *
     * @throws PaginationException
     */
    protected function computeNextItem(): void
    {
        if (!$this->alwaysUseNext && !$this->useNext) {
            return;
        }

        if (!$this->alwaysUseNext && $this->currentPage >= $this->maxPages) {
            return;
        }

        $this->next = new Item();
        $this->next->itemAttrs = $this->itemNextAttrs;
        $this->next->linkAttrs = $this->linkAttrs;

        $this->next->page = $this->currentPage + 1;
        if ($this->currentPage >= $this->maxPages) {
            $this->next->page = $this->maxPages;
        }

        $this->next->href = ($this->currentPage === $this->maxPages) ? '#' : $this->getURL($this->currentPage + 1);
        if ($this->next->href === '#' || $this->next->href === '&#x23;') {
            $this->next->isDisabled = true;
            $this->next->itemAttrs = $this->itemNextAttrsDisabled;
            $this->next->linkAttrs = $this->linkNextAttrsDisabled;
        }

        $this->next->text = $this->textNext;
        if ($this->escHtml) {
            try {
                $this->next->text = Security::escHTML($this->next->text, $this->charset);
            } catch (SecurityException $e) {
                throw new PaginationException('could not escHTML "next" text: ' . $e->getMessage());
            }
        }

        $this->next->ariaLabel = $this->ariaLabelNext;
        $this->next->ariaLabel = \str_replace('{{PAGE}}', (string) $this->next->page, $this->next->ariaLabel);
        if ($this->escAttr) {
            try {
                $this->next->ariaLabel = Security::escAttr($this->next->ariaLabel, $this->charset);
                $this->next->href = Security::escAttr($this->next->href, $this->charset);
            } catch (SecurityException $e) {
                throw new PaginationException('could not escAttr "next" aria label or "next" href: ' . $e->getMessage()); // phpcs:ignore
            }
        }

        $this->next->itemAttrs = \str_replace('{{PAGE}}', (string) $this->next->page, $this->next->itemAttrs);
        $this->next->linkAttrs = \str_replace('{{PAGE}}', (string) $this->next->page, $this->next->linkAttrs);
    }

    /**
     * Computes items.
     *
     * @throws PaginationException
     */
    protected function computeItems(): void
    {
        $canAddDot = true;
        for ($idxPages = 1; $idxPages <= $this->maxPages; ++$idxPages) {
            if ($idxPages === $this->currentPage) {
                $this->items[] = $this->computeItem($idxPages, true);
                $canAddDot = true;

                continue;
            }

            if ($this->showAllLinks || $this->isLimit($idxPages) || $this->isAdjacent($idxPages)) {
                $this->items[] = $this->computeItem($idxPages);
                $canAddDot = true;

                continue;
            }

            if ($this->useDots && $canAddDot) {
                $this->items[] = $this->computeItem($idxPages, false, true);
                $canAddDot = false;
            }
        }
    }

    /** Returns if page is limit. */
    protected function isLimit(int $page): bool
    {
        $inLimitLeft = $page <= $this->countPagesPairLimit;
        $inLimitRight = $page > $this->maxPages - $this->countPagesPairLimit;

        return $inLimitLeft || $inLimitRight;
    }

    /** Returns if page is adjacent. */
    protected function isAdjacent(int $page): bool
    {
        $inIntervalLeft = $page >= $this->currentPage - $this->countPagesPairAdjacent;
        $inIntervalRight = $page <= $this->currentPage + $this->countPagesPairAdjacent;

        return $inIntervalLeft && $inIntervalRight;
    }

    /**
     * Computes item.
     *
     * @throws PaginationException
     */
    protected function computeItem(int $page, bool $isCurrent = false, bool $isDots = false): Item
    {
        $item = new Item();
        $item->isCurrent = $isCurrent;
        $item->isDots = $isDots;
        $item->page = $page;

        if ($isCurrent) {
            $item->href = '#';

            $item->itemAttrs = $this->itemAttrsCurrent;
            $item->linkAttrs = $this->linkAttrsCurrent;
            $item->ariaLabel = $this->ariaLabelCurrentLink;
        } else {
            $item->href = $this->getURL($page);

            $item->itemAttrs = $this->itemAttrs;
            $item->linkAttrs = $this->linkAttrs;
            $item->ariaLabel = $this->ariaLabelLink;
        }

        $item->itemAttrs = \str_replace('{{PAGE}}', (string) $page, $item->itemAttrs);
        $item->linkAttrs = \str_replace('{{PAGE}}', (string) $page, $item->linkAttrs);
        $item->ariaLabel = \str_replace('{{PAGE}}', (string) $page, $item->ariaLabel);

        if ($isDots) {
            $item->text = $this->textDots;
            $item->itemAttrs = \str_replace('{{PAGE}}', (string) $page, $this->itemDotsAttrs);
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
            try {
                $item->text = Security::escHTML($item->text, $this->charset);
            } catch (SecurityException $e) {
                throw new PaginationException('could not escHTML "item" text: ' . $e->getMessage());
            }
        }

        if ($this->escAttr) {
            try {
                $item->ariaLabel = Security::escAttr($item->ariaLabel, $this->charset);
                $item->href = Security::escAttr($item->href, $this->charset);
            } catch (SecurityException $e) {
                throw new PaginationException('could not escAttr "item" aria label or "item" href: ' . $e->getMessage()); // phpcs:ignore
            }
        }

        return $item;
    }

    /** Returns URL. */
    protected function getURL(int $page): string
    {
        if ($this->url === '') {
            return (string) $page;
        }

        $url = \str_replace('{{PAGE}}', (string) $page, $this->url);

        if ($url === $this->url) {
            return $url . $page;
        }

        return $url;
    }

    /**
     * Returns pagination HTML output.
     *
     * @throws PaginationException
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
                try {
                    $ariaLabelNav = Security::escAttr($this->ariaLabelNav);
                } catch (SecurityException $e) {
                    throw new PaginationException('could not escAttr "nav" aria label: ' . $e->getMessage());
                }
            }

            $nav = '<nav';
            $nav .= $ariaLabelNav !== '' ? ' aria-label="' . $ariaLabelNav . '"' : '';
            $nav .= $this->navAttrs !== '' ? ' ' . $this->navAttrs : '';
            $nav .= '>';

            $html .= $tab . $nav . $endl;
            $tab = $this->getTabSequence();
        }

        $html .= $tab . '<' . $this->rootTag;
        if ($this->rootAttrs !== '') {
            $html .= ' ' . $this->rootAttrs;
        }
        $html .= '>' . $endl;

        $html .= $this->generateLinkFactory($this->previous);

        foreach ($this->items as $link) {
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

    /** Returns item HTML output. */
    protected function generateLinkFactory(?Item $item): string
    {
        if ($item === null) {
            return '';
        }

        $tab1 = $this->getTabSequence(1);
        $tab2 = $this->getTabSequence(2);
        $endl = $this->getEndlineSequence();

        $openItemTag = '<' . $this->itemTag;
        $openItemTag .= $item->itemAttrs !== '' ? ' ' . $item->itemAttrs : '';
        $openItemTag .= $item->isDots ? ' aria-hidden="true">' : '>';
        $closeItemTag = '</' . $this->itemTag . '>';

        if ($item->isDots) {
            $openLinkTag = '<' . $this->dotTag;
            $openLinkTag .= $this->dotAttrs !== '' ? ' ' . $this->dotAttrs : '';
            $openLinkTag .= '>' . $item->text;
            $closeLinkTag = '</' . $this->dotTag . '>';
        } else {
            $openLinkTag = '<' . $this->linkTag;
            $openLinkTag .= $item->linkAttrs !== '' ? ' ' . $item->linkAttrs : '';
            $openLinkTag .= $item->href !== '' ? ' href="' . $item->href . '"' : '';
            $openLinkTag .= $item->ariaLabel !== '' ? ' aria-label="' . $item->ariaLabel . '"' : '';
            $openLinkTag .= $item->isDisabled ? ' aria-disabled="true"' : '';
            $openLinkTag .= $item->isCurrent ? ' aria-current="page"' : '';
            $openLinkTag .= '>' . $item->text;
            $closeLinkTag = '</' . $this->linkTag . '>';
        }

        $html = $tab1 . $openItemTag . $endl;
        $html .= $tab2 . $openLinkTag . $closeLinkTag . $endl;
        $html .= $tab1 . $closeItemTag . $endl;

        return $html;
    }

    /**
     * Returns tab sequence HTML output.<br>
     *  If `usePrettyHtml` return `htmlTabSequence` with the current indentation else return `''`.
     */
    protected function getTabSequence(int $currentIndent = 0): string
    {
        if (!$this->usePrettyHtml) {
            return '';
        }

        return \str_repeat($this->htmlTabSequence, $this->htmlInitialIndentation + $currentIndent);
    }

    /**
     * Returns endline sequence HTML output.<br>
     * If `usePrettyHtml` return PHP_EOL else return `''`.
     */
    protected function getEndlineSequence(): string
    {
        if (!$this->usePrettyHtml) {
            return '';
        }

        return \PHP_EOL;
    }
}
