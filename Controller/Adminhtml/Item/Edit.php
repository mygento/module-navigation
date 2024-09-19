<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Navigation\Api\Data\ItemInterfaceFactory;
use Mygento\Navigation\Api\ItemRepositoryInterface;
use Mygento\Navigation\Controller\Adminhtml\Item;

class Edit extends Item
{
    public function __construct(
        private readonly ItemInterfaceFactory $entityFactory,
        private readonly PageFactory $resultPageFactory,
        ItemRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Edit Item action
     */
    public function execute(): ResultInterface
    {
        $entityId = (int) $this->getRequest()->getParam('id');
        $entity = $this->entityFactory->create();
        if ($entityId) {
            try {
                $entity = $this->repository->getById($entityId);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(
                    __('This Item no longer exists')->render()
                );
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('navigation_item', $entity);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mygento_Navigation::item');
        $resultPage->addBreadcrumb(
            $entityId ? __('Edit Item')->render() : __('New Item')->render(),
            $entityId ? __('Edit Item')->render() : __('New Item')->render()
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Item')->render());
        $resultPage->getConfig()->getTitle()->prepend(
            $entityId ? $entity->getTitle() : __('New Item')->render()
        );

        return $resultPage;
    }
}
