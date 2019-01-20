# Pagination Package

[![Build Status](https://travis-ci.org/rancoud/Pagination.svg?branch=master)](https://travis-ci.org/rancoud/Pagination) [![Coverage Status](https://coveralls.io/repos/github/rancoud/Pagination/badge.svg?branch=master)](https://coveralls.io/github/rancoud/Pagination?branch=master)

Pagination.  

## Installation
```php
composer require rancoud/pagination
```

## How to use it?
```php
$currentPage = 1;
$countElements = 10;
$countElementPerPage = 5;

$p = new Pagination();
$html = $p->generateHtml($currentPage, $countElements, $countElementPerPage);
echo $html;
```
It will output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="1" aria-label="Current page, page 1" aria-current="true" title="1">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto page 2" title="2">2</a>
		</li>
	</ul>
</nav>
```

## Pagination Constructor
### Settings
#### Optionnals
| Parameter | Type | Default value | Description |
| --- | --- | --- | --- |
| configuration | array | [] | Parameters for changing pagination behavior |

## Pagination Methods
### General Commands  
* generateHtml(currentPage: int, countElements: int, countElementPerPage: int):string
* generateData(currentPage: int, countElements: int, countElementPerPage: int):array
### Static methods
* countPages(countElements: int, countElementPerPage: int): int
* locateItemInPage(countElementPerPage: int, indexItem: int): int

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
* aria_label_nav (string: Pagination navigation) ([example](#aria_label_nav))
* aria_label_previous (string: Previous page) ([example](#aria_label_previous))
* aria_label_next (string: Next page) ([example](#aria_label_next))
* thousands_separator (string) ([example](#thousands_separator))

### HTML markup
#### Root
* root_tag (string: ul) ([example](#root_tag))
* root_attrs (string) ([example](#root_attrs))
* use_nav (bool: true) ([example](#use_nav))

#### Item
* item_tag (string: li) ([example](#item_tag))
* item_attrs (string) ([example](#item_attrs))
* item_attrs_current (string) ([example](#item_attrs_current))
* item_previous_attrs (string) ([example](#item_previous_attrs))
* item_next_attrs (string) ([example](#item_next_attrs))
* item_dot_attrs (string) ([example](#item_dot_attrs))

#### Link
* link_tag (string: a) ([example](#link_tag))
* link_attrs (string) ([example](#link_attrs))
* link_attrs_current (string) ([example](#link_attrs_current))

#### Indentation
* use_pretty_html (bool: true) ([example](#use_pretty_html))
* html_tab_sequence (string: \t) ([example](#html_tab_sequence))
* html_initial_indentation (int: 0) ([example](#html_initial_indentation))

#### Security
* esc_attr (bool: true) ([example](#esc_attr))
* esc_html (bool: true) ([example](#esc_html))
* charset (string: UTF-8) ([example](#charset))

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="https://example.com/news/page/2" aria-label="Goto&#x20;page&#x20;2">2</a>
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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="https://example.com/news/page/2/?date=desc" aria-label="Goto&#x20;page&#x20;2">2</a>
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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2?date=desc" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li>
			<a href="4" aria-label="Goto&#x20;page&#x20;4">4</a>
		</li>
		<li>
			<a href="5" aria-label="Goto&#x20;page&#x20;5">5</a>
		</li>
		<li>
			<a href="6" aria-label="Goto&#x20;page&#x20;6">6</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="1" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### always_use_previous
Previous has `data-disabled` on item tag if there is no previous page
#### Input
```php
$conf = [
    'always_use_previous' => true
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li data-disabled>
			<a href="#" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="2" aria-label="Next&#x20;page">Next page</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### always_use_next
Next have `data-disabled` on item tag if there is no next page
#### Input
```php
$conf = [
    'always_use_next' => true
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
		<li data-disabled>
			<a href="#" aria-label="Next&#x20;page">Next page</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li>
			<span>…</span>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li>
			<a href="4" aria-label="Goto&#x20;page&#x20;4">4</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
		</li>
		<li>
			<a href="6" aria-label="Goto&#x20;page&#x20;6">6</a>
		</li>
		<li>
			<a href="7" aria-label="Goto&#x20;page&#x20;7">7</a>
		</li>
		<li>
			<a href="60" aria-label="Goto&#x20;page&#x20;60">60</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="4" aria-label="Goto&#x20;page&#x20;4">4</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
		</li>
		<li>
			<a href="6" aria-label="Goto&#x20;page&#x20;6">6</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li>
			<a href="4" aria-label="Goto&#x20;page&#x20;4">4</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
		</li>
		<li>
			<a href="6" aria-label="Goto&#x20;page&#x20;6">6</a>
		</li>
		<li>
			<a href="7" aria-label="Goto&#x20;page&#x20;7">7</a>
		</li>
		<li>
			<a href="59" aria-label="Goto&#x20;page&#x20;59">59</a>
		</li>
		<li>
			<a href="60" aria-label="Goto&#x20;page&#x20;60">60</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="1" aria-label="Previous&#x20;page">prev</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="2" aria-label="Next&#x20;page">next</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li>
			<span>dots</span>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">yolo 1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">yolo 2</a>
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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">yo 1 lo</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">yo 2 lo</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="aria&#x20;label&#x20;link&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="aria&#x20;label&#x20;current&#x20;link" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="aria&#x20;label&#x20;current&#x20;link&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="aria label nav">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="aria label nav">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="aria label nav">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li>
			<a href="1000" aria-label="Goto&#x20;page&#x20;1000">1;000</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<root>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</root>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### root_attrs
#### Input
```php
$conf = [
    'root_attrs' => 'root attrs'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul root attrs>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
		<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
	</li>
	<li>
		<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
	</li>
</ul>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<item>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</item>
		<item>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</item>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### item_attrs
#### Input
```php
$conf = [
    'item_attrs' => 'item attrs'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li item attrs>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'item_attrs' => 'item attrs data-page="{{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li item attrs data-page="2">
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### item_attrs_current
#### Input
```php
$conf = [
    'item_attrs_current' => 'item attrs current'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li item attrs current>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'item_attrs_current' => 'item attrs current data-page="{{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li item attrs current data-page="1">
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### item_previous_attrs
#### Input
```php
$conf = [
    'use_previous' => true,
    'item_previous_attrs' => 'item previous attrs'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li item previous attrs>
			<a href="1" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'use_previous' => true,
    'item_previous_attrs' => 'item previous attrs data-page="{{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li item previous attrs data-page="1">
			<a href="1" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### item_next_attrs
#### Input
```php
$conf = [
    'use_next' => true,
    'item_next_attrs' => 'item next attrs'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li item next attrs>
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
    'item_next_attrs' => 'item next attrs data-page="{{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li item next attrs data-page="2">
			<a href="2" aria-label="Next&#x20;page">Next page</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### item_dot_attrs
#### Input
```php
$conf = [
    'use_dots' => true,
    'item_dot_attrs' => 'item dot attrs'
];
echo (new Pagination($conf))->generateHtml(1, 30, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li item dot attrs>
			<span>…</span>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

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
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<link href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</link>
		</li>
		<li>
			<link href="2" aria-label="Goto&#x20;page&#x20;2">2</link>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### link_attrs
#### Input
```php
$conf = [
    'link_attrs' => 'link attrs'
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a link attrs href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'link_attrs' => 'link attrs data-page="{{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a link attrs data-page="2" href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### link_attrs_current
#### Input
```php
$conf = [
    'use_pretty_html' => false
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a link attrs current href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

Page replace with {{PAGE}} pattern
#### Input
```php
$conf = [
    'link_attrs_current' => 'link attrs current data-page="{{PAGE}}"'
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a link attrs current data-page="1" href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### use_pretty_html
#### Input
```php
$conf = [
    'use_pretty_html' => false
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation"><ul><li><a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a></li><li><a href="2" aria-label="Goto&#x20;page&#x20;2">2</a></li></ul></nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### html_tab_sequence
#### Input
```php
$conf = [
    'html_tab_sequence' => ''
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
<ul>
<li>
<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
</li>
<li>
<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
</li>
</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### html_initial_indentation
#### Input
```php
$conf = [
    'html_initial_indentation' => 1
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
	<nav role="navigation" aria-label="Pagination navigation">
		<ul>
			<li>
				<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
			</li>
			<li>
				<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
			</li>
		</ul>
	</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### esc_attr
#### Input
```php
$conf = [
    'esc_attr' => false
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current page, page 1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto page 2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### esc_html
#### Input
```php
$conf = [
    'esc_html' => false,
    'text_page' => '<em>{{PAGE}}</em>'
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true"><em>1</em></a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2"><em>2</em></a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

### charset
#### Input
```php
$conf = [
    'charset' => 'EUC-JP'
];
echo (new Pagination($conf))->generateHtml(1, 2, 1);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

[⏫ Configuration Parameters](#configuration-parameters)

## How to Dev
`./run_all_commands.sh` for php-cs-fixer and phpunit and coverage  
`./run_php_unit_coverage.sh` for phpunit and coverage  