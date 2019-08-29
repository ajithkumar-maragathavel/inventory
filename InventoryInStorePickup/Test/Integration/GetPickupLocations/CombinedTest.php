<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryInStorePickup\Test\Integration\GetPickupLocations;

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\InventoryInStorePickup\Model\GetPickupLocations;
use Magento\InventoryInStorePickupApi\Api\Data\SearchRequest\DistanceFilterInterface;
use Magento\InventoryInStorePickupApi\Api\Data\SearchResultInterface;
use Magento\InventoryInStorePickupApi\Model\SearchRequestBuilderInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests coverage for @see \Magento\InventoryInStorePickup\Model\GetPickupLocations.
 *
 * Cover usage of Combined Filters.
 */
class CombinedTest extends TestCase
{
    /**
     * @var GetPickupLocations
     */
    private $getPickupLocations;

    /**
     * @var SearchRequestBuilderInterface
     */
    private $searchRequestBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    protected function setUp()
    {
        $this->getPickupLocations = Bootstrap::getObjectManager()->get(GetPickupLocations::class);
        $this->searchRequestBuilder = Bootstrap::getObjectManager()->get(SearchRequestBuilderInterface::class);
        $this->sortOrderBuilder = Bootstrap::getObjectManager()->get(SortOrderBuilder::class);
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_addresses.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_pickup_location_attributes.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites_with_stores.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/stock_website_sales_channels.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/inventory_geoname.php
     *
     * @magentoConfigFixture default/cataloginventory/source_selection_distance_based/provider offline
     *
     * @magentoAppArea frontend
     *
     * @magentoDbIsolation disabled
     */
    public function testExecuteDistanceFilterWithAddressFilter()
    {
        $searchRequest = $this->searchRequestBuilder->setScopeCode('global_website')
                                                    ->setScopeType(SalesChannelInterface::TYPE_WEBSITE)
                                                    ->setDistanceFilterRadius(750)
                                                    ->setDistanceFilterPostcode('86559')
                                                    ->setDistanceFilterCountry('DE')
                                                    ->setAddressCityFilter('Kolbermoor,Mitry-Mory', 'in')
                                                    ->setAddressRegionIdFilter('259')
                                                    ->setAddressRegionFilter('Seine-et-Marne')
                                                    ->create();

        /** @var SearchResultInterface $result */
        $result = $this->getPickupLocations->execute($searchRequest);

        $this->assertCount(1, $result->getItems());
        $this->assertEquals(1, $result->getTotalCount());
        $this->assertEquals('eu-1', current($result->getItems())->getPickupLocationCode());
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_addresses.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_pickup_location_attributes.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites_with_stores.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/stock_website_sales_channels.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/inventory_geoname.php
     *
     * @magentoConfigFixture default/cataloginventory/source_selection_distance_based/provider offline
     *
     * @magentoAppArea frontend
     *
     * @magentoDbIsolation disabled
     */
    public function testExecuteDistanceFilterWithGeneralFilters()
    {
        $searchRequest = $this->searchRequestBuilder->setScopeCode('global_website')
                                                    ->setScopeType(SalesChannelInterface::TYPE_WEBSITE)
                                                    ->setDistanceFilterRadius(750)
                                                    ->setDistanceFilterPostcode('86559')
                                                    ->setDistanceFilterCountry('DE')
                                                    ->setNameFilter('source', 'fulltext')
                                                    ->setPickupLocationCodeFilter('eu%', 'like')
                                                    ->setCurrentPage(2)
                                                    ->setPageSize(1)
                                                    ->create();

        /** @var SearchResultInterface $result */
        $result = $this->getPickupLocations->execute($searchRequest);

        $this->assertCount(1, $result->getItems());
        $this->assertEquals(2, $result->getTotalCount());
        $this->assertEquals('eu-3', current($result->getItems())->getPickupLocationCode());
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_addresses.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_pickup_location_attributes.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites_with_stores.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/stock_website_sales_channels.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/inventory_geoname.php
     *
     * @magentoConfigFixture default/cataloginventory/source_selection_distance_based/provider offline
     *
     * @magentoAppArea frontend
     *
     * @magentoDbIsolation disabled
     */
    public function testExecuteWithAllFilters()
    {
        $sort = $this->sortOrderBuilder->setField(DistanceFilterInterface::DISTANCE_FIELD)
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $searchRequest = $this->searchRequestBuilder->setScopeCode('global_website')
                                                    ->setScopeType(SalesChannelInterface::TYPE_WEBSITE)
                                                    ->setDistanceFilterRadius(6371000)
                                                    ->setDistanceFilterPostcode('86559')
                                                    ->setDistanceFilterCountry('DE')
                                                    ->setNameFilter('source', 'fulltext')
                                                    ->setAddressCityFilter(
                                                        'Kolbermoor,Mitry-Mory,Burlingame',
                                                        'in'
                                                    )->setAddressCountryFilter('DE', 'neq')
                                                    ->setPageSize(2)
                                                    ->setCurrentPage(2)
                                                    ->setSortOrders([$sort])
                                                    ->create();

        /** @var SearchResultInterface $result */
        $result = $this->getPickupLocations->execute($searchRequest);

        $this->assertCount(2, $result->getItems());
        $this->assertEquals(2, $result->getTotalCount());
        $items = $result->getItems();
        $this->assertEquals('us-1', current($items)->getPickupLocationCode());
        $this->assertEquals('eu-1', next($items)->getPickupLocationCode());
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_addresses.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/source_pickup_location_attributes.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites_with_stores.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/stock_website_sales_channels.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryInStorePickup/Test/_files/inventory_geoname.php
     *
     * @magentoConfigFixture default/cataloginventory/source_selection_distance_based/provider offline
     *
     * @magentoAppArea frontend
     *
     * @magentoDbIsolation disabled
     */
    public function testExecuteDistanceFilterWithPaging()
    {
        $searchRequest = $this->searchRequestBuilder->setDistanceFilterRadius(750)
                                                    ->setDistanceFilterCountry('DE')
                                                    ->setDistanceFilterPostcode('86559')
                                                    ->setScopeCode('global_website')
                                                    ->setPageSize(1)
                                                    ->setCurrentPage(1)
                                                    ->create();

        /** @var SearchResultInterface $result */
        $result = $this->getPickupLocations->execute($searchRequest);

        $this->assertCount(1, $result->getItems());
        $this->assertEquals(2, $result->getTotalCount());
        $this->assertEquals('eu-1', current($result->getItems())->getPickupLocationCode());
    }
}
