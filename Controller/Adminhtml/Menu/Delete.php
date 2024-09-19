<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Controller\Adminhtml\Menu;

use Magento\Framework\Controller\ResultInterface;
use Mygento\Navigation\Controller\Adminhtml\Menu;

class Delete extends Menu
{
    /**
     * Delete Menu action
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $entityId = (int) $this->getRequest()->getParam('id');
        if (!$entityId) {
            $this->messageManager->addErrorMessage(
                __('We can not find a Menu to delete.')->render()
            );

            return $resultRedirect->setPath('*/*/');
        }

        try {
            $this->repository->deleteById($entityId);
            $this->messageManager->addSuccessMessage(
                __('You deleted the Menu')->render()
            );

            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/edit', ['id' => $entityId]);
    }
}
