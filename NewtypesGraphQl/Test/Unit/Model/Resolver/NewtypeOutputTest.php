<?php

declare(strict_types=1);

namespace Vendor\NewtypesGraphQl\Test\Unit\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Vendor\NewtypesGraphQl\Model\Resolver\DataProvider\Newtypes as NewtypesDataProvider;
use Vendor\NewtypesGraphQl\Model\Resolver\NewtypeOutput;

/**
 * Added: unit tests for the input handling of the getNewtypes resolver (PHPUnit 10, as used by Magento 2.4.8).
 */
class NewtypeOutputTest extends TestCase
{
    /**
     * @var NewtypesDataProvider|MockObject
     */
    private $dataProvider;

    /**
     * @var NewtypeOutput
     */
    private NewtypeOutput $resolver;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->dataProvider = $this->createMock(NewtypesDataProvider::class);
        $this->resolver = new NewtypeOutput($this->dataProvider, $this->createMock(LoggerInterface::class));
    }

    /**
     * Test missing input is an input error
     *
     * @return void
     */
    public function testMissingInputIsAnInputError(): void
    {
        $this->expectException(GraphQlInputException::class);

        $this->resolve(null);
    }

    /**
     * Test invalid date is an input error
     *
     * @return void
     */
    public function testInvalidDateIsAnInputError(): void
    {
        $this->expectException(GraphQlInputException::class);

        $this->resolve(['input' => ['date' => 'not a date']]);
    }

    /**
     * Test valid input is trimmed and passed to the data provider
     *
     * @return void
     */
    public function testValidInputIsTrimmedAndPassedToTheDataProvider(): void
    {
        $this->dataProvider->expects($this->once())
            ->method('getData')
            ->with(['date' => '2025-04-10', 'store_id' => ['eq' => '1']])
            ->willReturn([]);

        $result = $this->resolve(['input' => ['date' => ' 2025-04-10 ', 'store_id' => ['eq' => 1]]]);

        $this->assertSame(['newtypes' => []], $result);
    }

    /**
     * Resolve
     *
     * @param array|null $args
     * @return array
     */
    private function resolve(?array $args): array
    {
        return $this->resolver->resolve(
            $this->createMock(Field::class),
            null,
            $this->createMock(ResolveInfo::class),
            null,
            $args
        );
    }
}
