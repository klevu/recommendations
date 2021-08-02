<?php

namespace Klevu\Recommendations\Service;

use Klevu\FrontendJs\Api\InteractiveOptionsProviderInterface;
use Klevu\FrontendJs\Api\IsEnabledConditionInterface;
use Klevu\Recommendations\Constants as Klevu_RecsConstants;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class RecsInteractiveOptionsProvider
 * @package Klevu\Recommendations\Service
 */
class RecsInteractiveOptionsProvider implements InteractiveOptionsProviderInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var IsEnabledConditionInterface
     */
    private $isEnabledCondition;

    /**
     * RecsInteractiveOptionsProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param IsEnabledConditionInterface $isEnabledCondition
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        IsEnabledConditionInterface $isEnabledCondition
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->isEnabledCondition = $isEnabledCondition;
    }

    /**
     * @param null $storeId
     * @return array
     */
    public function execute($storeId = null)
    {
        if (!$this->isEnabledCondition->execute($storeId)) {
            return [];
        }

        $apiKey = $this->scopeConfig->getValue(
            Klevu_RecsConstants::XML_PATH_RECS_JS_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return [
            'powerUp' => [
                'recsModule' => true
            ],
            'recs' => [
                'apiKey' => $apiKey,
            ],
            'analytics' => [
                'apiKey' => $apiKey,
            ],
        ];
    }
}
