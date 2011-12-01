<?php

error_reporting(E_ALL);

/**
 * TAO - wfEngine/helpers/Monitoring/class.ExecutionHistoryGrid.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 16.11.2011, 10:26:42 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 * @package wfEngine
 * @subpackage helpers_Monitoring
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include wfEngine_helpers_Monitoring_ActivityMonitoringGrid
 *
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 */
require_once('wfEngine/helpers/Monitoring/class.ActivityMonitoringGrid.php');

/* user defined includes */
// section 127-0-1-1-41d91020:13392d7ae4a:-8000:00000000000033A3-includes begin
// section 127-0-1-1-41d91020:13392d7ae4a:-8000:00000000000033A3-includes end

/* user defined constants */
// section 127-0-1-1-41d91020:13392d7ae4a:-8000:00000000000033A3-constants begin
// section 127-0-1-1-41d91020:13392d7ae4a:-8000:00000000000033A3-constants end

/**
 * Short description of class wfEngine_helpers_Monitoring_ExecutionHistoryGrid
 *
 * @access public
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 * @package wfEngine
 * @subpackage helpers_Monitoring
 */
class wfEngine_helpers_Monitoring_ExecutionHistoryGrid
    extends wfEngine_helpers_Monitoring_ActivityMonitoringGrid
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method initGrid
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @return boolean
     */
    public function initGrid()
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-41d91020:13392d7ae4a:-8000:00000000000033A5 begin
		if($this->data instanceof core_kernel_classes_Resource && $this->data->hasType(new core_kernel_classes_Class(CLASS_PROCESSINSTANCES))){
			$processExecutionService = wfEngine_models_classes_ProcessExecutionService::singleton();
			$this->data = $processExecutionService->getExecutionHistory($this->data);
			parent::initGrid();
		}else{
			throw new common_Exception('the data is not a process instance resource');
		}
		
        // section 127-0-1-1-41d91020:13392d7ae4a:-8000:00000000000033A5 end

        return (bool) $returnValue;
    }

} /* end of class wfEngine_helpers_Monitoring_ExecutionHistoryGrid */

?>