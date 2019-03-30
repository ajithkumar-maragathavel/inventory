<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryInStorePickup\Plugin\InventoryApi\SourceRepository;

use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryInStorePickupApi\Api\Data\PickupLocationInterface;

class LoadInStorePickupOnGetPlugin
{
    /**
     * Enrich the given Source Objects with the In-Store pickup attribute
     *
     * @param SourceRepositoryInterface $subject
     * @param SourceInterface $source
     *
     * @return SourceInterface
     */
    public function afterGet(
        SourceRepositoryInterface $subject,
        SourceInterface $source
    ):SourceInterface {
        $pickupAvailable = $source->getData(PickupLocationInterface::IS_PICKUP_LOCATION_ACTIVE);

        $extensionAttributes = $source->getExtensionAttributes();
        $extensionAttributes->setIsPickupLocationActive((bool)$pickupAvailable);

        return $source;
    }
}
