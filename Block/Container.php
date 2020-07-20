<?php
/**
 * @author     DCKAP
 * @package    DCKAP_Profiler
 * @copyright  Copyright (c) 2017 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\Profiler\Block;

/**
 * Class Container
 * @package DCKAP\Profiler\Block
 */
class Container
{
    /**
     * @var \Magento\Framework\Profiler\Driver\Standard\Stat
     */
    public $stat;

    /**
     * @return \Magento\Framework\Profiler\Driver\Standard\Stat
     */
    public function getStat()
    {
        return $this->stat;
    }

    /**
     * @param \Magento\Framework\Profiler\Driver\Standard\Stat $stat
     * @return $this
     */
    public function setStat($stat)
    {
        $this->stat = $stat;
        return $this;
    }

    /**
     * @return float
     */
    public function getRequestTime()
    {
        return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    }
}
