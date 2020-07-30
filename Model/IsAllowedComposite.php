<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Model;

use Magento\Framework\App\RequestInterface;
use function array_values;

final class IsAllowedComposite implements IsAllowedInterface
{
    /**
     * @var IsAllowedInterface[]
     */
    private array $isAllowedList;

    public function __construct(
        array $isAllowedList = []
    ) {
        $this->isAllowedList = (static function (IsAllowedInterface ...$isAllowedList): array {
            return $isAllowedList;
        })(...array_values($isAllowedList));
    }

    public function isAllowed(RequestInterface $request): bool
    {
        foreach ($this->isAllowedList as $isAllowed) {
            if (!$isAllowed->isAllowed($request)) {
                return false;
            }
        }

        return true;
    }
}
