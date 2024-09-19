<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Controller\Adminhtml\Menu;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Mygento\Navigation\Api\Data\MenuInterfaceFactory;
use Mygento\Navigation\Api\MenuRepositoryInterface;
use Mygento\Navigation\Controller\Adminhtml\Menu;

class Save extends Menu
{
    public function __construct(
        private readonly DataPersistorInterface $dataPersistor,
        private readonly MenuInterfaceFactory $entityFactory,
        MenuRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Save Menu action
     *
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }
        $entityId = (int) $this->getRequest()->getParam('id');
        $entity = $this->entityFactory->create();
        if ($entityId) {
            try {
                $entity = $this->repository->getById($entityId);
            } catch (NoSuchEntityException $e) {
                if (!$entity->getId()) {
                    $this->messageManager->addErrorMessage(
                        __('This Menu no longer exists')->render()
                    );

                    return $resultRedirect->setPath('*/*/');
                }
            }
        }
        if (empty($data['entity_id'])) {
            $data['entity_id'] = null;
        }
        $entity->setData($data);

        try {
            $this->repository->save($entity);
            $this->messageManager->addSuccessMessage(
                __('You saved the Menu')->render()
            );
            $this->dataPersistor->clear('navigation_menu');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $entity->getId()]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the Menu')->render()
            );
        }
        $this->dataPersistor->set('navigation_menu', $data);

        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
    }
}
