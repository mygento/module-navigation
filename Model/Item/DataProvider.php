<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\Item;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Mygento\Navigation\Model\EntityLabelResolver;
use Mygento\Navigation\Model\FileInfo;
use Mygento\Navigation\Model\ResourceModel\Item\Collection;
use Mygento\Navigation\Model\ResourceModel\Item\CollectionFactory;

class DataProvider extends ModifierPoolDataProvider
{
    /** @var Collection */
    protected $collection;

    private array $loadedData = [];

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private FileInfo $fileInfo,
        private DataPersistorInterface $dataPersistor,
        private EntityLabelResolver $labelResolver,
        CollectionFactory $collectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = [],
        ?PoolInterface $pool = null,
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);

        $this->collection = $collectionFactory->create();
    }

    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $data = $model->getData();
            $data['image'] = $this->getImageData($data, 'image');
            $data['entity_label'] = $this->labelResolver->resolve(
                $data['entity_type'],
                $data['entity_identifier'],
            );
            $this->loadedData[$model->getId()] = $data;
        }
        $data = $this->dataPersistor->get('navigation_item');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('navigation_item');
        }

        return $this->loadedData;
    }

    private function getImageData(array $data, string $key): ?array
    {
        $imageFileName = $data[$key] ?? null;
        if (!$imageFileName) {
            return null;
        }
        if (is_array($imageFileName) && isset($imageFileName[0]['name']) && $imageFileName[0]['name']) {
            $imageFileName = $imageFileName[0]['name'];
        }
        if (is_array($imageFileName)) {
            return null;
        }

        $imageFilePath = 'mygentonav/item/' . $imageFileName;
        $result = null;
        if (!$this->fileInfo->isExist($imageFilePath)) {
            return $result;
        }

        $stat = $this->fileInfo->getStat($imageFilePath);
        $mime = $this->fileInfo->getMimeType($imageFilePath);

        return [
            [
                'name' => $imageFileName,
                'url' => $this->fileInfo->getUrl($imageFilePath),
                'size' => $stat['size'],
                'type' => $mime,
            ],
        ];
    }
}
