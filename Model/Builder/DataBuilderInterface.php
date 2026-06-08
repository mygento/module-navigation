<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

declare(strict_types=1);

namespace Mygento\Navigation\Model\Builder;

interface DataBuilderInterface
{
    public function addData(array $items, array $entityIds, int $storeId): array;
}
