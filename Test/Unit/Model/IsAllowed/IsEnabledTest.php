<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Test\Unit\Model\Redirect\IsAllowed;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowed\IsEnabled;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Opengento\CountryStoreRedirect\Model\IsAllowed\IsEnabled
 */
class IsEnabledTest extends TestCase
{
    /**
     * @var MockObject|ScopeConfigInterface
     */
    private $scopeConfig;

    private IsEnabled $isEnabled;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);

        $this->isEnabled = new IsEnabled($this->scopeConfig);
    }

    /**
     * @dataProvider isAllowedData
     */
    public function testIsAllowed(RequestInterface $request, bool $isEnabled, bool $isAllowed): void
    {
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('country/redirect/enabled', 'store')
            ->willReturn($isEnabled);

        $this->assertSame($isAllowed, $this->isEnabled->isAllowed($request));
    }

    public function isAllowedData(): array
    {
        return [
            [$this->getMockForAbstractClass(RequestInterface::class), true, true],
            [$this->getMockForAbstractClass(RequestInterface::class), false, false],
        ];
    }
}
