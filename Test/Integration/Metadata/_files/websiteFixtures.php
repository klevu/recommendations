<?php
/** @noinspection PhpDeprecationInspection */
/** @noinspection PhpUnhandledExceptionInspection */

use Magento\Store\Model\Group;
use Magento\Store\Model\Store;
use Magento\Store\Model\Website;
use Magento\TestFramework\Helper\Bootstrap;

$websiteFixtures = [
    'klevu_test_website_1' => [
        'name' => '[Klevu] Test Website 1',
        'store_groups' => [
            'klevu_test_group_1' => [
                'name' => '[Klevu] Test Group 1',
                'stores' => [
                    'klevu_test_store_1' => [
                        'name' => '[Klevu] Test Store 1',
                    ],
                ],
            ],
        ],
    ],
    'klevu_test_website_2' => [
        'name' => '[Klevu] Test Website 2',
        'store_groups' => [
            'klevu_test_group_2' => [
                'name' => '[Klevu] Test Group 2',
                'stores' => [
                    'klevu_test_store_2' => [
                        'name' => '[Klevu] Test Store 2',
                    ],
                ],
            ],
        ],
    ],
];

$objectManager = Bootstrap::getObjectManager();

if (class_exists(\Magento\Elasticsearch\SearchAdapter\ConnectionManager::class)) {
    try {
        /** @var \Magento\Elasticsearch\SearchAdapter\ConnectionManager $connectionManager */
        $connectionManager = $objectManager->get(\Magento\Elasticsearch\SearchAdapter\ConnectionManager::class);
        $elasticsearchConnection = $connectionManager->getConnection([]);
        $elasticsearchConnection->deleteIndex('magento2_*');
    } catch (\RuntimeException $e) {
        if ('Elasticsearch client is not set.' !== $e->getMessage()) {
            throw $e;
        }
    }
}

foreach ($websiteFixtures as $websiteCode => $websiteFixture) {
    /** @var Website $website */
    $website = $objectManager->create(Website::class);
    $website->load($websiteCode, 'code');

    $websiteDefaultGroupIdSet = true;
    if (!$website->getId()) {
        $website->addData([
            'code' => $websiteCode,
            'name' => $websiteFixture['name'],
            'is_default' => 0,
        ]);
        $website->save();

        $websiteDefaultGroupIdSet = false;
    }

    foreach ($websiteFixture['store_groups'] as $storeGroupCode => $storeGroupFixture) {
        /** @var Group $storeGroup */
        $storeGroup = $objectManager->create(Group::class);
        $storeGroup->load($storeGroupCode, 'code');

        if (!$storeGroup->getId()) {
            $storeGroup->setCode($storeGroupCode);
            $storeGroup->setName($storeGroupFixture['name']);
            $storeGroup->setWebsite($website);
            $storeGroup->save();
        }

        if (!$websiteDefaultGroupIdSet) {
            $website->setDefaultGroupId($storeGroup->getId());
            $website->save();

            $websiteDefaultGroupIdSet = true;
        }

        $storeSortOrder = 0;
        foreach ($storeGroupFixture['stores'] as $storeCode => $storeFixture) {
            /** @var Store $store */
            $store = $objectManager->create(Store::class);
            $store->load($storeCode, 'code');

            if (!$store->getId()) {
                $store->addData([
                    'code' => $storeCode,
                    'website_id' => $website->getId(),
                    'group_id' => $storeGroup->getId(),
                    'name' => $storeFixture['name'],
                    'sort_order' => ($storeSortOrder += 10),
                    'is_active' => 1,
                ]);
                $store->save();
            }
        }
    }
}
