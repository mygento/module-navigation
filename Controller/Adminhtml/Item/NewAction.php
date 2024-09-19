<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Mygento\Navigation\Api\ItemRepositoryInterface;
use Mygento\Navigation\Controller\Adminhtml\Item;

class NewAction extends Item
{
    public function __construct(
        private readonly ForwardFactory $resultForwardFactory,
        ItemRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Create new Item
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();

        return $resultForward->forward('edit');
    }
}
