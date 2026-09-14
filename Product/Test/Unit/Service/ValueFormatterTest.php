<?php

declare(strict_types=1);

namespace M2\Product\Test\Unit\Service;

use Magento\Framework\DataObject;
use M2\Product\Service\ValueFormatter;
use PHPUnit\Framework\TestCase;

/**
 * Run from the Magento root with
 * vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/M2/Product/Test/Unit
 */
class ValueFormatterTest extends TestCase
{
    /**
     * @var ValueFormatter
     */
    private ValueFormatter $formatter;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->formatter = new ValueFormatter();
    }

    /**
     * Test nested arrays and data objects become dotted keys
     *
     * @return void
     */
    public function testNestedArraysAndDataObjectsBecomeDottedKeys(): void
    {
        $data = [
            'sku' => 'sample',
            'status' => 1,
            'media_gallery' => ['images' => [['file' => '/s/a/sample.jpg']]],
            'stock_item' => new DataObject(['qty' => 5.0, 'is_in_stock' => true]),
            'options' => [],
            'special_price' => null,
        ];

        $this->assertSame(
            [
                'sku' => 'sample',
                'status' => '1',
                'media_gallery.images.0.file' => '/s/a/sample.jpg',
                'stock_item.qty' => '5',
                'stock_item.is_in_stock' => 'true',
                'options' => '[]',
                'special_price' => 'NULL',
            ],
            $this->formatter->flatten($data)
        );
    }

    /**
     * Test objects are printed as their class name
     *
     * @return void
     */
    public function testObjectsArePrintedAsTheirClassName(): void
    {
        $this->assertSame('Closure', $this->formatter->format(static fn () => null));
    }

    /**
     * Test deep nesting is cut
     *
     * @return void
     */
    public function testDeepNestingIsCut(): void
    {
        $rows = $this->formatter->flatten(['a' => ['b' => ['c' => ['d' => ['e' => ['f' => ['g' => 1]]]]]]]);

        $this->assertSame(['a.b.c.d.e.f' => '[...]'], $rows);
    }
}
