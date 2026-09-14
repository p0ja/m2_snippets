<?php

declare(strict_types=1);

namespace Vendor\OrderSynchronizer\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Vendor\OrderSynchronizer\Api\Data\OrderErpStatusInterface;
use Vendor\OrderSynchronizer\Model\OrderErpStatusValidator;

/**
 * Run from the Magento root with
 * vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/Vendor/OrderSynchronizer/Test/Unit
 */
class OrderErpStatusValidatorTest extends TestCase
{
    /**
     * @var OrderErpStatusValidator
     */
    private OrderErpStatusValidator $validator;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->validator = new OrderErpStatusValidator();
    }

    /**
     * Test valid items have no errors
     *
     * @return void
     */
    public function testValidItemsHaveNoErrors(): void
    {
        $this->assertSame([], $this->validator->validate([$this->createStatus('000000001', 'ERP-1', 'accepted')]));
    }

    /**
     * Test an empty request is an error
     *
     * @return void
     */
    public function testAnEmptyRequestIsAnError(): void
    {
        $this->assertCount(1, $this->validator->validate([]));
    }

    /**
     * Test every error is reported
     *
     * @return void
     */
    public function testEveryErrorIsReported(): void
    {
        $errors = $this->validator->validate([
            $this->createStatus('000000001', 'ERP-1', 'accepted'),
            $this->createStatus(' ', str_repeat('X', 33), 'done'),
        ]);

        $this->assertSame(
            [
                'Item 1: "increment_id" is required.',
                'Item 1: "erp_order_id" is longer than 32 characters.',
                'Item 1: status "done" is not one of: new, waiting, accepted, synchronized, rejected, error.',
            ],
            array_map('strval', $errors)
        );
    }

    /**
     * Create a status mock
     *
     * @param string $incrementId
     * @param string $erpOrderId
     * @param string $status
     * @return OrderErpStatusInterface
     */
    private function createStatus(string $incrementId, string $erpOrderId, string $status): OrderErpStatusInterface
    {
        return $this->createConfiguredMock(
            OrderErpStatusInterface::class,
            ['getIncrementId' => $incrementId, 'getErpOrderId' => $erpOrderId, 'getStatus' => $status]
        );
    }
}
