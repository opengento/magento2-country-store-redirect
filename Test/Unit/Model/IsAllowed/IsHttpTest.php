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
use Opengento\CountryStoreRedirect\Model\IsAllowed\IsHttp;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Opengento\CountryStoreRedirect\Model\IsAllowed\IsHttp
 */
class IsHttpTest extends TestCase
{
    private IsHttp $isHttp;

    protected function setUp(): void
    {
        $this->isHttp = new IsHttp();
    }

    /**
     * @dataProvider isAllowedData
     */
    public function testIsAllowed(RequestInterface $request, bool $isAllowed): void
    {
        $this->assertSame($isAllowed, $this->isHttp->isAllowed($request));
    }

    public function isAllowedData(): array
    {
        return [
            [$this->createMock(Http::class), true],
            [$this->getMockForAbstractClass(RequestInterface::class), false],
            [$this->createMock(ConsoleRequest::class), false],
            [$this->createMock(RestRequest::class), false],
            [$this->createMock(WebapiRequest::class), false],
        ];
    }
}
