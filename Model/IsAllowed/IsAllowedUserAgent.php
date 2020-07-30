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
use Opengento\CountryStoreRedirect\Model\IsAllowedInterface;
use function array_filter;
use function array_map;
use function explode;
use function in_array;
use function strtolower;
use function trim;

final class IsAllowedUserAgent implements IsAllowedInterface
{
    private const CONFIG_PATH_IGNORE_USER_AGENT_LIST = 'country/redirect/ignore_user_agents';

    private ScopeConfigInterface $scopeConfig;

    /**
     * @var string[]
     */
    private array $userAgents;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isAllowed(RequestInterface $request): bool
    {
        return $request instanceof Http &&
            !in_array(
                strtolower((string) $request->getHeader('USER_AGENT')),
                $this->resolveIgnoreUserAgentList(),
                true
            );
    }

    private function resolveIgnoreUserAgentList(): array
    {
        return $this->userAgents ?? $this->userAgents = array_filter(array_map(
            static function (string $userAgent): string {
                return strtolower(trim($userAgent));
            },
            explode(',', (string) $this->scopeConfig->getValue(self::CONFIG_PATH_IGNORE_USER_AGENT_LIST))
        ));
    }
}
