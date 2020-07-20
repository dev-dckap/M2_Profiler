<?php
/**
 * @author     DCKAP
 * @package    DCKAP_Profiler
 * @copyright  Copyright (c) 2017 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\Profiler\Driver\Standard\Output;

use Magento\Framework\Profiler;
use Magento\Framework\Profiler\Driver\Standard\AbstractOutput;
use Magento\Framework\Profiler\Driver\Standard\Stat;
use Magento\Framework\App\ObjectManager;

/**
 * Class Html
 * @package DCKAP\Profiler\Driver\Standard\Output
 */
class Html extends AbstractOutput
{

    /**
     * Display profiling results
     *
     * @param Stat $stat
     * @return void
     */
    public function display(Stat $stat)
    {
        $objectManager = ObjectManager::getInstance();
        $layout = $objectManager->create('\Magento\Framework\View\LayoutInterface');
        $container = $objectManager->get('\DCKAP\Profiler\Block\Container');
        $container->setStat($stat);
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        if (!$isAjax) {
            echo $layout->createBlock('\DCKAP\Profiler\Block\ProfilerContainer')->toHtml();
        }
    }

    /**
     * Render timer id column value
     *
     * @param string $timerId
     * @return string
     */
    protected function _renderTimerId($timerId)
    {
        $nestingSep = preg_quote(Profiler::NESTING_SEPARATOR, '/');
        return preg_replace('/.+?' . $nestingSep . '/', '&middot;&nbsp;&nbsp;', $timerId);
    }
}
