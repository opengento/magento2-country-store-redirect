<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Test\Unit\Model\Redirect\IsAllowed;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Opengento\CountryStore\Api\CountryRegistryInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowed\IsInitial;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Opengento\CountryStoreRedirect\Model\IsAllowed\IsInitial
 */
class IsInitialTest extends TestCase
{
    /**
     * @var MockObject|DataPersistorInterface
     */
    private $dataPersistor;

    private IsInitial $isInitial;

    protected function setUp(): void
    {
        $this->dataPersistor = $this->getMockForAbstractClass(DataPersistorInterface::class);

        $this->isInitial = new IsInitial($this->dataPersistor);
    }

    /**
     * @dataProvider isAllowedData
     */
    public function testIsAllowed($countryCode, bool $isAllowed): void
    {
        $this->dataPersistor->expects($this->once())
            ->method('get')
            ->with(CountryRegistryInterface::PARAM_KEY)
            ->willReturn($countryCode);

        $this->assertSame(
            $isAllowed,
            $this->isInitial->isAllowed($this->getMockForAbstractClass(RequestInterface::class))
        );
    }

    public function isAllowedData(): array
    {
        return [
            ['FR', false],
            ['US', false],
            [true, false],
            [null, true],
            ['', true],
            ['0', true],
            [false, true],
        ];
    }
}
