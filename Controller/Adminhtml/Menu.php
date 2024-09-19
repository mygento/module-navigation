<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Mygento\Navigation\Api\MenuRepositoryInterface;

abstract class Menu extends Action
{
    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Mygento_Navigation::menu';

    public function __construct(
        protected readonly MenuRepositoryInterface $repository,
        protected readonly Registry $coreRegistry,
        Action\Context $context,
    ) {
        parent::__construct($context);
    }
}
