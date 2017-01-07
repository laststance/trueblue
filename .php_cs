#!/usr/bin/env php
<?php

$finder = Symfony\CS\Finder::create()
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->exclude('app')
    ->exclude('vendor')
    ->exclude('web')
    ->in(__DIR__)
;

return Symfony\CS\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        '-psr0', // Ignore Tests\ namespace prefix mismatch with tests/ directory
        'ordered_use',
        'phpdoc_order',
        'short_array_syntax',
    ])
    ->finder($finder)
    ;
