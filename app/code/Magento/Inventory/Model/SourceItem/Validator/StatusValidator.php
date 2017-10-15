<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Inventory\Model\SourceItem\Validator;

use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;
use Magento\Inventory\Model\OptionSource\SourceItemStatus;
use Magento\Inventory\Model\Source\Validator\SourceItemValidatorInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Check that status is valid
 */
class StatusValidator implements SourceItemValidatorInterface
{
    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * @var SourceItemStatus
     */
    private $sourceItemStatus;

    /**
     * @param ValidationResultFactory $validationResultFactory
     * @param SourceItemStatus $sourceItemStatus
     */
    public function __construct(
        ValidationResultFactory $validationResultFactory,
        SourceItemStatus $sourceItemStatus
    ) {
        $this->validationResultFactory = $validationResultFactory;
        $this->sourceItemStatus = $sourceItemStatus;
    }

    /**
     * @inheritdoc
     */
    public function validate(SourceItemInterface $source): ValidationResult
    {
        $value = (string)$source->getStatus();

        $allowedStatus = array_column($this->sourceItemStatus->toOptionArray(), 'value');

        $errors = [];
        if (!in_array($value, $allowedStatus)) {
            $errors[] = __(
                '"%field" should be of status ' . implode(', ', $allowedStatus),
                ['field' => SourceItemInterface::STATUS]
            );
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}
