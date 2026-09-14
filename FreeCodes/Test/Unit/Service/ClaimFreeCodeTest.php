<?php

declare(strict_types=1);

namespace Vendor\FreeCodes\Test\Unit\Service;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Vendor\FreeCodes\Model\ResourceModel\FreeCode as FreeCodeResource;
use Vendor\FreeCodes\Model\Session;
use Vendor\FreeCodes\Service\ClaimFreeCode;

/**
 * Run from the Magento root with
 * vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/Vendor/FreeCodes/Test/Unit
 */
class ClaimFreeCodeTest extends TestCase
{
    /**
     * @var FreeCodeResource|MockObject
     */
    private $freeCodeResource;

    /**
     * @var Session|MockObject
     */
    private $session;

    /**
     * @var ClaimFreeCode
     */
    private ClaimFreeCode $claimFreeCode;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->freeCodeResource = $this->createMock(FreeCodeResource::class);
        $this->session = $this->createMock(Session::class);
        $this->claimFreeCode = new ClaimFreeCode($this->freeCodeResource, $this->session);
    }

    /**
     * Test the code of the session is returned without touching the pool
     *
     * @return void
     */
    public function testTheCodeOfTheSessionIsReturnedWithoutTouchingThePool(): void
    {
        $this->session->method('getClaimedCode')->willReturn('SAMPLE-0001');
        $this->freeCodeResource->expects($this->never())->method('findFreeCode');

        $this->assertSame('SAMPLE-0001', $this->claimFreeCode->execute());
    }

    /**
     * Test a claimed code is stored in the session
     *
     * @return void
     */
    public function testAClaimedCodeIsStoredInTheSession(): void
    {
        $this->freeCodeResource->method('findFreeCode')->willReturn(['code_id' => '2', 'code' => 'SAMPLE-0002']);
        $this->freeCodeResource->method('claim')->with(2)->willReturn(true);
        $this->session->expects($this->once())->method('setClaimedCode')->with('SAMPLE-0002');

        $this->assertSame('SAMPLE-0002', $this->claimFreeCode->execute());
    }

    /**
     * Test an empty pool returns null
     *
     * @return void
     */
    public function testAnEmptyPoolReturnsNull(): void
    {
        $this->freeCodeResource->method('findFreeCode')->willReturn(null);
        $this->session->expects($this->never())->method('setClaimedCode');

        $this->assertNull($this->claimFreeCode->execute());
    }

    /**
     * Test a code claimed by another visitor is skipped
     *
     * @return void
     */
    public function testACodeClaimedByAnotherVisitorIsSkipped(): void
    {
        $this->freeCodeResource->method('findFreeCode')->willReturnOnConsecutiveCalls(
            ['code_id' => '3', 'code' => 'SAMPLE-0003'],
            ['code_id' => '4', 'code' => 'SAMPLE-0004']
        );
        $this->freeCodeResource->method('claim')->willReturnMap([[3, false], [4, true]]);

        $this->assertSame('SAMPLE-0004', $this->claimFreeCode->execute());
    }

    /**
     * Test no unclaimed code is returned when every claim is lost
     *
     * @return void
     */
    public function testNoUnclaimedCodeIsReturnedWhenEveryClaimIsLost(): void
    {
        $this->freeCodeResource->method('findFreeCode')->willReturn(['code_id' => '5', 'code' => 'SAMPLE-0005']);
        $this->freeCodeResource->expects($this->exactly(5))->method('claim')->willReturn(false);
        $this->session->expects($this->never())->method('setClaimedCode');

        $this->assertNull($this->claimFreeCode->execute());
    }
}
