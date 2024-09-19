<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Controller\Adminhtml\Menu;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Navigation\Api\Data\MenuInterfaceFactory;
use Mygento\Navigation\Api\MenuRepositoryInterface;
use Mygento\Navigation\Controller\Adminhtml\Menu;

class Edit extends Menu
{
    public function __construct(
        private readonly MenuInterfaceFactory $entityFactory,
        private readonly PageFactory $resultPageFactory,
        MenuRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Edit Menu action
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
                    __('This Menu no longer exists')->render()
                );
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('navigation_menu', $entity);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mygento_Navigation::menu');
        $resultPage->addBreadcrumb(
            $entityId ? __('Edit Menu')->render() : __('New Menu')->render(),
            $entityId ? __('Edit Menu')->render() : __('New Menu')->render()
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Menu')->render());
        $resultPage->getConfig()->getTitle()->prepend(
            $entityId ? $entity->getTitle() : __('New Menu')->render()
        );

        return $resultPage;
    }
}
