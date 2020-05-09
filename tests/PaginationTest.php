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
    /**
     * @throws \Rancoud\Security\SecurityException
     */
    public function testConstruct(): void
    {
        $p = new Pagination();
        $data = $p->generateData(1, 2, 1);
        $out = ['links' => [
            [
                'dots' => false,
                'current' => true,
                'href' => '&#x23;',
                'text' => '1',
                'page' => 1,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Current&#x20;page,&#x20;page&#x20;1"',
                'ariaCurrent' => true
            ],
            [
                'dots' => false,
                'current' => false,
                'href' => '2',
                'text' => '2',
                'page' => 2,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;2"',
                'ariaCurrent' => false
            ]
        ]];
        static::assertEquals($out, $data);

        $html = $p->generateHtml(1, 2, 1);
        $expected = '<nav role="navigation" aria-label="Pagination&#x20;navigation">'.PHP_EOL.
                    '	<ul>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '	</ul>'.PHP_EOL.
                    '</nav>';
        static::assertSame($expected, $html);
    }

    /**
     * @throws \Rancoud\Security\SecurityException
     */
    public function testConfiguration(): void
    {
        $p = new Pagination(['text_previous' => 'toto', 'use_next' => true]);
        $p->setConfiguration(['text_next' => 'aze', 'use_previous' => true]);
        $out = [
            'previous' => [
                'href' => '1',
                'text' => 'toto',
                'page' => 1,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Previous&#x20;page"'
            ],
            'links' => [
                [
                    'dots' => false,
                    'current' => false,
                    'href' => '1',
                    'text' => '1',
                    'page' => 1,
                    'itemAttrs' => '',
                    'linkAttrs' => '',
                    'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;1"',
                    'ariaCurrent' => false
                ],
                [
                    'dots' => false,
                    'current' => true,
                    'href' => '&#x23;',
                    'text' => '2',
                    'page' => 2,
                    'itemAttrs' => '',
                    'linkAttrs' => '',
                    'ariaLabel' => 'aria-label="Current&#x20;page,&#x20;page&#x20;2"',
                    'ariaCurrent' => true
                ],
                [
                    'dots' => false,
                    'current' => false,
                    'href' => '3',
                    'text' => '3',
                    'page' => 3,
                    'itemAttrs' => '',
                    'linkAttrs' => '',
                    'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;3"',
                    'ariaCurrent' => false
                ]
            ],
            'next' => [
                'href' => '3',
                'text' => 'aze',
                'page' => 3,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Next&#x20;page"'
            ],
        ];

        $data = $p->generateData(2, 6, 2);

        static::assertEquals($out, $data);
    }

    /** @dataProvider dataCeil
     * @param array $configuration
     * @param array $params
     * @param array $dataOut
     * @throws \Rancoud\Security\SecurityException
     */
    public function testIncorrectCeilCompute(array $configuration, array $params, array $dataOut): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEquals($dataOut, $data);
    }

    /**
     * @return array
     */
    public function dataCeil(): array
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
                        'page' => 1,
                        'itemAttrs' => '',
                        'linkAttrs' => '',
                        'ariaLabel' => 'aria-label="Previous&#x20;page"'
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
                    'links' => [
                        [
                            'dots' => false,
                            'current' => true,
                            'href' => '&#x23;',
                            'text' => '1',
                            'page' => 1,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Current&#x20;page,&#x20;page&#x20;1"',
                            'ariaCurrent' => true
                        ]
                    ],
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
                    'links' => [
                        [
                            'dots' => false,
                            'current' => true,
                            'href' => '&#x23;',
                            'text' => '1',
                            'page' => 1,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Current&#x20;page,&#x20;page&#x20;1"',
                            'ariaCurrent' => true
                        ]
                    ],
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
                        'page' => 3,
                        'itemAttrs' => '',
                        'linkAttrs' => '',
                        'ariaLabel' => 'aria-label="Previous&#x20;page"'
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
                            'href' => '&#x23;',
                            'text' => '1',
                            'page' => 1,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Current&#x20;page,&#x20;page&#x20;1"',
                            'ariaCurrent' => true
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '2',
                            'text' => '2',
                            'page' => 2,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;2"',
                            'ariaCurrent' => false
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '3',
                            'text' => '3',
                            'page' => 3,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;3"',
                            'ariaCurrent' => false
                        ]
                    ],
                    'next' => [
                        'href' => '2',
                        'text' => 'Next page',
                        'page' => 2,
                        'itemAttrs' => '',
                        'linkAttrs' => '',
                        'ariaLabel' => 'aria-label="Next&#x20;page"'
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
                        'text' => 'Previous page',
                        'page' => 1,
                        'itemAttrs' => '',
                        'linkAttrs' => '',
                        'ariaLabel' => 'aria-label="Previous&#x20;page"'
                    ],
                    'links' => [
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '1',
                            'text' => '1',
                            'page' => 1,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;1"',
                            'ariaCurrent' => false
                        ],
                        [
                            'dots' => false,
                            'current' => true,
                            'href' => '&#x23;',
                            'text' => '2',
                            'page' => 2,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Current&#x20;page,&#x20;page&#x20;2"',
                            'ariaCurrent' => true
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '3',
                            'text' => '3',
                            'page' => 3,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;3"',
                            'ariaCurrent' => false
                        ],
                        [
                            'dots' => false,
                            'current' => false,
                            'href' => '4',
                            'text' => '4',
                            'page' => 4,
                            'itemAttrs' => '',
                            'linkAttrs' => '',
                            'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;4"',
                            'ariaCurrent' => false
                        ]
                    ],
                    'next' => [
                        'href' => '3',
                        'text' => 'Next page',
                        'page' => 3,
                        'itemAttrs' => '',
                        'linkAttrs' => '',
                        'ariaLabel' => 'aria-label="Next&#x20;page"'
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
     * @throws \Rancoud\Security\SecurityException
     */
    public function testShowAllLinks(array $configuration, array $params, array $dataOut): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEquals($dataOut, $data);
    }

    /**
     * @return array
     */
    public function dataShowAllLinks(): array
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
                'page' => 1,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Previous&#x20;page"'
            ],
            [
                'href' => '49',
                'text' => 'Previous page',
                'page' => 49,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Previous&#x20;page"'
            ]
        ];

        $next = [
            [
                'href' => '2',
                'text' => 'Next page',
                'page' => 2,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Next&#x20;page"'
            ],
            [
                'href' => '3',
                'text' => 'Next page',
                'page' => 3,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Next&#x20;page"'
            ],
            null
        ];

        $linksCurrentPage1 = [];
        $linksCurrentPage2 = [];
        $linksCurrentPage50 = [];
        for ($i = 1; $i < 51; $i++) {
            $linksCurrentPage1[] = [
                'dots' => false,
                'current' => ($i === 1),
                'href' => ($i === 1) ? '&#x23;' : (string) $i,
                'text' => (string) $i,
                'page' => $i,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => ($i === 1) ? 'aria-label="Current&#x20;page,&#x20;page&#x20;'.$i.'"' : 'aria-label="Goto&#x20;page&#x20;'.$i.'"',
                'ariaCurrent' => ($i === 1)
            ];
            $linksCurrentPage2[] = [
                'dots' => false,
                'current' => ($i === 2),
                'href' => ($i === 2) ? '&#x23;' : (string) $i,
                'text' => (string) $i,
                'page' => $i,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => ($i === 2) ? 'aria-label="Current&#x20;page,&#x20;page&#x20;'.$i.'"' : 'aria-label="Goto&#x20;page&#x20;'.$i.'"',
                'ariaCurrent' => ($i === 2)
            ];
            $linksCurrentPage50[] = [
                'dots' => false,
                'current' => ($i === 50),
                'href' => ($i === 50) ? '&#x23;' : (string) $i,
                'text' => (string) $i,
                'page' => $i,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => ($i === 50) ? 'aria-label="Current&#x20;page,&#x20;page&#x20;'.$i.'"' : 'aria-label="Goto&#x20;page&#x20;'.$i.'"',
                'ariaCurrent' => ($i === 50)
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
     * @throws \Rancoud\Security\SecurityException
     */
    public function testAdjacentAndLimitConfiguration(array $configuration, array $params, array $dataOut): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $data = $p->generateData($params['current'], $params['count'], $params['per_page']);

        static::assertEquals($dataOut, $data);
    }

    /**
     * @return array
     */
    public function dataAdjacentAndLimitConfiguration(): array
    {
        $currentPage = [];
        $currentPage[] = [
            'dots' => false,
            'current' => true,
            'href' => '&#x23;',
            'text' => (string) 20,
            'page' => 20,
            'itemAttrs' => '',
            'linkAttrs' => '',
            'ariaLabel' => 'aria-label="Current&#x20;page,&#x20;page&#x20;20"',
            'ariaCurrent' => true
        ];

        $limitPagesLeft = [];
        $limitPagesRight = [];
        for ($i = 1; $i < 6; $i++) {
            $limitPagesLeft[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i,
                'page' => $i,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;' . $i . '"',
                'ariaCurrent' => false
            ];
        }
        for ($i = 46; $i < 51; $i++) {
            $limitPagesRight[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i,
                'page' => $i,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;' . $i . '"',
                'ariaCurrent' => false
            ];
        }

        $adjacentPagesLeft = [];
        $adjacentPagesRight = [];
        for ($i = 15; $i < 20; $i++) {
            $adjacentPagesLeft[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i,
                'page' => $i,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;' . $i . '"',
                'ariaCurrent' => false
            ];
        }
        for ($i = 21; $i < 26; $i++) {
            $adjacentPagesRight[] = [
                'dots' => false,
                'current' => false,
                'href' => (string) $i,
                'text' => (string) $i,
                'page' => $i,
                'itemAttrs' => '',
                'linkAttrs' => '',
                'ariaLabel' => 'aria-label="Goto&#x20;page&#x20;' . $i . '"',
                'ariaCurrent' => false
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
     * @param array $configuration
     * @param array $params
     * @param string $expectedHtml
     * @throws \Rancoud\Security\SecurityException
     */
    public function testRenderHtml(array $configuration, array $params, string $expectedHtml): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $html = $p->generateHtml($params['current'], $params['count'], $params['per_page']);
        static::assertSame($expectedHtml, $html);
    }

    /**
     * @return array
     */
    public function dataRenderHtml(): array
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
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">'.
                    '<ul>'.
                    '<li>'.
                    '<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>'.
                    '</li>'.
                    '<li>'.
                    '<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>'.
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
                'expectedHtml' => '				<nav role="navigation" aria-label="Pagination&#x20;navigation">'.PHP_EOL.
                    '					<ul>'.PHP_EOL.
                    '						<li>'.PHP_EOL.
                    '							<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>'.PHP_EOL.
                    '						</li>'.PHP_EOL.
                    '						<li>'.PHP_EOL.
                    '							<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>'.PHP_EOL.
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
                'expectedHtml' => '                <nav role="navigation" aria-label="Pagination&#x20;navigation">'.PHP_EOL.
                    '                    <ul>'.PHP_EOL.
                    '                        <li>'.PHP_EOL.
                    '                            <a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>'.PHP_EOL.
                    '                        </li>'.PHP_EOL.
                    '                        <li>'.PHP_EOL.
                    '                            <a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>'.PHP_EOL.
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
                    '                        <a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                </ul>'
            ],
            'initial indentation 4 + four spaces + nav off + previous + after' => [
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
                    '                        <a href="1" aria-label="Previous&#x20;page">Previous page</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>'.PHP_EOL.
                    '                    </li>'.PHP_EOL.
                    '                    <li>'.PHP_EOL.
                    '                        <a href="3" aria-label="Next&#x20;page">Next page</a>'.PHP_EOL.
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
                    'aria_label_link'             => '頁 -> {{PAGE}}',
                    'aria_label_current_link'     => '頁 - {{PAGE}}',
                    'aria_label_nav'              => 'plop',
                    'aria_label_previous'         => 'aria-前',
                    'aria_label_next'             => 'aria-次',
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
                    '			<zela data-data="o" href="1" aria-label="aria-&#x524D;">前</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item class="okay">'.PHP_EOL.
                    '			<zela data-data="o" href="1" aria-label="&#x9801;&#x20;-&gt;&#x20;1">1</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item data-id="yes">'.PHP_EOL.
                    '			<zela data-id="id" href="&#x23;" aria-label="&#x9801;&#x20;-&#x20;2" aria-current="true">2</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item class="okay">'.PHP_EOL.
                    '			<zela data-data="o" href="3" aria-label="&#x9801;&#x20;-&gt;&#x20;3">3</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '		<item next>'.PHP_EOL.
                    '			<zela data-data="o" href="3" aria-label="aria-&#x6B21;">次</zela>'.PHP_EOL.
                    '		</item>'.PHP_EOL.
                    '	</root>'.PHP_EOL.
                    '</nav>'
            ],
            'use dots' => [
                'configuration' => [
                    'use_dots' => true,
                    'text_dots' => 'dots',
                    'item_dots_attrs' => 'dotdot',
                    'count_pages_pair_adjacent' => 0
                ],
                'params' => [
                    'current' => 25,
                    'count' => 50,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">'.PHP_EOL.
                    '	<ul>'.PHP_EOL.
                    '		<li dotdot>'.PHP_EOL.
                    '			<span>dots</span>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;25" aria-current="true">25</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li dotdot>'.PHP_EOL.
                    '			<span>dots</span>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '	</ul>'.PHP_EOL.
                    '</nav>'
            ],
            'previous + next + attrs not empty + data disabled' => [
                'configuration' => [
                    'always_use_previous' => true,
                    'always_use_next' => true,
                    'item_previous_attrs' => 'item_previous_attrs',
                    'item_next_attrs' => 'item_next_attrs',
                ],
                'params' => [
                    'current' => 1,
                    'count' => 1,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">'.PHP_EOL.
                    '	<ul>'.PHP_EOL.
                    '		<li item_previous_attrs data-disabled>'.PHP_EOL.
                    '			<a href="&#x23;" aria-label="Previous&#x20;page">Previous page</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li item_next_attrs data-disabled>'.PHP_EOL.
                    '			<a href="&#x23;" aria-label="Next&#x20;page">Next page</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '	</ul>'.PHP_EOL.
                    '</nav>'
            ],
            'empty text + empty tag' => [
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
                    'current' => 2,
                    'count' => 3,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation">'.PHP_EOL.
                    '	<ul>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="1"></a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="1">1</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="&#x23;" aria-current="true">2</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="3">3</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="3"></a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '	</ul>'.PHP_EOL.
                    '</nav>'
            ],
            'security' => [
                'configuration' => [
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
                    'count_pages_pair_limit'            => 1
                ],
                'params' => [
                    'current' => 2,
                    'count' => 3000,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">'.PHP_EOL.
                    '	<ul>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="1" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt;</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="1" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 1</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="&#x23;" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;" aria-current="true">&lt;script&gt;alert(1);&lt;&#47;script&gt; 2</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="3" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 3</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="4" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 4</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<span>&lt;script&gt;alert(1);&lt;&#47;script&gt;</span>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="3000" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt; 3&lt;script&gt;alert(1);&lt;&#47;script&gt;000</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '		<li>'.PHP_EOL.
                    '			<a href="3" aria-label="&quot;&lt;script&gt;alert&#x28;1&#x29;&#x3B;&lt;&#x2F;script&gt;&quot;">&lt;script&gt;alert(1);&lt;&#47;script&gt;</a>'.PHP_EOL.
                    '		</li>'.PHP_EOL.
                    '	</ul>'.PHP_EOL.
                    '</nav>'
            ]
        ];
    }

    /** @dataProvider dataCountPages
     * @param int  $countElements
     * @param int  $countElementPerPage
     * @param int  $expected
     */
    public function testCountPages(int $countElements, int $countElementPerPage, int $expected): void
    {
        static::assertSame($expected, Pagination::countPages($countElements, $countElementPerPage));
    }

    /**
     * @return array
     */
    public function dataCountPages(): array
    {
        return [
            '1 item + 1 per page' => [1, 1, 1],
            '10 item + 1 per page' => [10, 1, 10],
            '10 item + 5 per page' => [10, 5, 2],
            '11 item + 5 per page' => [11, 5, 3],
        ];
    }

    /** @dataProvider dataLocateItemInPage
     * @param int $countElementPerPage
     * @param int $itemIndex
     * @param int $expected
     */
    public function testLocateItemInPage(int $countElementPerPage, int $itemIndex, int $expected): void
    {
        static::assertSame($expected, Pagination::locateItemInPage($countElementPerPage, $itemIndex));
    }

    /**
     * @return array
     */
    public function dataLocateItemInPage(): array
    {
        return [
            '1 per page + item index 1' => [1, 1, 1],
            '10 per page + item index 1' => [10, 1, 1],
            '10 per page + item index 5' => [10, 5, 1],
            '11 per page + item index 5' => [11, 5, 1],
            '10 per page + item index 10' => [10, 10, 1],
            '10 per page + item index 11' => [10, 11, 2],
            '5 per page + item index 25' => [5, 25, 5],
            '5 per page + item index 26' => [5, 26, 6]
        ];
    }

    /** @dataProvider dataRenderHtmlForReadme
     * @param array $configuration
     * @param array $params
     * @param string $expectedHtml
     * @throws \Rancoud\Security\SecurityException
     */
    public function testRenderHtmlForReadme(array $configuration, array $params, string $expectedHtml): void
    {
        $p = new Pagination();
        $p->setConfiguration($configuration);
        $html = $p->generateHtml($params['current'], $params['count'], $params['per_page']);
        $expectedHtml = str_replace("\n", PHP_EOL, $expectedHtml);
        static::assertSame($expectedHtml, $html);
    }

    /**
     * @return array
     */
    public function dataRenderHtmlForReadme(): array
    {
        return [
            'url (page append at the end)' => [
                'configuration' => [
                    'url' => 'https://example.com/news/page/'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;news&#x2F;page&#x2F;2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'url (page replace with {{PAGE}} pattern)' => [
                'configuration' => [
                    'url' => 'https://example.com/news/page/{{PAGE}}/?date=desc'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="https&#x3A;&#x2F;&#x2F;example.com&#x2F;news&#x2F;page&#x2F;2&#x2F;&#x3F;date&#x3D;desc" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'url after page (replace with {{PAGE}} pattern)' => [
                'configuration' => [
                    'url' => '{{PAGE}}?date=desc'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2&#x3F;date&#x3D;desc" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'show_all_links' => [
                'configuration' => [
                    'show_all_links' => true
                ],
                'params' => [
                    'current' => 1,
                    'count' => 30,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
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
</nav>'
            ],
            'use_previous' => [
                'configuration' => [
                    'use_previous' => true
                ],
                'params' => [
                    'current' => 2,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="1" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>'
            ],
            'always_use_previous' => [
                'configuration' => [
                    'always_use_previous' => true
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li data-disabled>
			<a href="&#x23;" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'use_next' => [
                'configuration' => [
                    'use_next' => true
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="2" aria-label="Next&#x20;page">Next page</a>
		</li>
	</ul>
</nav>'
            ],
            'always_use_next' => [
                'configuration' => [
                    'always_use_next' => true
                ],
                'params' => [
                    'current' => 2,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
		<li data-disabled>
			<a href="&#x23;" aria-label="Next&#x20;page">Next page</a>
		</li>
	</ul>
</nav>'
            ],
            'use_dots' => [
                'configuration' => [
                    'use_dots' => true
                ],
                'params' => [
                    'current' => 1,
                    'count' => 30,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
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
</nav>'
            ],
            'count_pages_pair_limit' => [
                'configuration' => [
                    'count_pages_pair_limit' => 1
                ],
                'params' => [
                    'current' => 5,
                    'count' => 300,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
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
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
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
</nav>'
            ],
            'count_pages_pair_adjacent' => [
                'configuration' => [
                    'count_pages_pair_adjacent' => 1
                ],
                'params' => [
                    'current' => 5,
                    'count' => 300,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="4" aria-label="Goto&#x20;page&#x20;4">4</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
		</li>
		<li>
			<a href="6" aria-label="Goto&#x20;page&#x20;6">6</a>
		</li>
	</ul>
</nav>'
            ],
            'count_pages_pair_limit 0 + count_pages_pair_adjacent 0' => [
                'configuration' => [
                    'count_pages_pair_limit' => 0,
                    'count_pages_pair_adjacent' => 0
                ],
                'params' => [
                    'current' => 5,
                    'count' => 300,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
		</li>
	</ul>
</nav>'
            ],
            'count_pages_pair_limit 2 + count_pages_pair_adjacent 2' => [
                'configuration' => [
                    'count_pages_pair_limit' => 2,
                    'count_pages_pair_adjacent' => 2
                ],
                'params' => [
                    'current' => 5,
                    'count' => 300,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
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
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;5" aria-current="true">5</a>
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
</nav>'
            ],
            'text_previous' => [
                'configuration' => [
                    'use_previous' => true,
                    'text_previous' => 'prev'
                ],
                'params' => [
                    'current' => 2,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="1" aria-label="Previous&#x20;page">prev</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>'
            ],
            'text_next' => [
                'configuration' => [
                    'use_next' => true,
                    'text_next' => 'next'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="2" aria-label="Next&#x20;page">next</a>
		</li>
	</ul>
</nav>'
            ],
            'text_dots' => [
                'configuration' => [
                    'use_dots' => true,
                    'text_dots' => 'dots'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 30,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
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
</nav>'
            ],
            'text_page (page append at the end)' => [
                'configuration' => [
                    'text_page' => 'yolo'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">yolo 1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">yolo 2</a>
		</li>
	</ul>
</nav>'
            ],
            'text_page (page replace with {{PAGE}} pattern)' => [
                'configuration' => [
                    'text_page' => 'yo {{PAGE}} lo'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">yo 1 lo</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">yo 2 lo</a>
		</li>
	</ul>
</nav>'
            ],
            'aria_label_link' => [
                'configuration' => [
                    'aria_label_link' => 'aria label link'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="aria&#x20;label&#x20;link">2</a>
		</li>
	</ul>
</nav>'
            ],
            'aria_label_link (with {{PAGE}} pattern)' => [
                'configuration' => [
                    'aria_label_link' => 'aria label link {{PAGE}}'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="aria&#x20;label&#x20;link&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'aria_label_current_link' => [
                'configuration' => [
                    'aria_label_current_link' => 'aria label current link'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="aria&#x20;label&#x20;current&#x20;link" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'aria_label_current_link (with {{PAGE}} pattern)' => [
                'configuration' => [
                    'aria_label_current_link' => 'aria label current link {{PAGE}}'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="aria&#x20;label&#x20;current&#x20;link&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'aria_label_nav' => [
                'configuration' => [
                    'aria_label_nav' => 'aria label nav'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="aria&#x20;label&#x20;nav">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'aria_label_previous' => [
                'configuration' => [
                    'use_previous' => true,
                    'aria_label_previous' => 'prev'
                ],
                'params' => [
                    'current' => 2,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="1" aria-label="prev">Previous page</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>'
            ],
            'aria_label_next' => [
                'configuration' => [
                    'use_next' => true,
                    'aria_label_next' => 'next'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="2" aria-label="next">Next page</a>
		</li>
	</ul>
</nav>'
            ],
            'thousands_separator' => [
                'configuration' => [
                    'thousands_separator' => ';',
                    'count_pages_pair_limit' => 1
                ],
                'params' => [
                    'current' => 1,
                    'count' => 1000,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
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
</nav>'
            ],
            'root_tag' => [
                'configuration' => [
                    'root_tag' => 'root'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<root>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</root>
</nav>'
            ],
            'root_attrs' => [
                'configuration' => [
                    'root_attrs' => 'root attrs'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul root attrs>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'use_nav' => [
                'configuration' => [
                    'use_nav' => false
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<ul>
	<li>
		<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
	</li>
	<li>
		<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
	</li>
</ul>'
            ],
            'item_tag' => [
                'configuration' => [
                    'item_tag' => 'item'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<item>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</item>
		<item>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</item>
	</ul>
</nav>'
            ],
            'item_attrs' => [
                'configuration' => [
                    'item_attrs' => 'item attrs'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li item attrs>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'item_attrs (with {{PAGE}} pattern)' => [
                'configuration' => [
                    'item_attrs' => 'item attrs data-page="{{PAGE}}"'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li item attrs data-page="2">
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'item_attrs_current' => [
                'configuration' => [
                    'item_attrs_current' => 'item attrs current'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li item attrs current>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'item_attrs_current (with {{PAGE}} pattern)' => [
                'configuration' => [
                    'item_attrs_current' => 'item attrs current data-page="{{PAGE}}"'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li item attrs current data-page="1">
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'item_previous_attrs' => [
                'configuration' => [
                    'use_previous' => true,
                    'item_previous_attrs' => 'item previous attrs'
                ],
                'params' => [
                    'current' => 2,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li item previous attrs>
			<a href="1" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>'
            ],
            'item_previous_attrs (page replace with {{PAGE}} pattern)' => [
                'configuration' => [
                    'use_previous' => true,
                    'item_previous_attrs' => 'item previous attrs data-page="{{PAGE}}"'
                ],
                'params' => [
                    'current' => 2,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li item previous attrs data-page="1">
			<a href="1" aria-label="Previous&#x20;page">Previous page</a>
		</li>
		<li>
			<a href="1" aria-label="Goto&#x20;page&#x20;1">1</a>
		</li>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;2" aria-current="true">2</a>
		</li>
	</ul>
</nav>'
            ],
            'item_next_attrs' => [
                'configuration' => [
                    'use_next' => true,
                    'item_next_attrs' => 'item next attrs'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li item next attrs>
			<a href="2" aria-label="Next&#x20;page">Next page</a>
		</li>
	</ul>
</nav>'
            ],
            'item_next_attrs (page replace with {{PAGE}} pattern)' => [
                'configuration' => [
                    'use_next' => true,
                    'item_next_attrs' => 'item next attrs data-page="{{PAGE}}"'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li item next attrs data-page="2">
			<a href="2" aria-label="Next&#x20;page">Next page</a>
		</li>
	</ul>
</nav>'
            ],
            'item_dots_attrs' => [
                'configuration' => [
                    'use_dots' => true,
                    'item_dots_attrs' => 'item dots attrs'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 30,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
		<li>
			<a href="3" aria-label="Goto&#x20;page&#x20;3">3</a>
		</li>
		<li item dots attrs>
			<span>…</span>
		</li>
	</ul>
</nav>'
            ],
            'link_tag' => [
                'configuration' => [
                    'link_tag' => 'link'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 10,
                    'per_page' => 5
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<link href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</link>
		</li>
		<li>
			<link href="2" aria-label="Goto&#x20;page&#x20;2">2</link>
		</li>
	</ul>
</nav>'
            ],
            'link_attrs' => [
                'configuration' => [
                    'link_attrs' => 'link attrs'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a link attrs href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'link_attrs (page replace with {{PAGE}} pattern)' => [
                'configuration' => [
                    'link_attrs' => 'link attrs data-page="{{PAGE}}"'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a link attrs data-page="2" href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'link_attrs_current' => [
                'configuration' => [
                    'link_attrs_current' => 'link attrs current'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a link attrs current href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'link_attrs_current (page replace with {{PAGE}} pattern)' => [
                'configuration' => [
                    'link_attrs_current' => 'link attrs current data-page="{{PAGE}}"'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a link attrs current data-page="1" href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'use_pretty_html' => [
                'configuration' => [
                    'use_pretty_html' => false
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation"><ul><li><a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a></li><li><a href="2" aria-label="Goto&#x20;page&#x20;2">2</a></li></ul></nav>'
            ],
            'html_tab_sequence' => [
                'configuration' => [
                    'html_tab_sequence' => ''
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
<ul>
<li>
<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
</li>
<li>
<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
</li>
</ul>
</nav>'
            ],
            'html_initial_indentation' => [
                'configuration' => [
                    'html_initial_indentation' => 1
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '	<nav role="navigation" aria-label="Pagination&#x20;navigation">
		<ul>
			<li>
				<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
			</li>
			<li>
				<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
			</li>
		</ul>
	</nav>'
            ],
            'esc_attr' => [
                'configuration' => [
                    'esc_attr' => false
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination navigation">
	<ul>
		<li>
			<a href="#" aria-label="Current page, page 1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto page 2">2</a>
		</li>
	</ul>
</nav>'
            ],
            'esc_html' => [
                'configuration' => [
                    'esc_html' => false,
                    'text_page' => '<em>{{PAGE}}</em>'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true"><em>1</em></a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2"><em>2</em></a>
		</li>
	</ul>
</nav>'
            ],
            'charset' => [
                'configuration' => [
                    'charset' => 'EUC-JP'
                ],
                'params' => [
                    'current' => 1,
                    'count' => 2,
                    'per_page' => 1
                ],
                'expectedHtml' => '<nav role="navigation" aria-label="Pagination&#x20;navigation">
	<ul>
		<li>
			<a href="&#x23;" aria-label="Current&#x20;page,&#x20;page&#x20;1" aria-current="true">1</a>
		</li>
		<li>
			<a href="2" aria-label="Goto&#x20;page&#x20;2">2</a>
		</li>
	</ul>
</nav>'
            ]
        ];
    }
}