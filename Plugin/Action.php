<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\CountryStoreRedirect\Plugin;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url\EncoderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Opengento\CountryStore\Api\CountryRegistryInterface;
use Opengento\CountryStore\Api\CountryStoreResolverInterface;
use Opengento\CountryStoreRedirect\Model\IsAllowedInterface;
use Psr\Log\LoggerInterface;

final class Action
{
    private CountryRegistryInterface $countryRegistry;

    private CountryStoreResolverInterface $countryStoreResolver;

    private RedirectFactory $redirectFactory;

    private IsAllowedInterface $isAllowed;

    private StoreManagerInterface $storeManager;

    private EncoderInterface $encoder;

    private LoggerInterface $logger;

    public function __construct(
        CountryRegistryInterface $countryRegistry,
        CountryStoreResolverInterface $countryStoreResolver,
        RedirectFactory $redirectFactory,
        IsAllowedInterface $isAllowed,
        StoreManagerInterface $storeManager,
        EncoderInterface $encoder,
        LoggerInterface $logger
    ) {
        $this->countryRegistry = $countryRegistry;
        $this->countryStoreResolver = $countryStoreResolver;
        $this->redirectFactory = $redirectFactory;
        $this->isAllowed = $isAllowed;
        $this->storeManager = $storeManager;
        $this->encoder = $encoder;
        $this->logger = $logger;
    }

    /**
     * @throws NoSuchEntityException
     */
    public function aroundDispatch(FrontControllerInterface $subject, callable $proceed, RequestInterface $request)
    {
        if ($this->isAllowed->isAllowed($request)) {
            try {
                $store = $this->countryStoreResolver->getStoreAware($this->countryRegistry->get());
                $currentStore = $this->storeManager->getStore();
            } catch (NoSuchEntityException $e) {
                $this->logger->error($e->getLogMessage(), $e->getTrace());

                return $proceed($request);
            }

            if ($store->getCode() !== $currentStore->getCode()) {
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->redirectFactory->create();
                $resultRedirect->setHeader('Cache-Control', 'no-cache');

                return $resultRedirect->setPath(
                    'stores/store/redirect',
                    [
                        '___store' => $store->getCode(),
                        '___from_store' => $currentStore->getCode(),
                        ActionInterface::PARAM_NAME_URL_ENCODED => $this->encoder->encode($store->getCurrentUrl(false)),
                    ]
                );
            }
        }

        return $proceed($request);
    }
}
