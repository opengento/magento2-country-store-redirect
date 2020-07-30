<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Model\IsAllowed;

use Magento\Framework\App\HttpRequestInterface;
use Magento\Framework\App\RequestInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowedInterface;

final class IsHttp implements IsAllowedInterface
{
    public function isAllowed(RequestInterface $request): bool
    {
        return $request instanceof HttpRequestInterface;
    }
}
