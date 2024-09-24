<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Ui\Component\Listing;

class ItemStatus extends \Magento\Ui\Component\Listing\Columns\Column
{
    private const SOURCE_FIELD_NAME = 'is_active';

    public function prepareDataSource(array $dataSource): array
    {
        $dataSource = parent::prepareDataSource($dataSource);

        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (isset($item[self::SOURCE_FIELD_NAME])) {
                $item[$fieldName] = $this->getOptionText($item[self::SOURCE_FIELD_NAME]);
            }
        }

        return $dataSource;
    }

    /**
     * Returns option text by option value
     *
     * @param int $item
     * @return string|null
     */
    private function getOptionText($item)
    {
        $statusesArray = [
            1 => __('Active'),
            2 => __('Inactive')
        ];
        return isset($statusesArray[$item]) ? $statusesArray[$item]: null;
    }
}
