<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Model\Source;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Mygento\Navigation\Api\MenuRepositoryInterface;

class Menu implements OptionSourceInterface
{
    public function __construct(
        private MenuRepositoryInterface $repo,
        private SearchCriteriaBuilder $builder,
    ) {
    }
    
    public function toOptionArray(): array 
    {
        $result = [];
        
        $data = $this->repo->getList($this->builder->create())->getItems();
        foreach($data as $i) {
            $result[] = ['value' => $i->getEntityId(), 'label' => $i->getCode()];
        }
        
        return $result;
    }
}