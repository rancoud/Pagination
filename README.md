# Pagination Package

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/rancoud/pagination)
[![Packagist Version](https://img.shields.io/packagist/v/rancoud/pagination)](https://packagist.org/packages/rancoud/pagination)
[![Packagist Downloads](https://img.shields.io/packagist/dt/rancoud/pagination)](https://packagist.org/packages/rancoud/pagination)
[![Composer dependencies](https://img.shields.io/badge/dependencies-1-brightgreen)](https://github.com/rancoud/Pagination/blob/master/composer.json)
[![Test workflow](https://img.shields.io/github/actions/workflow/status/rancoud/pagination/test.yml?branch=master)](https://github.com/rancoud/pagination/actions/workflows/test.yml)
[![Codecov](https://img.shields.io/codecov/c/github/rancoud/pagination?logo=codecov)](https://codecov.io/gh/rancoud/pagination)

Generate HTML pagination for accessibility.

## Dependencies
Security package: [https://github.com/rancoud/Security](https://github.com/rancoud/Security)

## Installation
```php
composer require rancoud/pagination
```

## How to use it?
```php
use Rancoud\Pagination\Pagination;

$currentPage = 1;
$countElements = 10;
$countElementPerPage = 5;

$p = new Pagination();
$html = $p->generateHtml($currentPage, $countElements, $countElementPerPage);
echo $html;
```
It will output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```
## Lexicon
* **Root** represents `<ul>`
* **Item** represents `<li>`
* **Link** represents `<a>`

## Pagination Constructor
### Settings
#### Optionnals
| Parameter     | Type  | Default value | Description                                 |
|---------------|-------|---------------|---------------------------------------------|
| configuration | array | []            | Parameters for changing pagination behavior |

## Pagination Methods
### General Commands
Generates HTML pagination.
```php
public function generateHtml(int $currentPage, int $countElements, int $countElementPerPage): string
```

Generates pagination and returns as array.
```php
public function generateData(int $currentPage, int $countElements, int $countElementPerPage): array
```

For changing pagination behavior.  
Checks for each valid props:
- when using string: force string type
- when using tag: use regex `/^[a-zA-Z-]+$/i` otherwise ignored
- when using bool: force bool type
- when using int: force int type and check if value is equal or greater than 0, otherwise use 0.
```php
public function setConfiguration(array $configuration): void
```

### Static methods
Computes number of pages.  
The formula is `ceil($countElements / $countElementPerPage)`.
```php
public static function countPages(int $countElements, int $countElementPerPage): int
```

Finds the page where the item index is located.  
The formula is `ceil($itemIndex / $countElementPerPage)`.
```php
public static function locateItemInPage(int $countElementPerPage, int $itemIndex): int
```

## Configuration Parameters

### Url
* url (string) ([example](#url-1))

### Behavior
* show_all_links (bool: false) ([example](#show_all_links))
* use_previous (bool: false) ([example](#use_previous))
* always_use_previous (bool: false) ([example](#always_use_previous))
* use_next (bool: false) ([example](#use_next))
* always_use_next (bool: false) ([example](#always_use_next))
* use_dots (bool: false) ([example](#use_dots))
* count_pages_pair_limit (int: 0) ([example](#count_pages_pair_limit))
* count_pages_pair_adjacent (int: 2) ([example](#count_pages_pair_adjacent))

### Labels
* text_previous (string: Previous page) ([example](#text_previous))
* text_next (string: Next page) ([example](#text_next))
* text_dots (string: …) ([example](#text_dots))
* text_page (string: {{PAGE}}) ([example](#text_page))
* aria_label_link (string: Goto page {{PAGE}}) ([example](#aria_label_link))
* aria_label_current_link (string: Current page, page {{PAGE}}) ([example](#aria_label_current_link))
* aria_label_nav (string: Pagination) ([example](#aria_label_nav))
* aria_label_previous (string: Previous page) ([example](#aria_label_previous))
* aria_label_next (string: Next page) ([example](#aria_label_next))
* thousands_separator (string) ([example](#thousands_separator))

### HTML markup
#### Root
* root_tag (string: ul) ([example](#root_tag))
* root_attrs (string) ([example](#root_attrs))
* use_nav (bool: true) ([example](#use_nav))
* nav_attrs (string) ([example](#nav_attrs))

#### Item
* item_tag (string: li) ([example](#item_tag))
* item_attrs (string) ([example](#item_attrs))
* item_attrs_current (string) ([example](#item_attrs_current))
* item_previous_attrs (string) ([example](#item_previous_attrs))
* item_previous_attrs_disabled (string) ([example](#item_previous_attrs_disabled))
* item_next_attrs (string) ([example](#item_next_attrs))
* item_next_attrs_disabled (string) ([example](#item_next_attrs_disabled))
* item_dots_attrs (string) ([example](#item_dots_attrs))

#### Link
* link_tag (string: a) ([example](#link_tag))
* link_attrs (string) ([example](#link_attrs))
* link_attrs_current (string) ([example](#link_attrs_current))
* link_previous_attrs_disabled (string) ([example](#link_previous_attrs_disabled))
* link_next_attrs_disabled (string) ([example](#link_next_attrs_disabled))
* dot_tag (string: span) ([example](#dot_tag))
* dot_attrs (string) ([example](#dot_attrs))

#### Indentation
* use_pretty_html (bool: true) ([example](#use_pretty_html))
* html_tab_sequence (string: \t) ([example](#html_tab_sequence))
* html_initial_indentation (int: 0) ([example](#html_initial_indentation))

#### Security
* esc_attr (bool: true) ([example](#esc_attr))
* esc_html (bool: true) ([example](#esc_html))
* charset (string: UTF-8) ([example](#charset))

You have to sanitize by yourself thoses parameters:
* nav_attrs
* root_attrs
* item_attrs
* item_attrs_current
* item_previous_attrs
* item_previous_attrs_disabled
* item_next_attrs
* item_next_attrs_disabled
* item_dots_attrs
* link_attrs
* link_attrs_current
* link_previous_attrs_disabled
* link_next_attrs_disabled
* dot_attrs
* html_tab_sequence

## Examples
### url
Page append at the end
#### Input
```php
$conf = [
    'url' => 'https://example.com/news/page/'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;news&#x2F;page&#x2F;2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'url' => 'https://example.com/news/page/{{PAGE}}/?date=desc'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;news&#x2F;page&#x2F;2&#x2F;&#x3F;date&#x3D;desc" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

After page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'url' => '{{PAGE}}?date=desc'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2&#x3F;date&#x3D;desc" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Url](#url)

### show_all_links
#### Input
```php
$conf = [
    'show_all_links' => true
];
echo (new Pagination($conf))->generateHtml(1, 30, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li>
            <a href="4" aria-label="Page&#x20;4">4</a>
        </li>
        <li>
            <a href="5" aria-label="Page&#x20;5">5</a>
        </li>
        <li>
            <a href="6" aria-label="Page&#x20;6">6</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### use_previous
#### Input
```php
$conf = [
    'use_previous' => true
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Previous&#x20;page">Previous page</a>
        </li>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### always_use_previous
Previous has `aria-disabled=true` on link tag if there is no previous page
#### Input
```php
$conf = [
    'always_use_previous' => true
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Previous&#x20;page" aria-disabled="true">Previous page</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### use_next
#### Input
```php
$conf = [
    'use_next' => true
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="2" aria-label="Next&#x20;page">Next page</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### always_use_next
Next has `aria-disabled=true` on link tag if there is no next page
#### Input
```php
$conf = [
    'always_use_next' => true
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Next&#x20;page" aria-disabled="true">Next page</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### use_dots
#### Input
```php
$conf = [
    'use_dots' => true
];
echo (new Pagination($conf))->generateHtml(1, 30, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li aria-hidden="true">
            <span>…</span>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### count_pages_pair_limit
#### Input
```php
$conf = [
    'count_pages_pair_limit' => 1
];
echo (new Pagination($conf))->generateHtml(5, 300, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li>
            <a href="4" aria-label="Page&#x20;4">4</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;5" aria-current="page">5</a>
        </li>
        <li>
            <a href="6" aria-label="Page&#x20;6">6</a>
        </li>
        <li>
            <a href="7" aria-label="Page&#x20;7">7</a>
        </li>
        <li>
            <a href="60" aria-label="Page&#x20;60">60</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### count_pages_pair_adjacent
#### Input
```php
$conf = [
    'count_pages_pair_adjacent' => 1
];
echo (new Pagination($conf))->generateHtml(5, 300, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="4" aria-label="Page&#x20;4">4</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;5" aria-current="page">5</a>
        </li>
        <li>
            <a href="6" aria-label="Page&#x20;6">6</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### count_pages_pair_adjacent + count_pages_pair_adjacent
count_pages_pair_limit 0 + count_pages_pair_adjacent 0
#### Input
```php
$conf = [
    'count_pages_pair_limit' => 0,
    'count_pages_pair_adjacent' => 0
];
echo (new Pagination($conf))->generateHtml(5, 300, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;5" aria-current="page">5</a>
        </li>
    </ul>
</nav>
```

count_pages_pair_limit 2 + count_pages_pair_adjacent 2
#### Input
```php
$conf = [
    'count_pages_pair_limit' => 2,
    'count_pages_pair_adjacent' => 2
];
echo (new Pagination($conf))->generateHtml(5, 300, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li>
            <a href="4" aria-label="Page&#x20;4">4</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;5" aria-current="page">5</a>
        </li>
        <li>
            <a href="6" aria-label="Page&#x20;6">6</a>
        </li>
        <li>
            <a href="7" aria-label="Page&#x20;7">7</a>
        </li>
        <li>
            <a href="59" aria-label="Page&#x20;59">59</a>
        </li>
        <li>
            <a href="60" aria-label="Page&#x20;60">60</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Behavior](#behavior)

### text_previous
#### Input
```php
$conf = [
    'use_previous' => true,
    'text_previous' => 'prev'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Previous&#x20;page">prev</a>
        </li>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### text_next
#### Input
```php
$conf = [
    'use_next' => true,
    'text_next' => 'next'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="2" aria-label="Next&#x20;page">next</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### text_dots
#### Input
```php
$conf = [
    'use_dots' => true,
    'text_dots' => 'dots'
];
echo (new Pagination($conf))->generateHtml(1, 30, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li aria-hidden="true">
            <span>dots</span>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### text_page
Page append at the end
#### Input
```php
$conf = [
    'text_page' => 'yolo'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">yolo 1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">yolo 2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'text_page' => 'yo {{PAGE}} lo'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">yo 1 lo</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">yo 2 lo</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### aria_label_link
#### Input
```php
$conf = [
    'aria_label_link' => 'aria label link'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="aria&#x20;label&#x20;link">2</a>
        </li>
    </ul>
</nav>
```

With {{PAGE}} pattern
#### Input
```php
$conf = [
    'aria_label_link' => 'aria label link {{PAGE}}'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="aria&#x20;label&#x20;link&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### aria_label_current_link
#### Input
```php
$conf = [
    'aria_label_current_link' => 'aria label current link'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="aria&#x20;label&#x20;current&#x20;link" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'aria_label_current_link' => 'aria label current link {{PAGE}}'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="aria&#x20;label&#x20;current&#x20;link&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### aria_label_nav
#### Input
```php
$conf = [
    'aria_label_nav' => 'aria label nav'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="aria&#x20;label&#x20;nav">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### aria_label_previous
#### Input
```php
$conf = [
    'use_previous' => true,
    'aria_label_previous' => 'prev'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="prev">Previous page</a>
        </li>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### aria_label_next
#### Input
```php
$conf = [
    'use_next' => true,
    'aria_label_next' => 'next'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="2" aria-label="next">Next page</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### thousands_separator
#### Input
```php
$conf = [
    'thousands_separator' => ';',
    'count_pages_pair_limit' => 1
];
echo (new Pagination($conf))->generateHtml(1, 1000, 1);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li>
            <a href="1000" aria-label="Page&#x20;1000">1;000</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / Labels](#labels)

### root_tag
#### Input
```php
$conf = [
    'root_tag' => 'root'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <root>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </root>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Root](#root)

### root_attrs
#### Input
```php
$conf = [
    'root_attrs' => 'data-root="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul data-root="attrs">
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Root](#root)

### use_nav
#### Input
```php
$conf = [
    'use_nav' => false
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<ul>
    <li>
        <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
    </li>
    <li>
        <a href="2" aria-label="Page&#x20;2">2</a>
    </li>
</ul>
```

[⏫ Configuration Parameters / HTML markup / Root](#root)

### nav_attrs
#### Input
```php
$conf = [
    'nav_attrs' => 'data-item="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination" data-item="attrs">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Root](#root)

### item_tag
#### Input
```php
$conf = [
    'item_tag' => 'item'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <item>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </item>
        <item>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </item>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### item_attrs
#### Input
```php
$conf = [
    'item_attrs' => 'data-item="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li data-item="attrs">
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'item_attrs' => 'data-item="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li data-item="attrs 2">
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### item_attrs_current
#### Input
```php
$conf = [
    'item_attrs_current' => 'data-item-current="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li data-item-current="attrs">
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'item_attrs_current' => 'data-item-current="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li data-item-current="attrs 1">
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### item_previous_attrs
#### Input
```php
$conf = [
    'use_previous' => true,
    'item_previous_attrs' => 'data-item-previous="attrs"'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li data-item-previous="attrs">
            <a href="1" aria-label="Previous&#x20;page">Previous page</a>
        </li>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'use_previous' => true,
    'item_previous_attrs' => 'data-item-previous="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li data-item-previous="attrs 1">
            <a href="1" aria-label="Previous&#x20;page">Previous page</a>
        </li>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### item_previous_attrs_disabled
#### Input
```php
$conf = [
    'always_use_previous' => true,
    'item_previous_attrs_disabled' => 'data-item-previous-disabled="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li data-item-previous-disabled="attrs">
            <a href="&#x23;" aria-label="Previous&#x20;page" aria-disabled="true">Previous page</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'always_use_previous' => true,
    'item_previous_attrs_disabled' => 'data-item-previous-disabled="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li data-item-previous-disabled="attrs 0">
            <a href="&#x23;" aria-label="Previous&#x20;page" aria-disabled="true">Previous page</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### item_next_attrs
#### Input
```php
$conf = [
    'use_next' => true,
    'item_next_attrs' => 'data-item-next="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li data-item-next="attrs">
            <a href="2" aria-label="Next&#x20;page">Next page</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'use_next' => true,
    'item_next_attrs' => 'data-item-next="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li data-item-next="attrs 2">
            <a href="2" aria-label="Next&#x20;page">Next page</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### item_next_attrs_disabled
#### Input
```php
$conf = [
    'always_use_next' => true,
    'item_next_attrs_disabled' => 'data-item-next-disabled="attrs"'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
        <li data-item-next-disabled="attrs">
            <a href="&#x23;" aria-label="Next&#x20;page" aria-disabled="true">Next page</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'always_use_next' => true,
    'item_next_attrs_disabled' => 'data-item-next-disabled="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
        <li data-item-next-disabled="attrs 2">
            <a href="&#x23;" aria-label="Next&#x20;page" aria-disabled="true">Next page</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### item_dots_attrs
#### Input
```php
$conf = [
    'use_dots' => true,
    'item_dots_attrs' => 'data-item-dots="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 30, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li data-item-dots="attrs" aria-hidden="true">
            <span>…</span>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Item](#item)

### link_tag
#### Input
```php
$conf = [
    'link_tag' => 'link'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <link href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</link>
        </li>
        <li>
            <link href="2" aria-label="Page&#x20;2">2</link>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Link](#link)

### link_attrs
#### Input
```php
$conf = [
    'link_attrs' => 'data-link="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a data-link="attrs" href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'link_attrs' => 'data-link="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a data-link="attrs 2" href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Link](#link)

### link_attrs_current
#### Input
```php
$conf = [
    'link_attrs_current' => 'data-link-current="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a data-link-current="attrs" href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'link_attrs_current' => 'data-link-current="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a data-link-current="attrs 1" href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Link](#link)

### link_previous_attrs_disabled
#### Input
```php
$conf = [
    'always_use_previous' => true,
    'link_previous_attrs_disabled' => 'data-item-next-disabled="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a data-item-next-disabled="attrs" href="&#x23;" aria-label="Previous&#x20;page" aria-disabled="true">Previous page</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'always_use_previous' => true,
    'link_previous_attrs_disabled' => 'data-item-next-disabled="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a data-item-next-disabled="attrs 0" href="&#x23;" aria-label="Previous&#x20;page" aria-disabled="true">Previous page</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Link](#link)

### link_next_attrs_disabled
#### Input
```php
$conf = [
    'always_use_next' => true,
    'link_next_attrs_disabled' => 'data-item-next-disabled="attrs"'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
        <li>
            <a data-item-next-disabled="attrs" href="&#x23;" aria-label="Next&#x20;page" aria-disabled="true">Next page</a>
        </li>
    </ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'always_use_next' => true,
    'link_next_attrs_disabled' => 'data-item-next-disabled="attrs {{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="1" aria-label="Page&#x20;1">1</a>
        </li>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>
        </li>
        <li>
            <a data-item-next-disabled="attrs 2" href="&#x23;" aria-label="Next&#x20;page" aria-disabled="true">Next page</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Link](#link)

### dot_tag
#### Input
```php
$conf = [
    'use_dots' => true,
    'dot_tag' => 'p'
];
echo (new Pagination($conf))->generateHtml(1, 30, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li aria-hidden="true">
            <p>…</p>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Link](#link)

### dot_attrs
#### Input
```php
$conf = [
    'use_dots' => true,
    'dot_attrs' => 'data-dot="attrs"'
];
echo (new Pagination($conf))->generateHtml(1, 30, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
        <li>
            <a href="3" aria-label="Page&#x20;3">3</a>
        </li>
        <li aria-hidden="true">
            <span data-dot="attrs">…</span>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Link](#link)

### use_pretty_html
#### Input
```php
$conf = [
    'use_pretty_html' => false
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination"><ul><li><a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a></li><li><a href="2" aria-label="Page&#x20;2">2</a></li></ul></nav>
```

[⏫ Configuration Parameters / HTML markup / Indentation](#indentation)

### html_tab_sequence
#### Input
```php
$conf = [
    'html_tab_sequence' => ''
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
<ul>
<li>
<a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
</li>
<li>
<a href="2" aria-label="Page&#x20;2">2</a>
</li>
</ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Indentation](#indentation)

### html_initial_indentation
#### Input
```php
$conf = [
    'html_initial_indentation' => 1
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
    <nav aria-label="Pagination">
        <ul>
            <li>
                <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
            </li>
            <li>
                <a href="2" aria-label="Page&#x20;2">2</a>
            </li>
        </ul>
    </nav>
```

[⏫ Configuration Parameters / HTML markup / Indentation](#indentation)

### esc_attr
#### Input
```php
$conf = [
    'esc_attr' => false
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="#" aria-label="Page 1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page 2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Security](#security)

### esc_html
#### Input
```php
$conf = [
    'esc_html' => false,
    'text_page' => '<em>{{PAGE}}</em>'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page"><em>1</em></a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2"><em>2</em></a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Security](#security)

### charset
#### Input
```php
$conf = [
    'charset' => 'EUC-JP'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav aria-label="Pagination">
    <ul>
        <li>
            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>
        </li>
        <li>
            <a href="2" aria-label="Page&#x20;2">2</a>
        </li>
    </ul>
</nav>
```

[⏫ Configuration Parameters / HTML markup / Security](#security)

## generateData output
```php
$conf = [
    'always_use_previous' => true,
    'always_use_next' => true,
    'use_dots' => true,
]
var_dump(new Pagination($conf))->generateData(1, 3000, 5);

array (size=3)
  'previous' => 
    object(Rancoud\Pagination\Item)[2]
      public 'ariaLabel' => string 'Previous&#x20;page' (length=18)
      public 'href' => string '&#x23;' (length=6)
      public 'itemAttrs' => string '' (length=0)
      public 'linkAttrs' => string '' (length=0)
      public 'text' => string 'Previous page' (length=13)
      public 'isCurrent' => boolean false
      public 'isDots' => boolean false
      public 'isDisabled' => boolean true
      public 'page' => int 0
  'links' => 
    array (size=4)
      0 => 
        object(Rancoud\Pagination\Item)[4]
          public 'ariaLabel' => string 'Page&#x20;1' (length=11)
          public 'href' => string '&#x23;' (length=6)
          public 'itemAttrs' => string '' (length=0)
          public 'linkAttrs' => string '' (length=0)
          public 'text' => string '1' (length=1)
          public 'isCurrent' => boolean true
          public 'isDots' => boolean false
          public 'isDisabled' => boolean false
          public 'page' => int 1
      1 => 
        object(Rancoud\Pagination\Item)[5]
          public 'ariaLabel' => string 'Page&#x20;2' (length=11)
          public 'href' => string '2' (length=1)
          public 'itemAttrs' => string '' (length=0)
          public 'linkAttrs' => string '' (length=0)
          public 'text' => string '2' (length=1)
          public 'isCurrent' => boolean false
          public 'isDots' => boolean false
          public 'isDisabled' => boolean false
          public 'page' => int 2
      2 => 
        object(Rancoud\Pagination\Item)[6]
          public 'ariaLabel' => string 'Page&#x20;3' (length=11)
          public 'href' => string '3' (length=1)
          public 'itemAttrs' => string '' (length=0)
          public 'linkAttrs' => string '' (length=0)
          public 'text' => string '3' (length=1)
          public 'isCurrent' => boolean false
          public 'isDots' => boolean false
          public 'isDisabled' => boolean false
          public 'page' => int 3
      3 => 
        object(Rancoud\Pagination\Item)[7]
          public 'ariaLabel' => string 'Page&#x20;4' (length=11)
          public 'href' => string '4' (length=1)
          public 'itemAttrs' => string '' (length=0)
          public 'linkAttrs' => string '' (length=0)
          public 'text' => string '…' (length=3)
          public 'isCurrent' => boolean false
          public 'isDots' => boolean true
          public 'isDisabled' => boolean false
          public 'page' => int 4
  'next' => 
    object(Rancoud\Pagination\Item)[8]
      public 'ariaLabel' => string 'Next&#x20;page' (length=14)
      public 'href' => string '2' (length=1)
      public 'itemAttrs' => string '' (length=0)
      public 'linkAttrs' => string '' (length=0)
      public 'text' => string 'Next page' (length=9)
      public 'isCurrent' => boolean false
      public 'isDots' => boolean false
      public 'isDisabled' => boolean false
      public 'page' => int 2
```

## How to Dev
`composer ci` for php-cs-fixer and phpunit and coverage  
`composer lint` for php-cs-fixer  
`composer test` for phpunit and coverage
