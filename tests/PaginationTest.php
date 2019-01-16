<?php

declare(strict_types=1);

namespace Rancoud\Pagination\Test;

use PHPUnit\Framework\TestCase;
use Rancoud\Pagination\Pagination;

/**
 * Class PaginationTest.
 */
class PaginationTest extends TestCase
{
    public function testConstruct()
    {
        $p = new Pagination();
        $data = $p->generateData(1, 2, 1);
        $out = ['links' => [
            [
                'dots' => false,
                'current' => true,
                'href' => '1',
                'text' => '1'
            ],
            [
                'dots' => false,
                'current' => false,
                'href' => '2',
                'text' => '2'
            ]
        ]];
        static::assertEquals($out, $data);

        $html = $p->generateHtml(1, 2, 1);
        $expected = '<nav role="navigation" aria-label="Pagination navigation">'.PHP_EOL.
                    '	<ul>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="1" aria-label="Current page, page 1" aria-current="true" title="1">1</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="2" aria-label="Goto page 2" title="2">2</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '	</ul>'.PHP_EOL.
                    '</nav>';
        static::assertSame($expected, $html);
    }

    public function testConfiguration()
    {
        $p = new Pagination(['text_previous' => 'toto', 'use_next' => true]);
        $p->setConfiguration(['text_next' => 'aze', 'use_previous' => true]);
        $out = [
            'previous' => [
                'href' => '1',
                'text' => 'toto',
            ],
            'links' => [
                [
                    'dots' => false,
                    'current' => false,
                    'href' => '1',
                    'text' => '1'
                ],
                [
                    'dots' => false,
                    'current' => true,
                    'href' => '2',
                    'text' => '2'
                ],
                [
                    'dots' => false,
                    'current' => false,
                    'href' => '3',
                    'text' => '3'
                ]
            ],
            'next' => [
                'href' => '3',
                'text' => 'aze',
            ],
        ];

        $data = $p->generateData(2, 6, 2);

        static::assertEquals($out, $data);
    }

    /** @dataProvider dataCeil
     * @param array $configuration
     * @param array $params
     * @param array $dataOut
     */
    public function testIncorrectCeilCompute(array $configuration, array $params, array $dataOut)
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEquals($dataOut, $data);
    }

    /**
     * @return array
     */
    public function dataCeil()
    {
        $configuration = [
            'use_previous' => true,
            'use_next' => true
        ];

        return [
            'Current page incorrect 99 (1 count 1 per page)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 99,
                    'count' => 1,
                    'per_page' => 1
                ],
                'data_out' => [
                    'previous' => [
                        'href' => '1',
                        'text' => 'Previous page',
                    ],
                    'links' => [],
                    'next' => null
                ]
            ],
            'Current page incorrect -1 (1 count 1 per page)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => -1,
                    'count' => 1,
                    'per_page' => 1
                ],
                'data_out' => [
                    'previous' => null,
                    'links' => [],
                    'next' => null,
                ]
            ],
            'Per page incorrect -9 (1 current 1 count)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 1,
                    'count' => 1,
                    'per_page' => -9
                ],
                'data_out' => [
                    'previous' => null,
                    'links' => [],
                    'next' => null,
                ]
            ],
            'Count incorrect -9 (1 current 1 per page)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 1,
                    'count' => -9,
                    'per_page' => 1
                ],
                'data_out' => [
                    'previous' => null,
                    'links' => [],
                    'next' => null,
                ]
            ],
            'Current page incorrect 99 (6 count 2 per page)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 99,
                    'count' => 6,
                    'per_page' => 2
                ],
                'data_out' => [
                    'previous' => [
                        'href' => '3',
                        'text' => 'Previous page',
                    ],
                    'links' => [],
                    'next' => null
                ]
            ],
            'Current page incorrect -1 (6 count 2 per page)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => -1,
                    'count' => 6,
                    'per_page' => 2
                ],
                'data_out' => [
                    'previous' => null,
                    'links' => [
                        [
                            'dots' => false,
                            'current' => true,
                            'href' => '1',
                            'text' => '1'
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '2',
                            'text' => '2'
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '3',
                            'text' => '3'
                        ]
                    ],
                    'next' => [
                        'href' => '2',
                        'text' => 'Next page'
                    ],
                ]
            ],
            'Per page incorrect -9 (2 current 6 count)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 2,
                    'count' => 6,
                    'per_page' => -9
                ],
                'data_out' => [
                    'previous' => [
                        'href' => '1',
                        'text' => 'Previous page'
                    ],
                    'links' => [
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '1',
                            'text' => '1'
                        ],
                        [
                            'dots' => false,
                            'current' => true,
                            'href' => '2',
                            'text' => '2'
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '3',
                            'text' => '3'
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '4',
                            'text' => '4'
                        ]
                    ],
                    'next' => [
                        'href' => '3',
                        'text' => 'Next page'
                    ],
                ]
            ],
            'Count incorrect -9 (2 current 2 per page)' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 2,
                    'count' => -9,
                    'per_page' => 2
                ],
                'data_out' => [
                    'previous' => null,
                    'links' => [],
                    'next' => null,
                ]
            ]
        ];
    }

    /** @dataProvider dataShowAllLinks
     * @param array $configuration
     * @param array $params
     * @param array $dataOut
     */
    public function testShowAllLinks(array $configuration, array $params, array $dataOut)
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEquals($dataOut, $data);
    }

    /**
     * @return array
     */
    public function dataShowAllLinks()
    {
        $configuration = [
            'use_previous' => true,
            'use_next' => true,
            'show_all_links' => true
        ];

        $previous = [
            null,
            [
                'href' => '1',
                'text' => 'Previous page',
            ],
            [
                'href' => '49',
                'text' => 'Previous page',
            ]
        ];

        $next = [
            [
                'href' => '2',
                'text' => 'Next page',
            ],
            [
                'href' => '3',
                'text' => 'Next page',
            ],
            null
        ];

        $linksCurrentPage1 = [];
        $linksCurrentPage2 = [];
        $linksCurrentPage50 = [];
        for ($i = 1; $i < 51; $i++) {
            $linksCurrentPage1[] = [
                'dots' => false,
                'current' => ($i == 1),
                'href' => (string) $i,
                'text' => (string) $i
            ];
            $linksCurrentPage2[] = [
                'dots' => false,
                'current' => ($i == 2),
                'href' => (string) $i,
                'text' => (string) $i
            ];
            $linksCurrentPage50[] = [
                'dots' => false,
                'current' => ($i == 50),
                'href' => (string) $i,
                'text' => (string) $i
            ];
        }
        
        return [
            '50 links + current page 1' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 1,
                    'count' => 50,
                    'per_page' => 1
                ],
                'data_out' => [
                    'previous' => $previous[0],
                    'links' => $linksCurrentPage1,
                    'next' => $next[0]
                ]
            ],
            '50 links + current page 2' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 2,
                    'count' => 50,
                    'per_page' => 1
                ],
                'data_out' => [
                    'previous' => $previous[1],
                    'links' => $linksCurrentPage2,
                    'next' => $next[1]
                ]
            ],
            '50 links + current page 50' => [
                'configuration' => $configuration,
                'params' => [
                    'current' => 50,
                    'count' => 50,
                    'per_page' => 1
                ],
                'data_out' => [
                    'previous' => $previous[2],
                    'links' => $linksCurrentPage50,
                    'next' => $next[2]
                ]
            ]
        ];
    }

    /** @dataProvider dataAdjacentAndLimitConfiguration
     * @param array $configuration
     * @param array $params
     * @param array $dataOut
     */
    public function testAdjacentAndLimitConfiguration(array $configuration, array $params, array $dataOut)
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEquals($dataOut, $data);
    }

    /**
     * @return array
     */
    public function dataAdjacentAndLimitConfiguration()
    {
        $currentPage = [];
        $currentPage[] = [
            'dots' => false,
            'current' => true,
            'href' => (string) 20,
            'text' => (string) 20
        ];

        $limitPagesLeft = [];
        $limitPagesRight = [];
        for ($i = 1; $i < 6; $i++) {
            $limitPagesLeft[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i
            ];
        }
        for ($i = 46; $i < 51; $i++) {
            $limitPagesRight[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i
            ];
        }

        $adjacentPagesLeft = [];
        $adjacentPagesRight = [];
        for ($i = 15; $i < 20; $i++) {
            $adjacentPagesLeft[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i
            ];
        }
        for ($i = 21; $i < 26; $i++) {
            $adjacentPagesRight[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i
            ];
        }
        
        $params = [
            'current' => 20,
            'count' => 50,
            'per_page' => 1
        ];

        return [
            'limit 0 + adjacent 0' => [
                'configuration' => [
                    'count_pages_pair_limit' => 0,
                    'count_pages_pair_adjacent' => 0,
                ],
                'params' => $params,
                'data_out' => [
                    'links' => $currentPage,
                ]
            ],
            'limit 5 + adjacent 0' => [
                'configuration' => [
                    'count_pages_pair_limit' => 5,
                    'count_pages_pair_adjacent' => 0,
                ],
                'params' => $params,
                'data_out' => [
                    'links' => array_merge($limitPagesLeft, $currentPage, $limitPagesRight),
                ]
            ],
            'limit 0 + adjacent 5' => [
                'configuration' => [
                    'count_pages_pair_limit' => 0,
                    'count_pages_pair_adjacent' => 5,
                ],
                'params' => $params,
                'data_out' => [
                    'links' => array_merge($adjacentPagesLeft, $currentPage, $adjacentPagesRight),
                ]
            ],
            'limit 5 + adjacent 5' => [
                'configuration' => [
                    'count_pages_pair_limit' => 5,
                    'count_pages_pair_adjacent' => 5,
                ],
                'params' => $params,
                'data_out' => [
                    'links' => array_merge($limitPagesLeft, $adjacentPagesLeft, $currentPage, $adjacentPagesRight, $limitPagesRight),
                ]
            ]
        ];
    }

    /** @dataProvider dataRenderHtml
     * @param array  $configuration
     * @param array  $params
     * @param string $expectedHtml
     */
    public function testRenderHtml(array $configuration, array $params, string $expectedHtml)
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $html = $p->generateHtml($params['current'], $params['count'], $params['per_page']);
        static::assertSame($expectedHtml, $html);
    }
    
    public function dataRenderHtml()
    {
        return [
            'pretty html off' => [
                'configuration' => [
                    'use_pretty_html' => false
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination navigation">'.
                    '<ul>'.
                    '<li>'.
                    '<a href="1" aria-label="Current page, page 1" aria-current="true" title="1">1</a>'.
                    '</li>'.
                    '<li>'.
                    '<a href="2" aria-label="Goto page 2" title="2">2</a>'.
                    '</li>'.
                    '</ul>'.
                    '</nav>'
            ],
            'initial indentation 4' => [
                'configuration' => [
                    'html_initial_indentation' => 4
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '				<nav role="navigation" aria-label="Pagination navigation">'.PHP_EOL.
                    '					<ul>'.PHP_EOL.
                    '						<li>'.PHP_EOL.
                    '							<a href="1" aria-label="Current page, page 1" aria-current="true" title="1">1</a>'.PHP_EOL.
                    '						</li>'.PHP_EOL.
                    '						<li>'.PHP_EOL.
                    '							<a href="2" aria-label="Goto page 2" title="2">2</a>'.PHP_EOL.
                    '						</li>'.PHP_EOL.
                    '					</ul>'.PHP_EOL.
                    '				</nav>'
            ],
            'initial indentation 4 + four spaces' => [
                'configuration' => [
                    'html_initial_indentation' => 4,
                    'html_tab_sequence' => '    '
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '                <nav role="navigation" aria-label="Pagination navigation">'.PHP_EOL.
                    '                    <ul>'.PHP_EOL.
                    '                        <li>'.PHP_EOL.
                    '                            <a href="1" aria-label="Current page, page 1" aria-current="true" title="1">1</a>'.PHP_EOL.
                    '                        </li>'.PHP_EOL.
                    '                        <li>'.PHP_EOL.
                    '                            <a href="2" aria-label="Goto page 2" title="2">2</a>'.PHP_EOL.
                    '                        </li>'.PHP_EOL.
                    '                    </ul>'.PHP_EOL.
                    '                </nav>'
            ],
            'initial indentation 4 + four spaces + nav off' => [
                'configuration' => [
                    'html_initial_indentation' => 4,
                    'html_tab_sequence' => '    ',
                    'use_nav' => false
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '                <ul>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="1" aria-label="Current page, page 1" aria-current="true" title="1">1</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="2" aria-label="Goto page 2" title="2">2</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                </ul>'
            ],
            'initial indentation 4 + four spaces + previous + after' => [
                'configuration' => [
                    'html_initial_indentation' => 4,
                    'html_tab_sequence' => '    ',
                    'use_nav' => false,
                    'use_next' => true,
                    'use_previous' => true,
                ],
                'params' => [
                    'current' => 2,
                    'count' => 3,
                    'per_page' => 1
                ],
                'expectedHtml' => '                <ul>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="1" aria-label="Previous page" title="Previous page">Previous page</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="1" aria-label="Goto page 1" title="1">1</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="2" aria-label="Current page, page 2" aria-current="true" title="2">2</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="3" aria-label="Goto page 3" title="3">3</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="3" aria-label="Next page" title="Next page">Next page</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                </ul>'
            ],
            'attrs + labels' => [
                'configuration' => [
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
                    'aria_label_link'             => '頁 -> %d',
                    'aria_label_current_link'     => '頁 - %d',
                    'aria_label_nav'              => 'plop',
                    'use_next'                    => true,
                    'use_previous'                => true,
                ],
                'params' => [
                    'current' => 2,
                    'count' => 3,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="plop">'.PHP_EOL.
                    '	<root x="f(x)">'.PHP_EOL.
                    '		<item previous>'.PHP_EOL.
                    '			<zela data-data="o" href="1" aria-label="前" title="前">前</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item class="okay">'.PHP_EOL.
                    '			<zela data-data="o" href="1" aria-label="頁 -> 1" title="1">1</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item data-id="yes">'.PHP_EOL.
                    '			<zela data-id="id" href="2" aria-label="頁 - 2" aria-current="true" title="2">2</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item class="okay">'.PHP_EOL.
                    '			<zela data-data="o" href="3" aria-label="頁 -> 3" title="3">3</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item next>'.PHP_EOL.
                    '			<zela data-data="o" href="3" aria-label="次" title="次">次</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '	</root>'.PHP_EOL.
                    '</nav>'
            ],
            'use dots' => [
                'configuration' => [
                    'use_dots' => true,
                    'text_dots' => 'dots',
                    'item_dot_attrs' => 'dotdot',
                    'count_pages_pair_adjacent' => 0
                ],
                'params' => [
                    'current' => 25,
                    'count' => 50,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination navigation">'.PHP_EOL.
                    '	<ul>'.PHP_EOL.
                    '		<li dotdot>'.PHP_EOL.
                    '			<span>dots</span>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="25" aria-label="Current page, page 25" aria-current="true" title="25">25</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li dotdot>'.PHP_EOL.
                    '			<span>dots</span>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '	</ul>'.PHP_EOL.
                    '</nav>'
            ]
        ];
    }
}