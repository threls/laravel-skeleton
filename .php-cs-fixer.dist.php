<?php

use App\Fixer\ClassNotation\CustomControllerOrderFixer;
use App\Fixer\ClassNotation\CustomOrderedClassElementsFixer;
use App\Fixer\ClassNotation\CustomPhpUnitOrderFixer;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = (new Finder)
    ->in(__DIR__)
    ->exclude([
        'blueprints',
    ]);

return (new Config)
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setFinder($finder)
    ->setUsingCache(false)
    ->registerCustomFixers([
        new CustomControllerOrderFixer,
        new CustomOrderedClassElementsFixer,
        new CustomPhpUnitOrderFixer,
    ])
    ->setRules([
        'Tighten/custom_controller_order' => true,
        'Tighten/custom_ordered_class_elements' => true,
        'Tighten/custom_phpunit_order' => true,
    ]);
