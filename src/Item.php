<?php

declare(strict_types=1);

namespace Rancoud\Pagination;

/**
 * Class Item.
 */
class Item
{
    /**
     * @var string Aria label
     */
    public string $ariaLabel = '';

    /**
     * @var string Href attribute
     */
    public string $href = '';

    /**
     * @var string Item HTML attributes
     */
    public string $itemAttrs = '';

    /**
     * @var string Link HTML attributes
     */
    public string $linkAttrs = '';

    /**
     * @var string Text to display
     */
    public string $text = '';

    /**
     * @var bool Is Current
     */
    public bool $isCurrent = false;

    /**
     * @var bool Is Dots
     */
    public bool $isDots = false;

    /**
     * @var bool Is Disabled
     */
    public bool $isDisabled = false;

    /**
     * @var int Page
     */
    public int $page = 0;
}
