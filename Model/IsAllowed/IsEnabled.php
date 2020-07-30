<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Model\IsAllowed;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowedInterface;

final class IsEnabled implements IsAllowedInterface
{
    private const CONFIG_PATH_COUNTRY_REDIRECT_INITIAL = 'country/redirect/enabled';

    private ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isAllowed(RequestInterface $request): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_COUNTRY_REDIRECT_INITIAL, ScopeInterface::SCOPE_STORE);
    }
}
