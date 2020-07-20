<?php
/**
 * @author     DCKAP
 * @package    DCKAP_Profiler
 * @copyright  Copyright (c) 2017 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\Profiler\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class OptionalColumn
 * @package DCKAP\Profiler\Model\Config\Source
 */
class OptionalColumn implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {

        $arr = [
            0 => "Un select all",
            1 => "Realmem",
            2 => "Emalloc"
        ];

        $ret = [];

        foreach ($arr as $key => $value) {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }
}
