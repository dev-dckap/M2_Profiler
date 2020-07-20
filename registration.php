<?php
/**
 * @author     DCKAP
 * @package    DCKAP_Profiler
 * @copyright  Copyright (c) 2017 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

if (PHP_SAPI != 'cli') {
    $_SERVER['PROFILER'] = new \Magento\Framework\Profiler\Driver\Standard\Stat();
    \Magento\Framework\Profiler::applyConfig(
        [
            'drivers' => [
                [
                    'stat'   => $_SERVER['PROFILER'],
                    'output' => 'DCKAP\Profiler\Driver\Standard\Output\Html',
                ]
            ]
        ],
        BP,
        false
    );
}
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'DCKAP_Profiler',
    __DIR__
);
