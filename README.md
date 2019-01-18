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
* base_url_before (string) ([example](#base_url_before))
* base_link_after (string) ([example](#base_url_after))

### Behavior
* show_all_links (bool: false) ([example](#show_all_links))
* use_previous (bool: false) ([example](#use_previous))
* use_next (bool: false) ([example](#use_next))
* use_dots (bool: false) ([example](#use_dots))
* count_pages_pair_limit (int: 0) ([example](#count_pages_pair_limit))
* count_pages_pair_adjacent (int: 2) ([example](#count_pages_pair_adjacent))

### Labels
* text_previous (string: Previous page) ([example](#text_previous))
* text_next (string: Next page) ([example](#text_next))
* text_dots (string: …) ([example](#text_dots))
* text_page (string: %d) ([example](#text_page))
* aria_label_link (string: Goto page %d) ([example](#aria_label_link))
* aria_label_current_link (string: Current page, page %d) ([example](#aria_label_current_link))
* aria_label_nav (string: Pagination navigation) ([example](#aria_label_nav))
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
* item_next_attrs (string) ([example](#item_next_attrs))
* item_previous_attrs (string) ([example](#item_previous_attrs))
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
### base_url_before
#### Input
```php
$conf = [
    'base_url_before' => 'https://example.com/news/page/'
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

### base_url_after
#### Input
```php
$conf = [
    'base_url_after' => '/?date=desc'
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
			<a href="2/?date=desc" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>
```

### base_url_before + base_url_after
#### Input
```php
$conf = [
    'base_url_before' => 'https://example.com/news/page/',
    'base_url_after' => '/?date=desc'
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
			<span>&amp;hellip;</span>
		</li>
	</ul>
</nav>
```

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

### count_pages_pair_limit 0 + count_pages_pair_adjacent 0
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

### count_pages_pair_limit 2 + count_pages_pair_adjacent 2
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
			<a href="1" aria-label="prev">prev</a>
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

### text_previous with page inside
#### Input
```php
$conf = [
    'use_previous' => true,
    'text_previous' => 'prev %d'
];
echo (new Pagination($conf))->generateHtml(2, 10, 5);
```
#### Output
```html
<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="1" aria-label="prev&#x20;&#x25;d">prev %d</a>
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

### text_next
#### Input
```php
```
#### Output
```html
```

### text_dots
#### Input
```php
```
#### Output
```html
```

### text_page
#### Input
```php
```
#### Output
```html
```

### aria_label_link
#### Input
```php
```
#### Output
```html
```

### aria_label_current_link
#### Input
```php
```
#### Output
```html
```

### aria_label_nav
#### Input
```php
```
#### Output
```html
```

### thousands_separator
#### Input
```php
```
#### Output
```html
```

### root_tag
#### Input
```php
```
#### Output
```html
```

### root_attrs
#### Input
```php
```
#### Output
```html
```

### use_nav
#### Input
```php
```
#### Output
```html
```

### item_tag
#### Input
```php
```
#### Output
```html
```

### item_attrs
#### Input
```php
```
#### Output
```html
```

### item_attrs_current
#### Input
```php
```
#### Output
```html
```

### item_next_attrs
#### Input
```php
```
#### Output
```html
```

### item_previous_attrs
#### Input
```php
```
#### Output
```html
```

### item_dot_attrs
#### Input
```php
```
#### Output
```html
```

### link_tag
#### Input
```php
```
#### Output
```html
```

### link_attrs
#### Input
```php
```
#### Output
```html
```

### link_attrs_current
#### Input
```php
```
#### Output
```html
```

### use_pretty_html
#### Input
```php
```
#### Output
```html
```

### html_tab_sequence
#### Input
```php
```
#### Output
```html
```

### html_initial_indentation
#### Input
```php
```
#### Output
```html
```

### esc_attr
#### Input
```php
```
#### Output
```html
```

### esc_html
#### Input
```php
```
#### Output
```html
```

### charset
#### Input
```php
```
#### Output
```html
```

## How to Dev
`./run_all_commands.sh` for php-cs-fixer and phpunit and coverage  
`./run_php_unit_coverage.sh` for phpunit and coverage  