<?php

namespace Klevu\Recommendations\Service;

use Klevu\FrontendJs\Api\IsEnabledConditionInterface;
use Klevu\Recommendations\Constants;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class IsEnabledCondition implements IsEnabledConditionInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * IsEnabledCondition constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function execute($storeId = null)
    {
        $apiKey = trim((string)$this->scopeConfig->getValue(
            Constants::XML_PATH_RECS_JS_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
        $isEnabled = $this->scopeConfig->isSetFlag(
            Constants::XML_PATH_RECS_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $apiKey && $isEnabled;
    }
}
