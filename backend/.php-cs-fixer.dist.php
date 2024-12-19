<?php
$finder = PhpCsFixer\Finder::create();
$config = new Rshop\CS\Config\Rshop();

$config->setFinder($finder);

return $config;
