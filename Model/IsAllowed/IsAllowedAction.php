<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Model\IsAllowed;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowedInterface;
use function array_filter;
use function array_map;
use function explode;
use function in_array;
use function strtolower;
use function trim;

final class IsAllowedAction implements IsAllowedInterface
{
    private const CONFIG_PATH_IGNORE_ACTION_LIST = 'country/redirect/ignore_actions';

    private ScopeConfigInterface $scopeConfig;

    /**
     * @var string[]
     */
    private array $actionList;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isAllowed(RequestInterface $request): bool
    {
        return $request instanceof Http &&
            !in_array(strtolower($request->getFullActionName('/')), $this->resolveIgnoreActionList(), true);
    }

    private function resolveIgnoreActionList(): array
    {
        return $this->actionList ?? $this->actionList = array_filter(array_map(
            static function (string $fullAction): string {
                return strtolower(trim($fullAction));
            },
            explode(
                ',',
                (string) $this->scopeConfig->getValue(self::CONFIG_PATH_IGNORE_ACTION_LIST, ScopeInterface::SCOPE_STORE)
            )
        ));
    }
}
