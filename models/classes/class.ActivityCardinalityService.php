<?php

error_reporting(E_ALL);

/**
 * TAO - wfEngine/models/classes/class.ActivityCardinalityService.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 29.09.2011, 14:49:27 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 * @package wfEngine
 * @subpackage models_classes
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * The Service class is an abstraction of each service instance. 
 * Used to centralize the behavior related to every servcie instances.
 *
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 */
require_once('tao/models/classes/class.GenerisService.php');

/* user defined includes */
// section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000304E-includes begin
// section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000304E-includes end

/* user defined constants */
// section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000304E-constants begin
// section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000304E-constants end

/**
 * Short description of class wfEngine_models_classes_ActivityCardinalityService
 *
 * @access public
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 * @package wfEngine
 * @subpackage models_classes
 */
class wfEngine_models_classes_ActivityCardinalityService
    extends tao_models_classes_GenerisService
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method isCardinality
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @param  Resource activityCardinality
     * @return boolean
     */
    public function isCardinality( core_kernel_classes_Resource $activityCardinality)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000304F begin
		
		if(!is_null($activityCardinality)){
			$returnValue = $activityCardinality->hasType($this->classMultiplicity);
		}
		
        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000304F end

        return (bool) $returnValue;
    }

    /**
     * Short description of method createCardinality
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @param  Resource activity
     * @param  int cardinality
     * @return core_kernel_classes_Resource
     */
    public function createCardinality( core_kernel_classes_Resource $activity, $cardinality = 1)
    {
        $returnValue = null;

        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003052 begin
		$cardinalityValue = null;
		if(is_numeric($cardinality)){
			$cardinalityValue = intval($cardinality);
		}else if($cardinality instanceof core_kernel_classes_Resource){
			$cardinalityValue = $cardinality;
		}
		
		if(!is_null($cardinalityValue)){
			$returnValue = $this->classMultiplicity->createInstance(); //empty label please, for perf optimization
			$returnValue->setPropertyValue($this->propMultiplicityActivity, $activity);
			$returnValue->setPropertyValue($this->propMultiplicityCardinality, $cardinality);
		}
		
        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003052 end

        return $returnValue;
    }

    /**
     * Short description of method getActivity
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @param  Resource activityCardinality
     * @return core_kernel_classes_Resource
     */
    public function getActivity( core_kernel_classes_Resource $activityCardinality)
    {
        $returnValue = null;

        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003058 begin
		if(!is_null($activityCardinality)){
			$returnValue = $activityCardinality->getUniquePropertyValue($this->propMultiplicityActivity);
		}
        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003058 end

        return $returnValue;
    }

    /**
     * Short description of method getCardinality
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @param  Resource activityCardinality
     * @param  Resource activityExecution
     * @return mixed
     */
    public function getCardinality( core_kernel_classes_Resource $activityCardinality,  core_kernel_classes_Resource $activityExecution = null)
    {
        $returnValue = null;

        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000305B begin
		if(!is_null($activityCardinality)){
			$cardinality = $activityCardinality->getUniquePropertyValue($this->propMultiplicityCardinality);
			if($cardinality instanceof core_kernel_classes_Literal && is_numeric((string)$cardinality)){
				$returnValue = intval((string)$cardinality);
			}else if($cardinality instanceof core_kernel_classes_Resource){
				//consider it as a process variable:
				if(is_null($activityExecution)){
					$returnValue = $cardinality;
				}else{
					//we want to retrieve the value in the context of execution
					$cardinalityValue = $activityExecution->getOnePropertyValue(new core_kernel_classes_Property($cardinality->uriResource));
					if($cardinalityValue instanceof core_kernel_classes_Literal){
						$returnValue = intval((string)$cardinalityValue);
					}else{
						$returnValue = 0;
						throw new wfEngine_models_classes_ProcessExecutionException('cardinality must be of a numeric type in a process variable');
					}
				}
			}else{
				throw new wfEngine_models_classes_ProcessDefinitonException('cardinality must be a process variable of an integer');
			}
		}
        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:000000000000305B end

        return $returnValue;
    }

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @return mixed
     */
    public function __construct()
    {
        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003064 begin
		
		$this->classMultiplicity = new core_kernel_classes_Class(CLASS_ACTIVITYCARDINALITY);
		$this->propMultiplicityActivity = new core_kernel_classes_Property(PROPERTY_ACTIVITYCARDINALITY_ACTIVITY);
		$this->propMultiplicityCardinality = new core_kernel_classes_Property(PROPERTY_ACTIVITYCARDINALITY_CARDINALITY);
		
        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003064 end
    }

    /**
     * Short description of method editCardinality
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @param  Resource activityCardinality
     * @param  int cardinality
     * @return boolean
     */
    public function editCardinality( core_kernel_classes_Resource $activityCardinality, $cardinality = 1)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003067 begin
		if(!is_null($activityCardinality)){
			$returnValue = $activityCardinality->editPropertyValues($this->propMultiplicityCardinality, intval($cardinality));
		}
        // section 127-0-1-1-6eb1148b:132b4a0f8d0:-8000:0000000000003067 end

        return (bool) $returnValue;
    }

} /* end of class wfEngine_models_classes_ActivityCardinalityService */

?>