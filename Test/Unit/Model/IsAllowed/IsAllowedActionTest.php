<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Test\Unit\Model\Redirect\IsAllowed;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowed\IsAllowedAction;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Opengento\CountryStoreRedirect\Model\IsAllowed\IsAllowedAction
 */
class IsAllowedActionTest extends TestCase
{
    /**
     * @var MockObject|ScopeConfigInterface
     */
    private $scopeConfig;

    private IsAllowedAction $isAllowedAction;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('country/redirect/ignore_actions')
            ->willReturn(' route/controller/actionA, route/controller/actionB , rootAction,');

        $this->isAllowedAction = new IsAllowedAction($this->scopeConfig);
    }

    /**
     * @dataProvider isAllowedData
     */
    public function testIsAllowed(RequestInterface $request, bool $isAllowed): void
    {
        $this->assertSame($isAllowed, $this->isAllowedAction->isAllowed($request));
    }

    public function isAllowedData(): array
    {
        return [
            [$this->createRequestMock('route/controller/actionC'), true],
            [$this->createRequestMock('route/controller/actionA'), false],
            [$this->createRequestMock('route/controller/actiona'), false],
            [$this->createRequestMock('route/controller/actionB'), false],
            [$this->createRequestMock('route/controller/actionb'), false],
            [$this->createRequestMock('rootAction'), false],
            [$this->createRequestMock('rootaction'), false],
            [$this->createRequestMock('route/controller/actionD'), true],
            [$this->createRequestMock('route/controller/action_e'), true],
            [$this->createRequestMock(''), true],
            [$this->createRequestMock(' '), true],
            [$this->createRequestMock('/'), true],
        ];
    }

    private function createRequestMock(string $action): MockObject
    {
        $request = $this->createMock(Http::class);
        $request->expects($this->once())->method('getFullActionName')->willReturn($action);

        return $request;
    }
}
