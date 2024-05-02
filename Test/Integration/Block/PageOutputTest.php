<?php

namespace Klevu\Recommendations\Test\Integration\Block;

use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\AbstractController as AbstractControllerTestCase;

class PageOutputTest extends AbstractControllerTestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     * @magentoConfigFixture default/klevu_search/recommendations/enabled 1
     * @magentoConfigFixture default_store klevu_search/recommendations/enabled 1
     * @magentoConfigFixture default/klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default_store klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default/klevu_search/developer/theme_version v1
     * @magentoConfigFixture default_store klevu_search/developer/theme_version v1
     * @noinspection PhpParamsInspection
     */
    public function testRecsJsIncludesAreOutputToPageWhenEnabled()
    {
        $this->setupPhp5();

        $this->dispatch('/');

        $response = $this->getResponse();
        $responseBody = $response->getBody();
        $this->assertSame(200, $response->getHttpResponseCode());

        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is present in response body'
            );
        } else {
            $this->assertContains(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is present in response body'
            );
        }
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     * @magentoConfigFixture default/klevu_search/recommendations/enabled 0
     * @magentoConfigFixture default_store klevu_search/recommendations/enabled 0
     * @magentoConfigFixture default/klevu_frontendjs/configuration/enabled 0
     * @magentoConfigFixture default_store klevu_frontendjs/configuration/enabled 0
     * @magentoConfigFixture default/klevu_search/developer/theme_version v1
     * @magentoConfigFixture default_store klevu_search/developer/theme_version v1
     * @depends testRecsJsIncludesAreOutputToPageWhenEnabled
     * @noinspection PhpParamsInspection
     */
    public function testRecsJsIncludesAreNotOutputToPageWhenDisabled()
    {
        $this->setupPhp5();

        $this->dispatch('/');

        $response = $this->getResponse();
        $responseBody = $response->getBody();
        $this->assertSame(200, $response->getHttpResponseCode());

        if (method_exists($this, 'assertStringNotContainsString')) {
            $this->assertStringNotContainsString(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is NOT present in response body'
            );
        } else {
            $this->assertNotContains(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is NOT present in response body'
            );
        }
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     * @magentoConfigFixture default/klevu_search/recommendations/enabled 1
     * @magentoConfigFixture default_store klevu_search/recommendations/enabled 1
     * @magentoConfigFixture default/klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default_store klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default/klevu_search/general/js_api_key klevu-1234567890
     * @magentoConfigFixture default_store klevu_search/general/js_api_key klevu-1234567890
     * @magentoConfigFixture default/klevu_search/developer/theme_version v1
     * @magentoConfigFixture default_store klevu_search/developer/theme_version v1
     * @noinspection PhpParamsInspection
     */
    public function testRecsJsIncludesAreOutputToPageWhenRecsAndAPIKeyGiven()
    {
        $this->setupPhp5();

        $this->dispatch('/');

        $response = $this->getResponse();
        $responseBody = $response->getBody();
        $this->assertSame(200, $response->getHttpResponseCode());

        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is present in response body'
            );
            $this->assertStringContainsString(
                '"recsModule":true',
                $responseBody,
                'RECs Initialisation script is present in response body'
            );
            $this->assertStringContainsString(
                '"recs":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is present for recs in response body'
            );
            $this->assertStringContainsString(
                '"analytics":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is present for analytics in response body'
            );
        } else {
            $this->assertContains(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;/klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is present in response body'
            );
            $this->assertContains(
                '"recs":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is present for recs in response body'
            );
            $this->assertContains(
                '"analytics":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is present for analytics in response body'
            );
        }
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     * @magentoConfigFixture default/klevu_frontendjs/configuration/enabled 0
     * @magentoConfigFixture default_store klevu_frontendjs/configuration/enabled 0
     * @magentoConfigFixture default/klevu_search/recommendations/enabled 0
     * @magentoConfigFixture default_store klevu_search/recommendations/enabled 0
     * @magentoConfigFixture default/klevu_search/general/js_api_key klevu-1234567890
     * @magentoConfigFixture default_store klevu_search/general/js_api_key klevu-1234567890
     * @magentoConfigFixture default/klevu_search/developer/theme_version v1
     * @magentoConfigFixture default_store klevu_search/developer/theme_version v1
     * @depends testRecsJsIncludesAreOutputToPageWhenRecsAndAPIKeyGiven
     * @noinspection PhpParamsInspection
     */
    /*
    * TODO:: module/frontend-js needs to consider flag while rendering through ifconfig in separate argument
    *
    */
    public function testRecsJsIncludesAreNotOutputToPageWhenRecsDisabledAndAPIKeyGiven()
    {
        $this->setupPhp5();

        $this->dispatch('/');

        $response = $this->getResponse();
        $responseBody = $response->getBody();
        $this->assertSame(200, $response->getHttpResponseCode());

        if (method_exists($this, 'assertStringNotContainsString')) {
            $this->assertStringNotContainsString(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is NOT present in response body'
            );
            $this->assertStringNotContainsString(
                '"recsModule":true',
                $responseBody,
                'RECs Initialisation script is NOT present in response body'
            );
            $this->assertStringNotContainsString(
                '"recs":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is NOT present for recs in response body'
            );
            $this->assertStringNotContainsString(
                '"analytics":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is NOT present for analytics in response body'
            );
        } else {
            $this->assertNotContains(
                '<script type="text&#x2F;javascript" src="https&#x3A;&#x2F;&#x2F;js.klevu.com&#x2F;recs&#x2F;v2&#x2F;klevu-recs.js"></script>', // phpcs:ignore Generic.Files.LineLength.TooLong
                $responseBody,
                'RECs Library JS include is NOT present in response body'
            );
            $this->assertNotContains(
                '"recsModule":true',
                $responseBody,
                'RECs Initialisation script is NOT present in response body'
            );
            $this->assertNotContains(
                '"recs":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is NOT present for recs in response body'
            );
            $this->assertNotContains(
                '"analytics":{"apiKey":"klevu-1234567890"}',
                $responseBody,
                'JSApi key available script is NOT present for analytics in response body'
            );
        }
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     * @magentoConfigFixture default/klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default_store klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default/klevu_search/recommendations/enabled 1
     * @magentoConfigFixture default_store klevu_search/recommendations/enabled 1
     * @magentoConfigFixture default_store klevu_search/general/js_api_key
     * @magentoConfigFixture default/klevu_search/developer/theme_version v1
     * @magentoConfigFixture default_store klevu_search/developer/theme_version v1
     * @depends testRecsJsIncludesAreOutputToPageWhenRecsAndAPIKeyGiven
     * @noinspection PhpParamsInspection
     */
    public function testRecsJsInitOptionsAreNotOutputToPageWhenRecsEnabledAndAPIKeyNotGiven()
    {
        $this->setupPhp5();

        $this->dispatch('/');

        $response = $this->getResponse();
        $responseBody = $response->getBody();
        $this->assertSame(200, $response->getHttpResponseCode());

        if (method_exists($this, 'assertStringNotContainsString')) {
            $this->assertStringNotContainsString(
                '"recsModule":true',
                $responseBody,
                'RECs Initialisation script is NOT present in response body'
            );
            $this->assertStringNotContainsString(
                '"recs":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for recs in response body'
            );
            $this->assertStringNotContainsString(
                '"analytics":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for analytics in response body'
            );
        } else {
            $this->assertNotContains(
                '"recsModule":true',
                $responseBody,
                'RECs Initialisation script is NOT present in response body'
            );
            $this->assertNotContains(
                '"recs":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for recs in response body'
            );
            $this->assertNotContains(
                '"analytics":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for analytics in response body'
            );
        }
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     * @magentoConfigFixture default/klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default_store klevu_frontendjs/configuration/enabled 1
     * @magentoConfigFixture default/klevu_search/recommendations/enabled 0
     * @magentoConfigFixture default_store klevu_search/recommendations/enabled 0
     * @magentoConfigFixture default/klevu_search/developer/theme_version v1
     * @magentoConfigFixture default_store klevu_search/developer/theme_version v1
     * @depends testRecsJsIncludesAreOutputToPageWhenRecsAndAPIKeyGiven
     * @noinspection PhpParamsInspection
     */
    public function testRecsJsInitOptionsAreNotOutputToPageWhenRecsDisabledAndAPIKeyNotGiven()
    {
        $this->setupPhp5();

        $this->dispatch('/');

        $response = $this->getResponse();
        $responseBody = $response->getBody();
        $this->assertSame(200, $response->getHttpResponseCode());

        if (method_exists($this, 'assertStringNotContainsString')) {
            $this->assertStringNotContainsString(
                '"recsModule":true',
                $responseBody,
                'RECs Initialisation script is NOT present in response body'
            );
            $this->assertStringNotContainsString(
                '"recs":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for recs in response body'
            );
            $this->assertStringNotContainsString(
                '"analytics":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for analytics in response body'
            );
        } else {
            $this->assertNotContains(
                '"recsModule":true',
                $responseBody,
                'RECs Initialisation script is NOT present in response body'
            );
            $this->assertNotContains(
                '"recs":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for recs in response body'
            );
            $this->assertNotContains(
                '"analytics":{"apiKey":',
                $responseBody,
                'JSApi key available script is NOT present for analytics in response body'
            );
        }
    }

    /**
     * @return void
     * @todo Move to setUp when PHP 5.x is no longer supported
     */
    private function setupPhp5()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }
}
