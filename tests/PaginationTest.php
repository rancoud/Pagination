<?php

/** @noinspection HtmlUnknownAttribute */

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rancoud\Pagination\Item;
use Rancoud\Pagination\Pagination;
use Rancoud\Pagination\PaginationException;

/** @internal */
class PaginationTest extends TestCase
{
    /** @throws PaginationException */
    public function testConstruct(): void
    {
        $p = new Pagination();
        $data = $p->generateData(1, 2, 1);

        $link1 = new Item();
        $link1->isCurrent = true;
        $link1->href = '&#x23;';
        $link1->text = '1';
        $link1->page = 1;
        $link1->ariaLabel = 'Page&#x20;1';

        $link2 = new Item();
        $link2->href = '2';
        $link2->text = '2';
        $link2->page = 2;
        $link2->ariaLabel = 'Page&#x20;2';

        $out = [
            'links' => [
                $link1,
                $link2
            ]
        ];
        static::assertEqualsCanonicalizing($out, $data);

        $html = $p->generateHtml(1, 2, 1);
        $expected = '<nav aria-label="Pagination">' . \PHP_EOL .
                    '	<ul>' . \PHP_EOL .
                    '		<li>' . \PHP_EOL .
                    '			<a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>' . \PHP_EOL .
                    '		</li>' . \PHP_EOL .
                    '		<li>' . \PHP_EOL .
                    '			<a href="2" aria-label="Page&#x20;2">2</a>' . \PHP_EOL .
                    '		</li>' . \PHP_EOL .
                    '	</ul>' . \PHP_EOL .
                    '</nav>';
        static::assertSame($expected, $html);
    }

    /** @throws PaginationException */
    public function testConfiguration(): void
    {
        $p = new Pagination(['text_previous' => 'toto', 'use_next' => true]);
        $p->setConfiguration(['text_next' => 'aze', 'use_previous' => true]);
        $previous = new Item();
        $previous->href = '1';
        $previous->text = 'toto';
        $previous->page = 1;
        $previous->ariaLabel = 'Previous&#x20;page';

        $link1 = new Item();
        $link1->href = '1';
        $link1->text = '1';
        $link1->page = 1;
        $link1->ariaLabel = 'Page&#x20;1';

        $link2 = new Item();
        $link2->isCurrent = true;
        $link2->href = '&#x23;';
        $link2->text = '2';
        $link2->page = 2;
        $link2->ariaLabel = 'Page&#x20;2';

        $link3 = new Item();
        $link3->href = '3';
        $link3->text = '3';
        $link3->page = 3;
        $link3->ariaLabel = 'Page&#x20;3';

        $next = new Item();
        $next->href = '3';
        $next->text = 'aze';
        $next->page = 3;
        $next->ariaLabel = 'Next&#x20;page';

        $out = [
            'previous' => $previous,
            'links'    => [
                $link1,
                $link2,
                $link3,
            ],
            'next' => $next
        ];

        $data = $p->generateData(2, 6, 2);

        static::assertEqualsCanonicalizing($out, $data);
    }

    public static function provideCeilDataCases(): iterable
    {
        $configuration = [
            'use_previous' => true,
            'use_next'     => true
        ];

        $previous = new Item();
        $previous->href = '1';
        $previous->text = 'Previous page';
        $previous->page = 1;
        $previous->ariaLabel = 'Previous&#x20;page';

        $previousPage3 = new Item();
        $previousPage3->href = '3';
        $previousPage3->text = 'Previous page';
        $previousPage3->page = 3;
        $previousPage3->ariaLabel = 'Previous&#x20;page';

        $nextPage2 = new Item();
        $nextPage2->href = '2';
        $nextPage2->text = 'Next page';
        $nextPage2->page = 2;
        $nextPage2->ariaLabel = 'Next&#x20;page';

        $nextPage3 = new Item();
        $nextPage3->href = '3';
        $nextPage3->text = 'Next page';
        $nextPage3->page = 3;
        $nextPage3->ariaLabel = 'Next&#x20;page';

        $linkPage1Current = new Item();
        $linkPage1Current->isCurrent = true;
        $linkPage1Current->href = '&#x23;';
        $linkPage1Current->text = '1';
        $linkPage1Current->page = 1;
        $linkPage1Current->ariaLabel = 'Page&#x20;1';

        $linkPage1 = new Item();
        $linkPage1->href = '1';
        $linkPage1->text = '1';
        $linkPage1->page = 1;
        $linkPage1->ariaLabel = 'Page&#x20;1';

        $linkPage2 = new Item();
        $linkPage2->href = '2';
        $linkPage2->text = '2';
        $linkPage2->page = 2;
        $linkPage2->ariaLabel = 'Page&#x20;2';

        $linkPage3 = new Item();
        $linkPage3->href = '3';
        $linkPage3->text = '3';
        $linkPage3->page = 3;
        $linkPage3->ariaLabel = 'Page&#x20;3';

        $linkPage4 = new Item();
        $linkPage4->href = '4';
        $linkPage4->text = '4';
        $linkPage4->page = 4;
        $linkPage4->ariaLabel = 'Page&#x20;4';

        $linkPage2Current = new Item();
        $linkPage2Current->isCurrent = true;
        $linkPage2Current->href = '&#x23;';
        $linkPage2Current->text = '2';
        $linkPage2Current->page = 2;
        $linkPage2Current->ariaLabel = 'Page&#x20;2';

        yield 'Current page incorrect 99 (1 count 1 per page)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 99,
                'count'    => 1,
                'per_page' => 1
            ],
            'dataOut' => [
                'previous' => $previous,
                'links'    => [],
                'next'     => null
            ]
        ];

        yield 'Current page incorrect -1 (1 count 1 per page)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => -1,
                'count'    => 1,
                'per_page' => 1
            ],
            'dataOut' => [
                'previous' => null,
                'links'    => [$linkPage1Current],
                'next'     => null,
            ]
        ];

        yield 'Per page incorrect -9 (1 current 1 count)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 1,
                'count'    => 1,
                'per_page' => -9
            ],
            'dataOut' => [
                'previous' => null,
                'links'    => [$linkPage1Current],
                'next'     => null,
            ]
        ];

        yield 'Count incorrect -9 (1 current 1 per page)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 1,
                'count'    => -9,
                'per_page' => 1
            ],
            'dataOut' => [
                'previous' => null,
                'links'    => [],
                'next'     => null,
            ]
        ];

        yield 'Current page incorrect 99 (6 count 2 per page)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 99,
                'count'    => 6,
                'per_page' => 2
            ],
            'dataOut' => [
                'previous' => $previousPage3,
                'links'    => [],
                'next'     => null
            ]
        ];

        yield 'Current page incorrect -1 (6 count 2 per page)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => -1,
                'count'    => 6,
                'per_page' => 2
            ],
            'dataOut' => [
                'previous' => null,
                'links'    => [
                    $linkPage1Current,
                    $linkPage2,
                    $linkPage3
                ],
                'next' => $nextPage2,
            ]
        ];

        yield 'Per page incorrect -9 (2 current 6 count)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 2,
                'count'    => 6,
                'per_page' => -9
            ],
            'dataOut' => [
                'previous' => $previous,
                'links'    => [
                    $linkPage1,
                    $linkPage2Current,
                    $linkPage3,
                    $linkPage4,
                ],
                'next' => $nextPage3,
            ]
        ];

        yield 'Count incorrect -9 (2 current 2 per page)' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 2,
                'count'    => -9,
                'per_page' => 2
            ],
            'dataOut' => [
                'previous' => null,
                'links'    => [],
                'next'     => null,
            ]
        ];
    }

    /** @throws PaginationException */
    #[DataProvider('provideCeilDataCases')]
    public function testIncorrectCeilCompute(array $configuration, array $params, array $dataOut): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEqualsCanonicalizing($dataOut, $data);
    }

    public static function provideShowAllLinksDataCases(): iterable
    {
        $configuration = [
            'use_previous'   => true,
            'use_next'       => true,
            'show_all_links' => true
        ];

        $previousPage1 = new Item();
        $previousPage1->href = '1';
        $previousPage1->text = 'Previous page';
        $previousPage1->page = 1;
        $previousPage1->ariaLabel = 'Previous&#x20;page';

        $previousPage49 = new Item();
        $previousPage49->href = '49';
        $previousPage49->text = 'Previous page';
        $previousPage49->page = 49;
        $previousPage49->ariaLabel = 'Previous&#x20;page';

        $nextPage2 = new Item();
        $nextPage2->href = '2';
        $nextPage2->text = 'Next page';
        $nextPage2->page = 2;
        $nextPage2->ariaLabel = 'Next&#x20;page';

        $nextPage3 = new Item();
        $nextPage3->href = '3';
        $nextPage3->text = 'Next page';
        $nextPage3->page = 3;
        $nextPage3->ariaLabel = 'Next&#x20;page';

        $linksCurrentPage1 = [];
        $linksCurrentPage2 = [];
        $linksCurrentPage50 = [];
        for ($i = 1; $i < 51; ++$i) {
            $linkCurrentPage1 = new Item();
            $linkCurrentPage1->isDots = false;
            $linkCurrentPage1->isCurrent = ($i === 1);
            $linkCurrentPage1->href = ($i === 1) ? '&#x23;' : (string) $i;
            $linkCurrentPage1->text = (string) $i;
            $linkCurrentPage1->page = $i;
            $linkCurrentPage1->ariaLabel = 'Page&#x20;' . $i;

            $linkCurrentPage2 = new Item();
            $linkCurrentPage2->isDots = false;
            $linkCurrentPage2->isCurrent = ($i === 2);
            $linkCurrentPage2->href = ($i === 2) ? '&#x23;' : (string) $i;
            $linkCurrentPage2->text = (string) $i;
            $linkCurrentPage2->page = $i;
            $linkCurrentPage2->ariaLabel = 'Page&#x20;' . $i;

            $linkCurrentPage50 = new Item();
            $linkCurrentPage50->isDots = false;
            $linkCurrentPage50->isCurrent = ($i === 50);
            $linkCurrentPage50->href = ($i === 50) ? '&#x23;' : (string) $i;
            $linkCurrentPage50->text = (string) $i;
            $linkCurrentPage50->page = $i;
            $linkCurrentPage50->ariaLabel = 'Page&#x20;' . $i;

            $linksCurrentPage1[] = $linkCurrentPage1;
            $linksCurrentPage2[] = $linkCurrentPage2;
            $linksCurrentPage50[] = $linkCurrentPage50;
        }

        yield '50 links + current page 1' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 1,
                'count'    => 50,
                'per_page' => 1
            ],
            'dataOut' => [
                'previous' => null,
                'links'    => $linksCurrentPage1,
                'next'     => $nextPage2
            ]
        ];

        yield '50 links + current page 2' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 2,
                'count'    => 50,
                'per_page' => 1
            ],
            'dataOut' => [
                'previous' => $previousPage1,
                'links'    => $linksCurrentPage2,
                'next'     => $nextPage3
            ]
        ];

        yield '50 links + current page 50' => [
            'configuration' => $configuration,
            'params'        => [
                'current'  => 50,
                'count'    => 50,
                'per_page' => 1
            ],
            'dataOut' => [
                'previous' => $previousPage49,
                'links'    => $linksCurrentPage50,
                'next'     => null
            ]
        ];
    }

    /** @throws PaginationException */
    #[DataProvider('provideShowAllLinksDataCases')]
    public function testShowAllLinks(array $configuration, array $params, array $dataOut): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEqualsCanonicalizing($dataOut, $data);
    }

    public static function provideAdjacentAndLimitConfigurationDataCases(): iterable
    {
        $linkCurrentPage20 = new Item();
        $linkCurrentPage20->isDots = false;
        $linkCurrentPage20->isCurrent = true;
        $linkCurrentPage20->href = '&#x23;';
        $linkCurrentPage20->text = '20';
        $linkCurrentPage20->page = 20;
        $linkCurrentPage20->ariaLabel = 'Page&#x20;20';

        $currentPage = [];
        $currentPage[] = $linkCurrentPage20;

        $limitPagesLeft = [];
        $limitPagesRight = [];
        for ($i = 1; $i < 6; ++$i) {
            $limitPageLeft = new Item();
            $limitPageLeft->isDots = false;
            $limitPageLeft->isCurrent = false;
            $limitPageLeft->href = (string) $i;
            $limitPageLeft->text = (string) $i;
            $limitPageLeft->page = $i;
            $limitPageLeft->ariaLabel = 'Page&#x20;' . $i;

            $limitPagesLeft[] = $limitPageLeft;
        }
        for ($i = 46; $i < 51; ++$i) {
            $limitPageRight = new Item();
            $limitPageRight->isDots = false;
            $limitPageRight->isCurrent = false;
            $limitPageRight->href = (string) $i;
            $limitPageRight->text = (string) $i;
            $limitPageRight->page = $i;
            $limitPageRight->ariaLabel = 'Page&#x20;' . $i;

            $limitPagesRight[] = $limitPageRight;
        }

        $adjacentPagesLeft = [];
        $adjacentPagesRight = [];
        for ($i = 15; $i < 20; ++$i) {
            $adjacentPageLeft = new Item();
            $adjacentPageLeft->isDots = false;
            $adjacentPageLeft->isCurrent = false;
            $adjacentPageLeft->href = (string) $i;
            $adjacentPageLeft->text = (string) $i;
            $adjacentPageLeft->page = $i;
            $adjacentPageLeft->ariaLabel = 'Page&#x20;' . $i;

            $adjacentPagesLeft[] = $adjacentPageLeft;
        }
        for ($i = 21; $i < 26; ++$i) {
            $adjacentPageRight = new Item();
            $adjacentPageRight->isDots = false;
            $adjacentPageRight->isCurrent = false;
            $adjacentPageRight->href = (string) $i;
            $adjacentPageRight->text = (string) $i;
            $adjacentPageRight->page = $i;
            $adjacentPageRight->ariaLabel = 'Page&#x20;' . $i;

            $adjacentPagesRight[] = $adjacentPageRight;
        }

        $params = [
            'current'  => 20,
            'count'    => 50,
            'per_page' => 1
        ];

        yield 'limit 0 + adjacent 0' => [
            'configuration' => [
                'count_pages_pair_limit'    => 0,
                'count_pages_pair_adjacent' => 0,
            ],
            'params'   => $params,
            'dataOut'  => [
                'links' => $currentPage,
            ]
        ];

        yield 'limit 5 + adjacent 0' => [
            'configuration' => [
                'count_pages_pair_limit'    => 5,
                'count_pages_pair_adjacent' => 0,
            ],
            'params'   => $params,
            'dataOut'  => [
                'links' => \array_merge($limitPagesLeft, $currentPage, $limitPagesRight),
            ]
        ];

        yield 'limit 0 + adjacent 5' => [
            'configuration' => [
                'count_pages_pair_limit'    => 0,
                'count_pages_pair_adjacent' => 5,
            ],
            'params'   => $params,
            'dataOut'  => [
                'links' => \array_merge($adjacentPagesLeft, $currentPage, $adjacentPagesRight),
            ]
        ];

        yield 'limit 5 + adjacent 5' => [
            'configuration' => [
                'count_pages_pair_limit'    => 5,
                'count_pages_pair_adjacent' => 5,
            ],
            'params'   => $params,
            'dataOut'  => [
                'links' => \array_merge($limitPagesLeft, $adjacentPagesLeft, $currentPage, $adjacentPagesRight, $limitPagesRight),
            ]
        ];
    }

    /** @throws PaginationException */
    #[DataProvider('provideAdjacentAndLimitConfigurationDataCases')]
    public function testAdjacentAndLimitConfiguration(array $configuration, array $params, array $dataOut): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEqualsCanonicalizing($dataOut, $data);
    }

    public static function provideRenderHtmlDataCases(): iterable
    {
        yield 'pretty html off' => [
            'configuration' => [
                'use_pretty_html' => false
            ],
            'params' => [
                'current'  => 1,
                'count'    => 2,
                'per_page' => 1
            ],
            'expectedHtml' => '<nav aria-label="Pagination">' .
                '<ul>' .
                '<li>' .
                '<a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>' .
                '</li>' .
                '<li>' .
                '<a href="2" aria-label="Page&#x20;2">2</a>' .
                '</li>' .
                '</ul>' .
                '</nav>'
        ];

        yield 'initial indentation 4' => [
            'configuration' => [
                'html_initial_indentation' => 4
            ],
            'params' => [
                'current'  => 1,
                'count'    => 2,
                'per_page' => 1
            ],
            'expectedHtml' => '				<nav aria-label="Pagination">' . \PHP_EOL .
                '					<ul>' . \PHP_EOL .
                '						<li>' . \PHP_EOL .
                '							<a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>' . \PHP_EOL .
                '						</li>' . \PHP_EOL .
                '						<li>' . \PHP_EOL .
                '							<a href="2" aria-label="Page&#x20;2">2</a>' . \PHP_EOL .
                '						</li>' . \PHP_EOL .
                '					</ul>' . \PHP_EOL .
                '				</nav>'
        ];

        yield 'initial indentation 4 + four spaces' => [
            'configuration' => [
                'html_initial_indentation' => 4,
                'html_tab_sequence'        => '    '
            ],
            'params' => [
                'current'  => 1,
                'count'    => 2,
                'per_page' => 1
            ],
            'expectedHtml' => '                <nav aria-label="Pagination">' . \PHP_EOL .
                '                    <ul>' . \PHP_EOL .
                '                        <li>' . \PHP_EOL .
                '                            <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>' . \PHP_EOL .
                '                        </li>' . \PHP_EOL .
                '                        <li>' . \PHP_EOL .
                '                            <a href="2" aria-label="Page&#x20;2">2</a>' . \PHP_EOL .
                '                        </li>' . \PHP_EOL .
                '                    </ul>' . \PHP_EOL .
                '                </nav>'
        ];

        yield 'initial indentation 4 + four spaces + nav off' => [
            'configuration' => [
                'html_initial_indentation' => 4,
                'html_tab_sequence'        => '    ',
                'use_nav'                  => false
            ],
            'params' => [
                'current'  => 1,
                'count'    => 2,
                'per_page' => 1
            ],
            'expectedHtml' => '                <ul>' . \PHP_EOL .
                '                    <li>' . \PHP_EOL .
                '                        <a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>' . \PHP_EOL .
                '                    </li>' . \PHP_EOL .
                '                    <li>' . \PHP_EOL .
                '                        <a href="2" aria-label="Page&#x20;2">2</a>' . \PHP_EOL .
                '                    </li>' . \PHP_EOL .
                '                </ul>'
        ];

        yield 'initial indentation 4 + four spaces + nav off + previous + after' => [
            'configuration' => [
                'html_initial_indentation' => 4,
                'html_tab_sequence'        => '    ',
                'use_nav'                  => false,
                'use_next'                 => true,
                'use_previous'             => true,
            ],
            'params' => [
                'current'  => 2,
                'count'    => 3,
                'per_page' => 1
            ],
            'expectedHtml' => '                <ul>' . \PHP_EOL .
                '                    <li>' . \PHP_EOL .
                '                        <a href="1" aria-label="Previous&#x20;page">Previous page</a>' . \PHP_EOL .
                '                    </li>' . \PHP_EOL .
                '                    <li>' . \PHP_EOL .
                '                        <a href="1" aria-label="Page&#x20;1">1</a>' . \PHP_EOL .
                '                    </li>' . \PHP_EOL .
                '                    <li>' . \PHP_EOL .
                '                        <a href="&#x23;" aria-label="Page&#x20;2" aria-current="page">2</a>' . \PHP_EOL .
                '                    </li>' . \PHP_EOL .
                '                    <li>' . \PHP_EOL .
                '                        <a href="3" aria-label="Page&#x20;3">3</a>' . \PHP_EOL .
                '                    </li>' . \PHP_EOL .
                '                    <li>' . \PHP_EOL .
                '                        <a href="3" aria-label="Next&#x20;page">Next page</a>' . \PHP_EOL .
                '                    </li>' . \PHP_EOL .
                '                </ul>'
        ];

        yield 'attrs + labels + url' => [
            'configuration' => [
                'url'                         => 'https://example.com/',
                'text_previous'               => '前',
                'text_next'                   => '次',
                'root_tag'                    => 'root',
                'root_attrs'                  => 'x="f(x)"',
                'item_tag'                    => 'item',
                'item_attrs'                  => 'class="okay"',
                'item_attrs_current'          => 'data-id="yes"',
                'item_next_attrs'             => 'next',
                'item_previous_attrs'         => 'previous',
                'link_tag'                    => 'zela',
                'link_attrs'                  => 'data-data="o"',
                'link_attrs_current'          => 'data-id="id"',
                'aria_label_link'             => '頁 -> {{PAGE}}',
                'aria_label_current_link'     => '頁 - {{PAGE}}',
                'aria_label_nav'              => 'plop',
                'aria_label_previous'         => 'aria-前',
                'aria_label_next'             => 'aria-次',
                'use_next'                    => true,
                'use_previous'                => true,
                'nav_attrs'                   => 'class="pagination"'
            ],
            'params' => [
                'current'  => 2,
                'count'    => 3,
                'per_page' => 1
            ],
            'expectedHtml' => '<nav aria-label="plop" class="pagination">' . \PHP_EOL .
                '	<root x="f(x)">' . \PHP_EOL .
                '		<item previous>' . \PHP_EOL .
                '			<zela data-data="o" href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;1" aria-label="aria-&#x524D;">前</zela>' . \PHP_EOL .
                '		</item>' . \PHP_EOL .
                '		<item class="okay">' . \PHP_EOL .
                '			<zela data-data="o" href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;1" aria-label="&#x9801;&#x20;-&gt;&#x20;1">1</zela>' . \PHP_EOL .
                '		</item>' . \PHP_EOL .
                '		<item data-id="yes">' . \PHP_EOL .
                '			<zela data-id="id" href="&#x23;" aria-label="&#x9801;&#x20;-&#x20;2" aria-current="page">2</zela>' . \PHP_EOL .
                '		</item>' . \PHP_EOL .
                '		<item class="okay">' . \PHP_EOL .
                '			<zela data-data="o" href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;3" aria-label="&#x9801;&#x20;-&gt;&#x20;3">3</zela>' . \PHP_EOL .
                '		</item>' . \PHP_EOL .
                '		<item next>' . \PHP_EOL .
                '			<zela data-data="o" href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;3" aria-label="aria-&#x6B21;">次</zela>' . \PHP_EOL .
                '		</item>' . \PHP_EOL .
                '	</root>' . \PHP_EOL .
                '</nav>'
        ];

        yield 'use dots' => [
            'configuration' => [
                'use_dots'                  => true,
                'text_dots'                 => 'dots',
                'dot_tag'                   => 'dot',
                'dot_attrs'                 => 'data-dot="attrs"',
                'item_dots_attrs'           => 'dotdot',
                'count_pages_pair_adjacent' => 0
            ],
            'params' => [
                'current'  => 25,
                'count'    => 50,
                'per_page' => 1
            ],
            'expectedHtml' => '<nav aria-label="Pagination">' . \PHP_EOL .
                '	<ul>' . \PHP_EOL .
                '		<li dotdot aria-hidden="true">' . \PHP_EOL .
                '			<dot data-dot="attrs">dots</dot>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="&#x23;" aria-label="Page&#x20;25" aria-current="page">25</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li dotdot aria-hidden="true">' . \PHP_EOL .
                '			<dot data-dot="attrs">dots</dot>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '	</ul>' . \PHP_EOL .
                '</nav>'
        ];

        yield 'previous + next + attrs not empty + aria disabled' => [
            'configuration' => [
                'always_use_previous'          => true,
                'always_use_next'              => true,
                'item_previous_attrs_disabled' => 'item_previous_attrs_disabled',
                'item_next_attrs_disabled'     => 'item_next_attrs_disabled',
            ],
            'params' => [
                'current'  => 1,
                'count'    => 1,
                'per_page' => 1
            ],
            'expectedHtml' => '<nav aria-label="Pagination">' . \PHP_EOL .
                '	<ul>' . \PHP_EOL .
                '		<li item_previous_attrs_disabled>' . \PHP_EOL .
                '			<a href="&#x23;" aria-label="Previous&#x20;page" aria-disabled="true">Previous page</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="&#x23;" aria-label="Page&#x20;1" aria-current="page">1</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li item_next_attrs_disabled>' . \PHP_EOL .
                '			<a href="&#x23;" aria-label="Next&#x20;page" aria-disabled="true">Next page</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '	</ul>' . \PHP_EOL .
                '</nav>'
        ];

        yield 'empty text + empty tag' => [
            'configuration' => [
                'text_previous'               => '',
                'text_next'                   => '',
                'text_dots'                   => '',
                'text_page'                   => '',
                'aria_label_link'             => '',
                'aria_label_current_link'     => '',
                'aria_label_nav'              => '',
                'aria_label_previous'         => '',
                'aria_label_next'             => '',
                'root_tag'                    => '',
                'item_tag'                    => '',
                'link_tag'                    => '',
                'use_next'                    => true,
                'use_previous'                => true,
                'use_dots'                    => true
            ],
            'params' => [
                'current'  => 2,
                'count'    => 3,
                'per_page' => 1
            ],
            'expectedHtml' => '<nav>' . \PHP_EOL .
                '	<ul>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="1"></a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="1">1</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="&#x23;" aria-current="page">2</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="3">3</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="3"></a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '	</ul>' . \PHP_EOL .
                '</nav>'
        ];

        yield 'security' => [
            'configuration' => [
                'url'                         => 'https://example.com/{{PAGE}}/" <script>alert(1);</script> ',
                'text_previous'               => '<script>alert(1);</script>',
                'text_next'                   => '<script>alert(1);</script>',
                'text_dots'                   => '<script>alert(1);</script>',
                'text_page'                   => '<script>alert(1);</script>',
                'aria_label_link'             => '"<script>alert(1);</script>"',
                'aria_label_current_link'     => '"<script>alert(1);</script>"',
                'aria_label_nav'              => '"<script>alert(1);</script>"',
                'aria_label_previous'         => '"<script>alert(1);</script>"',
                'aria_label_next'             => '"<script>alert(1);</script>"',
                'root_tag'                    => '><script>alert(1);</script>',
                'item_tag'                    => '><script>alert(1);</script>',
                'link_tag'                    => '><script>alert(1);</script>',
                'thousands_separator'         => '<script>alert(1);</script>',
                'use_next'                    => true,
                'use_previous'                => true,
                'use_dots'                    => true,
                'count_pages_pair_limit'      => 1
            ],
            'params' => [
                'current'  => 2,
                'count'    => 3000,
                'per_page' => 1
            ],
            'expectedHtml' => '<nav aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">' . \PHP_EOL .
                '	<ul>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;1&#x2F;&quot;&#x20;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&#x20;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt;</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;1&#x2F;&quot;&#x20;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&#x20;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 1</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="&#x23;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;" aria-current="page">&lt;script&gt;alert(1);&lt;&#47;script&gt; 2</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;3&#x2F;&quot;&#x20;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&#x20;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 3</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;4&#x2F;&quot;&#x20;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&#x20;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 4</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li aria-hidden="true">' . \PHP_EOL .
                '			<span>&lt;script&gt;alert(1);&lt;&#47;script&gt;</span>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;3000&#x2F;&quot;&#x20;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&#x20;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 3&lt;script&gt;alert(1);&lt;&#47;script&gt;000</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '		<li>' . \PHP_EOL .
                '			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;3&#x2F;&quot;&#x20;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&#x20;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt;</a>' . \PHP_EOL .
                '		</li>' . \PHP_EOL .
                '	</ul>' . \PHP_EOL .
                '</nav>'
        ];
    }

    /** @throws PaginationException */
    #[DataProvider('provideRenderHtmlDataCases')]
    public function testRenderHtml(array $configuration, array $params, string $expectedHtml): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $html = $p->generateHtml($params['current'], $params['count'], $params['per_page']);
        static::assertSame($expectedHtml, $html);
    }

    public static function provideCountPagesDataCases(): iterable
    {
        yield '1 item + 1 per page'  => [1, 1, 1];

        yield '10 item + 1 per page' => [10, 1, 10];

        yield '10 item + 5 per page' => [10, 5, 2];

        yield '11 item + 5 per page' => [11, 5, 3];
    }

    #[DataProvider('provideCountPagesDataCases')]
    public function testCountPages(int $countElements, int $countElementPerPage, int $expected): void
    {
        static::assertSame($expected, Pagination::countPages($countElements, $countElementPerPage));
    }

    public static function provideLocateItemInPageDataCases(): iterable
    {
        yield '1 per page + item index 1'   => [1, 1, 1];

        yield '10 per page + item index 1'  => [10, 1, 1];

        yield '10 per page + item index 5'  => [10, 5, 1];

        yield '11 per page + item index 5'  => [11, 5, 1];

        yield '10 per page + item index 10' => [10, 10, 1];

        yield '10 per page + item index 11' => [10, 11, 2];

        yield '5 per page + item index 25'  => [5, 25, 5];

        yield '5 per page + item index 26'  => [5, 26, 6];
    }

    #[DataProvider('provideLocateItemInPageDataCases')]
    public function testLocateItemInPage(int $countElementPerPage, int $itemIndex, int $expected): void
    {
        static::assertSame($expected, Pagination::locateItemInPage($countElementPerPage, $itemIndex));
    }

    public static function provideExceptionDataCases(): iterable
    {
        yield 'aria_label_nav' => [
            'conf'    => ['aria_label_nav' => \chr(99999999)],
            'message' => 'could not escAttr "nav" aria label: String to convert is not valid for the specified charset'
        ];

        yield 'text_previous' => [
            'conf'    => ['text_previous' => \chr(99999999), 'always_use_previous' => true],
            'message' => 'could not escHTML "previous" text: String to convert is not valid for the specified charset'
        ];

        yield 'aria_label_previous' => [
            'conf'    => ['aria_label_previous' => \chr(99999999), 'always_use_previous' => true],
            'message' => 'could not escAttr "previous" aria label or "previous" href: String to convert is not valid for the specified charset'
        ];

        yield 'text_next' => [
            'conf'    => ['text_next' => \chr(99999999), 'always_use_next' => true],
            'message' => 'could not escHTML "next" text: String to convert is not valid for the specified charset'
        ];

        yield 'aria_label_next' => [
            'conf'    => ['aria_label_next' => \chr(99999999), 'always_use_next' => true],
            'message' => 'could not escAttr "next" aria label or "next" href: String to convert is not valid for the specified charset'
        ];

        yield 'aria_label_link' => [
            'conf'    => ['aria_label_link' => \chr(99999999)],
            'message' => 'could not escAttr "item" aria label or "item" href: String to convert is not valid for the specified charset'
        ];

        yield 'text_page' => [
            'conf'    => ['text_page' => \chr(99999999)],
            'message' => 'could not escHTML "item" text: String to convert is not valid for the specified charset'
        ];
    }

    /** @throws PaginationException */
    #[DataProvider('provideExceptionDataCases')]
    public function testException(array $conf, string $message): void
    {
        $this->expectException(PaginationException::class);
        $this->expectExceptionMessage($message);

        new Pagination($conf)->generateHtml(5, 10, 1);
    }
}
