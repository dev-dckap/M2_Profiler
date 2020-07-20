<?php
/**
 * @author     DCKAP
 * @package    DCKAP_Profiler
 * @copyright  Copyright (c) 2017 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\Profiler\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use DCKAP\Profiler\Block\Container;
use DCKAP\Profiler\Block\Profiler;
use DCKAP\Profiler\Block\Mysql;

/**
 * Class ProfilerContainer
 * @package DCKAP\Profiler\Block
 */
class ProfilerContainer extends Template
{
    /**
     * @var XML_PROFILE_ENABLED
     */
    const XML_PROFILE_ENABLED = 'profiler_section/profiler_group_general/status';
    /**
     * @var string
     */
    protected $_template = 'profilerContainer.phtml';
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var Profiler
     */
    protected $profiler;
    /**
     * @var mysql
     */
    protected $mysql;
    /**
     * @var scopeConfig
     */
    protected $scopeConfig;
    /**
     * @var container
     */
    protected $container;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Container $container,
        Profiler $profiler,
        Mysql $mysql,
        array $data = []
    )
    {
        $this->profiler = $profiler;
        $this->mysql = $mysql;
        $this->scopeConfig = $scopeConfig;
        $this->container = $container;
        parent::__construct($context, $data);
    }

    /**
     * @return profiler page
     */
    public function getProfilerPage()
    {
        return $this->profiler;
    }

    /**
     * @return mysql page
     */
    public function getMysqlPage()
    {
        return $this->mysql;
    }

    /**
     * @return float
     */
    public function getRequestTime()
    {
        return $this->container->getRequestTime();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $status = $this->scopeConfig->getValue(self::XML_PROFILE_ENABLED, $storeScope);
    }
}
