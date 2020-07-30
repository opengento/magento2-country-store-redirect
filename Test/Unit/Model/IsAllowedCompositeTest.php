<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Test\Unit\Model\Redirect;

use Magento\Framework\App\RequestInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowedComposite;
use Opengento\CountryStoreRedirect\Model\IsAllowedInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Opengento\CountryStoreRedirect\Model\IsAllowedComposite
 */
class IsAllowedCompositeTest extends TestCase
{
    /**
     * @dataProvider isAllowedData
     */
    public function testIsAllowed(RequestInterface $request, array $isAllowedList, bool $isAllowed): void
    {
        /** @var IsAllowedComposite $isAllowedComposite */
        $isAllowedComposite = new IsAllowedComposite($isAllowedList);

        $this->assertSame($isAllowed, $isAllowedComposite->isAllowed($request));
    }

    public function isAllowedData(): array
    {
        return [
            [
                $this->createRequestMock(),
                [
                    $this->createIsAllowedMock(true),
                    $this->createIsAllowedMock(true),
                ],
                true
            ],
            [
                $this->createRequestMock(),
                [
                    $this->createIsAllowedMock(true),
                    $this->createIsAllowedMock(false),
                ],
                false
            ],
            [
                $this->createRequestMock(),
                [
                    $this->createIsAllowedMock(false),
                    $this->createIsAllowedMock(false),
                ],
                false
            ],
            [
                $this->createRequestMock(),
                [],
                true
            ],
            [
                $this->createRequestMock(),
                [
                    $this->createIsAllowedMock(true),
                ],
                true
            ],
            [
                $this->createRequestMock(),
                [
                    $this->createIsAllowedMock(false),
                ],
                false
            ],
        ];
    }

    private function createIsAllowedMock(bool $isAllowed): MockObject
    {
        $isAllowedMock = $this->getMockForAbstractClass(IsAllowedInterface::class);
        $isAllowedMock->expects($this->atMost(1))->method('isAllowed')->willReturn($isAllowed);

        return $isAllowedMock;
    }

    private function createRequestMock(): MockObject
    {
        return $this->getMockForAbstractClass(RequestInterface::class);
    }
}
