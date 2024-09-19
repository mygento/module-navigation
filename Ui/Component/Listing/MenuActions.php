<?php

/**
 * @author Mygento Team
 * @copyright 2023 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Ui\Component\Listing;

use Mygento\Base\Ui\Component\Listing\Actions;

class MenuActions extends Actions
{
    /** @var string */
    protected $route = 'navigation';

    /** @var string */
    protected $controller = 'menu';

    /** @var string */
    protected $key = 'entity_id';
}
