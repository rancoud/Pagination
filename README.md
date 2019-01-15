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
// it will output
// <ul ><li ><a  href="1"  title="1">1</a></li><li ><a  href="2"  title="2">2</a></li></ul>
```

## Pagination Constructor
### Settings
#### Optionnals
| Parameter | Type | Default value | Description |
| --- | --- | --- | --- |
| configuration | array | [] |  |

## Pagination Methods
### General Commands  
* generateHtml(currentPage: int, countElements: int, countElementPerPage: int):string  
* generateData(currentPage: int, countElements: int, countElementPerPage: int):array  

## How to Dev
`./run_all_commands.sh` for php-cs-fixer and phpunit and coverage  
`./run_php_unit_coverage.sh` for phpunit and coverage  