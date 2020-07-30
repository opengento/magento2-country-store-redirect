<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Model;

use Magento\Framework\App\RequestInterface;

/**
 * @api
 */
interface IsAllowedInterface
{
    public function isAllowed(RequestInterface $request): bool;
}
