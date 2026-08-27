<?php

/**
 * @author Mygento Team
 * @copyright 2023-2026 Mygento (https://www.mygento.com)
 * @package Mygento_Navigation
 */

namespace Mygento\Navigation\Api;

interface EntityResolverInterface
{
    /**
     * @param string[] $ids
     * @return array<string, string>
     */
    public function resolve(array $ids): array;
}
