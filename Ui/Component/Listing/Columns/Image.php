<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Ui\Component\Listing\Columns;

use Magento\Catalog\Helper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Image extends Column
{
    public function __construct(
        private StoreManagerInterface $storeManager,
        private Helper\Image $helper,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');
        foreach ($dataSource['data']['items'] as & $item) {
            if (!isset($item[$fieldName]) || !$item[$fieldName]) {
                $item[$fieldName . '_orig_src'] = $this->helper->getDefaultPlaceholderUrl('thumbnail');
                $item[$fieldName . '_src'] = $this->helper->getDefaultPlaceholderUrl('thumbnail');
                continue;
            }
            $item[$fieldName . '_orig_src'] = $this->getUrl($item[$fieldName]);
            $item[$fieldName . '_src'] = $this->getUrl($item[$fieldName]);
        }

        return $dataSource;
    }

    private function getUrl(string $path): string
    {
        /** @var Store $store */
        $store = $this->storeManager->getStore();

        return $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'mygentonav/item/' . $path;
    }
}
