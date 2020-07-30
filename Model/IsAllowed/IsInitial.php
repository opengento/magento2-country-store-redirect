<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Model\IsAllowed;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Opengento\CountryStore\Api\CountryRegistryInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowedInterface;

final class IsInitial implements IsAllowedInterface
{
    private DataPersistorInterface $dataPersistor;

    public function __construct(
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
    }

    public function isAllowed(RequestInterface $request): bool
    {
        return !$this->dataPersistor->get(CountryRegistryInterface::PARAM_KEY);
    }
}
