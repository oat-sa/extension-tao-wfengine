<?php
require_once dirname(__FILE__) . '/../../tao/test/TestRunner.php';
include_once dirname(__FILE__) . '/../includes/raw_start.php';

/**
 * Test the service wfEngine_models_classes_ActivityService
 *
 * @author Lionel Lecaque, <taosupport@tudor.lu>
 * @package wfEngine
 * @subpackage test
 */

class ActivityServiceTestCase extends UnitTestCase {
    
    /**
	 * @var wfEngine_models_classes_ActivityService
     */
    protected $service;

    /**
     * output messages
     * @param string $message
     * @param boolean $ln
     * @return void
     */
    private function out($message, $ln = false){
        if(self::OUTPUT){
            if(PHP_SAPI == 'cli'){
                if($ln){
                    echo "\n";
                }
                echo "$message\n";
            }
            else{
                if($ln){
                    echo "<br />";
                }
                echo "$message<br />";
            }
        }
    }

    /**
     * Test the service implementation
     */
    public function testService(){

        $aService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ActivityService');
        $this->assertIsA($aService, 'tao_models_classes_Service');
        $this->assertIsA($aService, 'wfEngine_models_classes_ActivityService');

        $this->service = $aService;
    }

    public function testIsFinal(){
         $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
       
        //check first activity
        $this->assertTrue($this->service->isFinal($activity1) );

        
        $connector1 = $authoringService->createConnector($activity1);
		$authoringService->setConnectorType($connector1, new core_kernel_classes_Resource(INSTANCE_TYPEOFCONNECTORS_SEQUENCE));
        $activity2 = $authoringService->createSequenceActivity($connector1, null, 'activity2');
		

		$this->assertFalse($this->service->isFinal($activity1) );
		$this->assertTrue($this->service->isFinal($activity2) );
		
		$activity1->delete(true);
        $connector1->delete(true);
        $activity2->delete(true);
        $processDefinition->delete(true);
       
    }
    
    public function testIsInitial(){
        $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
        $this->assertNotNull($activity1);
        $authoringService->setFirstActivity($processDefinition, $activity1);
                
        $this->assertTrue($this->service->isInitial($activity1) );
        
        $connector1 = $authoringService->createConnector($activity1);
		$authoringService->setConnectorType($connector1, new core_kernel_classes_Resource(INSTANCE_TYPEOFCONNECTORS_SEQUENCE));
        $activity2 = $authoringService->createSequenceActivity($connector1, null, 'activity2');
                
        $this->assertTrue($this->service->isInitial($activity1) );
        $this->assertFalse($this->service->isInitial($activity2) );
        
        $activity1->delete(true);
        $connector1->delete(true);
        $activity2->delete(true);
        $processDefinition->delete(true);
    }
    
    
    public function testGetNextConnectors(){
        $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
        $this->assertNotNull($activity1);
        $authoringService->setFirstActivity($processDefinition, $activity1);
        
        $this->assertTrue(count($this->service->getNextConnectors($activity1)) == 0);
                

		
        $connector1 = $authoringService->createConnector($activity1);
        $connectorList = $this->service->getNextConnectors($activity1);
        $this->assertTrue(count($connectorList) == 1);
        $this->assertTrue(array_key_exists($connector1->uriResource, $connectorList));
        
		$authoringService->setConnectorType($connector1, new core_kernel_classes_Resource(INSTANCE_TYPEOFCONNECTORS_SEQUENCE));

		
		$activity2 = $authoringService->createSequenceActivity($connector1, null, 'activity2');
                
		$connectorList = $this->service->getNextConnectors($activity1);
        $this->assertTrue(count($connectorList) == 1);
        $this->assertTrue(array_key_exists($connector1->uriResource, $connectorList));
        
        $connector2 = $authoringService->createConnector($activity2);
        		
        $then = $authoringService->createSplitActivity($connector2, 'then');//create "Activity_2"
		$else = $authoringService->createSplitActivity($connector2, 'else', null, '', true);//create another connector
        
		
		$connector3 = $authoringService->createConnector($then);
		$activity3 = $authoringService->createSequenceActivity($connector3, null, 'activity3');
		
		$this->assertTrue(count($this->service->getNextConnectors($else)) == 0);
		$this->assertTrue(count($this->service->getNextConnectors($then)) == 1);
		$this->assertTrue(count($this->service->getNextConnectors($activity3)) == 0);
        
        $activity1->delete(true);
        $connector1->delete(true);
        $connector2->delete(true);
        $connector3->delete(true);
        $then->delete(true);
        $else->delete(true);
        $activity2->delete(true);
        $activity3->delete(true);
        $processDefinition->delete(true);
    }
    
    public function testIsActivity(){
        $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
        
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
        $this->assertTrue($this->service->isActivity($activity1) );
        $this->assertFalse($this->service->isActivity($processDefinition) );
        
        $activity1->delete(true);
       
        $processDefinition->delete(true);
    }
    
    public function testIsHidden(){
        $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
        $this->assertIsA($processDefinition, 'core_kernel_classes_Resource');
        
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
        $this->assertFalse($this->service->isHidden($activity1) );
        $authoringService->setActivityHidden($activity1, true);
        $this->assertTrue($this->service->isHidden($activity1) );
        $authoringService->setActivityHidden($activity1, false);
        $this->assertFalse($this->service->isHidden($activity1) );
        
        $activity1->delete(true);
       
        $processDefinition->delete(true);
        
    }
    
    public function testGetInteractiveServices(){
        $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
                    
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $interactiveService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_InteractiveServiceService');
        
        
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
        
        
        $service1 = $authoringService->createInteractiveService($activity1);
        $this->assertTrue( count($this->service->getInteractiveServices($activity1)) == 1 );
        $this->assertTrue( array_key_exists($service1->uriResource, $this->service->getInteractiveServices($activity1)) );
        
        $service1->delete(true);
        $activity1->delete(true);
        $processDefinition->delete(true);
    }
    
    public function testGetConstrols(){
        $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
        $this->assertIsA($processDefinition, 'core_kernel_classes_Resource');
        
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
        $authoringService->setFirstActivity($processDefinition, $activity1);
    
         $connector1 = $authoringService->createConnector($activity1);
		$authoringService->setConnectorType($connector1, new core_kernel_classes_Resource(INSTANCE_TYPEOFCONNECTORS_SEQUENCE));
        $activity2 = $authoringService->createSequenceActivity($connector1, null, 'activity2');
		
        $activity1Controls = $this->service->getControls($activity1);
        $activity2Controls = $this->service->getControls($activity2);
        
        $this->assertFalse($activity1Controls[INSTANCE_CONTROL_BACKWARD]);
        $this->assertTrue($activity2Controls[INSTANCE_CONTROL_BACKWARD]);
        $this->assertTrue($activity1Controls[INSTANCE_CONTROL_FORWARD]);  
        $this->assertTrue($activity2Controls[INSTANCE_CONTROL_FORWARD]);  
                  
        $activity1->delete(true);
        $connector1->delete(true);
        $activity2->delete(true);

        $processDefinition->delete(true);
    }
    
    public function testVirtualProcess(){
        $processAuthoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
        $this->assertIsA($processDefinition, 'core_kernel_classes_Resource');
        
        $authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        
        //define activities and connectors
        $activity1 = $authoringService->createActivity($processDefinition, 'activity1');
        $this->assertNotNull($activity1);
        $authoringService->setFirstActivity($processDefinition, $activity1);
    
        //check first activity
        $this->assertTrue($this->service->isActivity($activity1) );
        $this->assertTrue($this->service->isInitial($activity1) );

        
        $connector1 = $authoringService->createConnector($activity1);
		$authoringService->setConnectorType($connector1, new core_kernel_classes_Resource(INSTANCE_TYPEOFCONNECTORS_SEQUENCE));
        $activity2 = $authoringService->createSequenceActivity($connector1, null, 'activity2');
		
        $this->assertNotNull($activity2);		
		$this->assertTrue($this->service->isActivity($activity2) );
		$this->assertFalse($this->service->isFinal($activity1) );
		$this->assertFalse($this->service->isInitial($activity2) );
		$this->assertTrue($this->service->isFinal($activity2) );
        
		

		
		
        $activity1->delete(true);
        $connector1->delete(true);
        $activity2->delete(true);
        $processDefinition->delete(true);
        
         
    }

}