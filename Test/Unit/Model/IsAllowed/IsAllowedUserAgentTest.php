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
use Opengento\CountryStoreRedirect\Model\IsAllowed\IsAllowedUserAgent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Opengento\CountryStoreRedirect\Model\IsAllowed\IsAllowedUserAgent
 */
class IsAllowedUserAgentTest extends TestCase
{
    /**
     * @var MockObject|ScopeConfigInterface
     */
    private $scopeConfig;

    private IsAllowedUserAgent $isAllowedUserAgent;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->scopeConfig
            ->method('getValue')
            ->with('country/redirect/ignore_user_agents')
            ->willReturn(' userAgentA, userAgentB , userAgentC,');

        $this->isAllowedUserAgent = new IsAllowedUserAgent($this->scopeConfig);
    }

    /**
     * @dataProvider isAllowedData
     */
    public function testIsAllowed(RequestInterface $request, bool $isAllowed): void
    {
        $this->assertSame($isAllowed, $this->isAllowedUserAgent->isAllowed($request));
    }

    public function isAllowedData(): array
    {
        return [
            [$this->createRequestMock('userAgentA'), false],
            [$this->createRequestMock('useragenta'), false],
            [$this->createRequestMock('useragent_a'), true],
            [$this->createRequestMock('userAgentB'), false],
            [$this->createRequestMock('useragentb'), false],
            [$this->createRequestMock('useragent_b'), true],
            [$this->createRequestMock('userAgentC'), false],
            [$this->createRequestMock('useragentc'), false],
            [$this->createRequestMock('useragent_c'), true],
            [$this->createRequestMock('userAgentD'), true],
            [$this->createRequestMock('useragentd'), true],
            [$this->createRequestMock('useragent_d'), true],
            [$this->createRequestMock(''), true],
            [$this->createRequestMock(' '), true],
            [$this->createRequestMock(false), true],
            [$this->createRequestMock(null), true],
            [$this->createRequestMock(0), true],
        ];
    }

    private function createRequestMock($userAgent): MockObject
    {
        $request = $this->createMock(Http::class);
        $request->expects($this->once())->method('getHeader')->with('USER_AGENT')->willReturn($userAgent);

        return $request;
    }
}
