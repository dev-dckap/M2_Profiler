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
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;
use DCKAP\Profiler\Block\Container;

/**
 * Class Profiler
 * @package DCKAP\Profiler\Block
 */
class Profiler extends Template
{
    /**
     *
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var string
     */
    protected $enclosure;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var string
     */
    protected $_template = 'profiler.phtml';

    /**
     * @var container
     */
    protected $container;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManagerInterface;

    public function __construct(
        Context $context,
        Filesystem $filesystem,
        Container $container,
        StoreManagerInterface $storeManagerInterface,
        array $data = []
    ) {

        $this->container = $container;
        $this->filesystem = $filesystem;
        $this->storeManagerInterface = $storeManagerInterface;
        parent::__construct($context, $data);
        $this->filePath = 'codeprofiler.csv';
        $this->delimiter = ',';
        $this->enclosure = '"';
    }

    /**
     * @return \Magento\Framework\Profiler\Driver\Standard\Stat
     */
    public function getStat()
    {
        return $this->container->getStat();
    }

    /**
     * @param int $timerId
     * @return string
     */
    public function renderTimerId($timerId)
    {
        $nestingSep = preg_quote('->', '/');

        return preg_replace('/.+?' . $nestingSep . '/', '', $timerId);
    }

    /**
     * @param int $timerId
     * @return string
     */
    public function getParentTimerId($timerId)
    {
        $timerId = explode('->', $timerId);
        array_pop($timerId);

        return implode('->', $timerId);
    }

    /**
     * @param int $timerId
     * @return float
     */
    public function getTimerLength($timerId)
    {
        $total = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        return round($this->getStat()->fetch($timerId, 'sum') / $total * 100, 2);
    }

    /**
     * @return float
     */
    public function getTotalTime()
    {
        return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param int $timerId
     * @return array
     */
    public function getOptionalColumnHeader($path)
    {
        $headers[0] = "header";
        $headers[1] = "Realmem";
        $headers[2] = "Emalloc";
        $value = $this->getConfigValue($path);
        if ($value) {
            if (strpos($value, ',') !== false) {
                $val = explode(',', $value);
                for ($i=0; $i<count($val); $i++)  {
                    unset($headers[$val[$i]]);
                }
            } else {
                unset($headers[$value]);
            }
        }

        return $headers;
    }

    /**
     * @param int $timerId
     * @return array
     */
    public function getOptionalColumnValue($path)
    {
        $colsvalue[0] = "value";
        $colsvalue[1] = "realmem";
        $colsvalue[2] = "emalloc";

        $value = $this->getConfigValue($path);
        if ($value) {
            if (strpos($value, ',') !== false) {
                $val = explode(',', $value);
                for ($i=0; $i<count($val); $i++) {
                    unset($colsvalue[$val[$i]]);
                }
            } else {
                unset($colsvalue[$value]);
            }
        }

        return $colsvalue;
    }

    /**
     * Write code profiling results to CSV-file
     *
     * @param Stat $stat
     * @return void
     * @throws \RuntimeException if output file cannot be opened
     */
    public function downloadCSV()
    {
        $chkEnabled = $this->getConfigValue('profiler_section/profiler_group_general/csvcode');
        if ($chkEnabled) {
            $pubDirectory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::PUB);
            $fileHandle = fopen($pubDirectory->getAbsolutePath().$this->filePath, 'w');
            if (!$fileHandle) {
                throw new \RuntimeException(sprintf('Can not open a file "%s".', $this->filePath));
            }

            $csvOutput = $this->getStat();
            $lockRequired = strpos($pubDirectory->getAbsolutePath().$this->filePath, 'php://') !== 0;
            $isLocked = false;
            while ($lockRequired && !$isLocked) {
                $isLocked = flock($fileHandle, LOCK_EX);
            }

            $this->_writeFileContent($fileHandle,  $csvOutput);
            if ($isLocked) {
                flock($fileHandle,  LOCK_UN);
            }

            fclose($fileHandle);
        }
    }

    /**
     * Write content into an opened file handle
     *
     * @param resource $fileHandle
     * @param Stat $stat
     * @return void
     */
    protected function _writeFileContent($fileHandle, $csvOutput)
    {
        $row = [];
        $row[] = "Timerid";
        $row[] = "Time(ms)";
        $row[] = "Avg(ms)";
        $row[] = "Cnt";
        $row[] = "Realmem";
        $row[] = "Emalloc";
        fputcsv($fileHandle, $row, $this->delimiter, $this->enclosure);
        foreach ($csvOutput->getFilteredTimerIds() as $timerId) {
            $row = [];
            $row[] = $this->renderTimerId($csvOutput->fetch($timerId, 'id'));
            $row[] = number_format(($csvOutput->fetch($timerId, 'sum') * 1000), 1, '.', '');
            $row[] = number_format(($csvOutput->fetch($timerId, 'avg') * 1000), 1, '.', '');
            $row[] = $csvOutput->fetch($timerId, 'count');
            $row[] = $csvOutput->fetch($timerId, 'realmem');
            $row[] = $csvOutput->fetch($timerId, 'emalloc');
            fputcsv($fileHandle, $row, $this->delimiter, $this->enclosure);
        }

    }

}
