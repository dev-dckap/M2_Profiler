<?php
/**
 * @author     DCKAP
 * @package    DCKAP_Profiler
 * @copyright  Copyright (c) 2017 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\Profiler\Block;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Filesystem;

/**
 * Class Profiler
 * @package DCKAP\Profiler\Block
 */
class Mysql extends Template
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
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManagerInterface;
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var string
     */
    protected $_template = 'mysql.phtml';
    /**
     * @var mysqlConnection
     */
    protected $mysqlConnection;

    public function __construct(
        Context $context,
        Filesystem $filesystem,
        ResourceConnection $mysqlConnection,
        StoreManagerInterface $storeManagerInterface,
        array $data = []
    )
    {

        $this->mysqlConnection = $mysqlConnection;
        $this->filesystem = $filesystem;
        $this->storeManagerInterface = $storeManagerInterface;
        parent::__construct($context, $data);
        $this->filePath = 'mysqlprofiler.csv';
        $this->delimiter = ',';
        $this->enclosure = '"';
    }

    /**
     * @return \Zend_Db_Profiler
     */
    public function getMysqlProfiler()
    {
        return $this->mysqlConnection->getConnection('read')
            ->getProfiler();
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
     * Write code profiling results to CSV-file
     *
     * @param Stat $stat
     * @return void
     * @throws \RuntimeException if output file cannot be opened
     */
    public function downloadCSV()
    {
        $chkEnabled = $this->getConfigValue('profiler_section/profiler_group_general/csvmysql');
        if ($chkEnabled) {
            $pubDirectory = $this->filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::PUB);
            $fileHandle = fopen($pubDirectory->getAbsolutePath().$this->filePath, 'w');
            if (!$fileHandle) {
                throw new \RuntimeException(sprintf('Can not open a file "%s".', $this->filePath));
            }

            $csvOutput = $this->getMysqlProfiler();
            $lockRequired = strpos($pubDirectory->getAbsolutePath().$this->filePath, 'php://') !== 0;
            $isLocked = false;
            while ($lockRequired && !$isLocked) {
                $isLocked = flock($fileHandle, LOCK_EX);
            }

            $this->_writeFileContent($fileHandle,  $csvOutput);
            if ($isLocked) {
                flock($fileHandle, LOCK_UN);
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
        $row[] = "Timer";
        $row[] = "Query";
        $row[] = "Params";
        fputcsv($fileHandle, $row, $this->delimiter, $this->enclosure);
        foreach ($csvOutput->getQueryProfiles() as $query) {
            $row = [];
            $row[] = number_format(1000 * $query->getElapsedSecs(), 2)."ms";
            $row[] = $query->getQuery();
            $row[] = json_encode($query->getQueryParams());
            fputcsv($fileHandle, $row, $this->delimiter, $this->enclosure);
        }
    }
}
