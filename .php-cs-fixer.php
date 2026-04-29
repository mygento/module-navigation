<?php
$header = <<<EOF
@author Mygento Team
@copyright 2023-2026 Mygento (https://www.mygento.com)
@package Mygento_Navigation
EOF;

$finder = PhpCsFixer\Finder::create()
    ->in('.')
    ->ignoreVCSIgnored(true);

$config = new \Mygento\CS\Config\Module($header);
$config->setFinder($finder);
return $config;
