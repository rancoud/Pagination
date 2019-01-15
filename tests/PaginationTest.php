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
        $expected = '<ul ><li ><a  href="1"  title="1">1</a></li><li ><a  href="2"  title="2">2</a></li></ul>';
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

    // security sur attributes + html by default
    // base link + base link after
    // remove start and end when no dots
    // choose count page each side
}
