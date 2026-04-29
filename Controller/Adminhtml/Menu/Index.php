<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Controller\Adminhtml\Menu;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Navigation\Api\MenuRepositoryInterface;
use Mygento\Navigation\Controller\Adminhtml\Menu;

class Index extends Menu
{
    public function __construct(
        private readonly PageFactory $resultPageFactory,
        private readonly DataPersistorInterface $dataPersistor,
        MenuRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Index action
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Mygento_Navigation::menu')
            ->getConfig()
            ->getTitle()->prepend(__('Menu')->render());

        $this->dataPersistor->clear('navigation_menu');

        return $resultPage;
    }
}
