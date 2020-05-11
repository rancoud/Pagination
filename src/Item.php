<?php

declare(strict_types=1);

namespace Rancoud\Pagination;

/**
 * Class Item.
 */
class Item
{
    public string $ariaLabel = '';
    public string $href = '';
    public string $itemAttrs = '';
    public string $linkAttrs = '';
    public string $text = '';

    public bool $ariaCurrent = false;
    public bool $isCurrent = false;
    public bool $isDots = false;

    public int $page = 0;
}
