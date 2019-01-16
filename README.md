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

## Configuration Parameters

### Url
* base_link (string)
* base_link_after (string)

### Behavior
* show_all_links (bool: false)
* use_previous (bool: false)
* use_next (bool: false)
* use_dots (bool: false)
* count_pages_pair_limit (int: 0)
* count_pages_pair_adjacent (int: 2)

### Labels
* text_previous (string: Previous page)
* text_next (string: Next page)
* text_dots (string: &hellip;)
* aria_label_link (string: Goto page %d)
* aria_label_current_link (string: Current page, page %d)
* aria_label_nav (string: Pagination navigation)

### HTML markup
#### Root
* root_tag (string: ul)
* root_attrs (string)
* use_nav (bool: true)

#### Item
* item_tag (string: li)
* item_attrs (string)
* item_attrs_current (string)
* item_next_attrs (string)
* item_previous_attrs (string)
* item_dot_attrs (string)

#### Link
* link_tag (string: a)
* link_attrs (string)
* link_attrs_current (string)

#### Indentation
* use_pretty_html (bool: true)
* html_tab_sequence (string: \t)
* html_initial_indentation (int: 0)

## How to Dev
`./run_all_commands.sh` for php-cs-fixer and phpunit and coverage  
`./run_php_unit_coverage.sh` for phpunit and coverage  