<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Test\Unit\Model\Redirect\IsAllowed;

use Magento\Framework\App\Console\Request as ConsoleRequest;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Webapi\Request as WebapiRequest;
use Magento\Framework\Webapi\Rest\Request as RestRequest;
use Opengento\CountryStoreRedirect\Model\IsAllowed\IsDirect;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Opengento\CountryStoreRedirect\Model\IsAllowed\IsDirect
 */
class IsDirectTest extends TestCase
{
    private IsDirect $isDirect;

    protected function setUp(): void
    {
        $this->isDirect = new IsDirect();
    }

    /**
     * @dataProvider isAllowedData
     */
    public function testIsAllowed(RequestInterface $request, bool $isAllowed): void
    {
        $this->assertSame($isAllowed, $this->isDirect->isAllowed($request));
    }

    public function isAllowedData(): array
    {
        return [
            [$this->createRequestMock(Http::class, true, true), false],
            [$this->createRequestMock(Http::class, true, false), true],
            [$this->createRequestMock(Http::class, false, false), false],
            [$this->createRequestMock(Http::class, false, true), false],
            [$this->getMockForAbstractClass(RequestInterface::class), false],
            [$this->createMock(ConsoleRequest::class), false],
            [$this->createMock(RestRequest::class), false],
            [$this->createMock(WebapiRequest::class), false],
        ];
    }

    private function createRequestMock(string $implement, bool $isGet, bool $isAjax): MockObject
    {
        $requestMock = $this->getMockBuilder($implement)->disableOriginalConstructor()->getMock();

        $requestMock->expects($this->once())->method('isGet')->willReturn($isGet);
        $requestMock->expects($isGet ? $this->once() : $this->never())->method('isAjax')->willReturn($isAjax);

        return $requestMock;
    }
}
