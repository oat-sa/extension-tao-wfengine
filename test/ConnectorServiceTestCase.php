<?php
require_once dirname(__FILE__) . '/../../tao/test/TestRunner.php';
include_once dirname(__FILE__) . '/../includes/raw_start.php';

/**
 * Test the service wfEngine_models_classes_ConnectorService
 *
 * @author Lionel Lecaque, <taosupport@tudor.lu>
 * @package wfEngine
 * @subpackage test
 */

class ConnectorServiceTestCase extends UnitTestCase {
    /**
     * @var wfEngine_models_classes_ActivityService
     */
    protected $service;
    protected $authoringService;
    protected $processDefinition;
    protected $activity;

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
     * tests initialization
     */
    public function setUp(){
        TestRunner::initTest();

        $this->authoringService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessAuthoringService');
        $processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
        $this->processDefinition = $processDefinitionClass->createInstance('ProcessForUnitTest', 'Unit test');
         
        //define activities and connectors
        $activity = $this->authoringService->createActivity($this->processDefinition, 'activity for interactive service unit test');
        if($activity instanceof core_kernel_classes_Resource){
            $this->activity = $activity;
        }else{
            $this->fail('fail to create a process definition resource');
        }
    }

    public function tearDown() {
        $this->assertTrue($this->authoringService->deleteProcess($this->processDefinition));
    }

    /**
     * Test the service implementation
     */
    public function testService(){

        $aService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ConnectorService');
        $this->assertIsA($aService, 'tao_models_classes_Service');
        $this->assertIsA($aService, 'wfEngine_models_classes_ConnectorService');

        $this->service = $aService;
    }

    public function testIsConnector(){
        $connector1 = $this->authoringService->createConnector($this->activity);
        $this->authoringService->setConnectorType($connector1, new core_kernel_classes_Resource(INSTANCE_TYPEOFCONNECTORS_SEQUENCE));
        $activity2 = $this->authoringService->createSequenceActivity($connector1, null, 'activity2');
        $this->assertTrue($this->service->isConnector($connector1));
        $this->assertFalse($this->service->isConnector($activity2));

        $connector1->delete(true);
        $activity2->delete(true);
    }

    public function testGetTransitionRule(){
        $connector1 = $this->authoringService->createConnector($this->activity);

        $then = $this->authoringService->createSplitActivity($connector1, 'then');//create "Activity_2"
        $else = $this->authoringService->createSplitActivity($connector1, 'else', null, '', true);//create another connector
        $activity3 = $this->authoringService->createSequenceActivity($else, null, 'Act3');

        $myProcessVar1 = $this->authoringService->getProcessVariable('myProcessVarCode1', true);
        $transitionRule = $this->authoringService->createTransitionRule($connector1, '^myProcessVarCode1 == 1');

        $transitionRuleBis = $this->service->getTransitionRule($connector1);
        $this->assertEqual($transitionRule->uriResource,$transitionRuleBis->uriResource);

        $then->delete(true);
        $else->delete(true);
        $activity3->delete(true);
        $transitionRule->delete(true);
        $connector1->delete(true);

    }

    public function testGetType(){


        /*
         *  activity > connector1(COND)
         *  -> THEN  > thenConnector(SQ)
         *  -> ELSE > elseConnector (SQ)
         *  -> Act3 > connector2(PARA)
         *  -> Act4 > connector3(JOIN)
         *  -> Act5 > connector4(JOIN)
         * 	-> Acto6
         *
         */
        $connector1 = $this->authoringService->createConnector($this->activity);

        $then = $this->authoringService->createSplitActivity($connector1, 'then');//create "Activity_2"
        $thenConnector = $this->authoringService->createConnector($then, 'then Connector');//create "Activity_2"

        $else = $this->authoringService->createSplitActivity($connector1, 'else', null, '', true);//create another connector
        $elseConnector = $this->authoringService->createConnector($else, 'else Connector');//create "Activity_2"

        $activity3 = $this->authoringService->createSequenceActivity($thenConnector, null, 'Act3');
        $this->authoringService->createSequenceActivity($elseConnector, $activity3);

        $this->assertIsA($this->service->getType($thenConnector),'core_kernel_classes_Resource');
        $this->assertIsA($this->service->getType($elseConnector),'core_kernel_classes_Resource');
        $this->assertEqual($this->service->getType($thenConnector)->uriResource, INSTANCE_TYPEOFCONNECTORS_SEQUENCE);
        $this->assertEqual($this->service->getType($elseConnector)->uriResource, INSTANCE_TYPEOFCONNECTORS_SEQUENCE);

        $myProcessVar1 = $this->authoringService->getProcessVariable('myProcessVarCode1', true);
        $transitionRule = $this->authoringService->createTransitionRule($connector1, '^myProcessVarCode1 == 1');
        
        $connectorType = $this->service->getType($connector1);
        $this->assertEqual($connectorType->uriResource,INSTANCE_TYPEOFCONNECTORS_CONDITIONAL);

        $connector2 = $this->authoringService->createConnector($activity3);
        $activity4 = $this->authoringService->createActivity($this->processDefinition, 'activity4 for interactive service unit test');
        $connector3 = $this->authoringService->createConnector($activity4);

        $activity5 = $this->authoringService->createActivity($this->processDefinition, 'activity5 for interactive service unit test');
        $connector4 = $this->authoringService->createConnector($activity5);

        $newActivitiesArray = array(
            $activity4->uriResource => 2,
            $activity5->uriResource => 3
        );

        $this->authoringService->setParallelActivities($connector2, $newActivitiesArray);
        $activity6 = $this->authoringService->createJoinActivity($connector3, null, '', $activity4);
        $this->authoringService->createJoinActivity($connector4, null, '', $activity5);

        $this->assertEqual($this->service->getType($connector2)->uriResource, INSTANCE_TYPEOFCONNECTORS_PARALLEL);
        $this->assertEqual($this->service->getType($connector3)->uriResource, INSTANCE_TYPEOFCONNECTORS_JOIN);
        $this->assertEqual($this->service->getType($connector4)->uriResource, INSTANCE_TYPEOFCONNECTORS_JOIN);

        $then->delete(true);
        $else->delete(true);
        $activity3->delete(true);
        $activity4->delete(true);
        $activity5->delete(true);
        $activity6->delete(true);

        $transitionRule->delete(true);
        $connector1->delete(true);
        $connector2->delete(true);
        $connector3->delete(true);
        $connector4->delete(true);

    }
    
  public function testGetNextActivities(){


        /*
         *  activity > connector1(COND)
         *  -> THEN  > thenConnector(SQ)
         *  -> ELSE > elseConnector (SQ)
         *  -> Act3 > connector2(PARA)
         *  -> Act4 > connector3(JOIN)
         *  -> Act5 > connector4(JOIN)
         * 	-> Acto6
         *
         */
        $connector1 = $this->authoringService->createConnector($this->activity);

        $then = $this->authoringService->createSplitActivity($connector1, 'then');//create "Activity_2"
        $thenConnector = $this->authoringService->createConnector($then, 'then Connector');//create "Activity_2"

        $else = $this->authoringService->createSplitActivity($connector1, 'else', null, '', true);//create another connector
        $elseConnector = $this->authoringService->createConnector($else, 'else Connector');//create "Activity_2"

        $activity3 = $this->authoringService->createSequenceActivity($thenConnector, null, 'Act3');
        $this->authoringService->createSequenceActivity($elseConnector, $activity3);

       //  $this->assertIsA($this->service->getNextActivities($thenConnector),'core_kernel_classes_ContainerCollection');
        // $this->assertTrue($this->service->getNextActivities($thenConnector)->count() == 3 );

        $connector1NextAct = $this->service->getNextActivities($connector1);
        $connector1RealNextAct = array($then->uriResource,$else->uriResource);
        $this->assertIsA($connector1NextAct,'array');
        $this->assertTrue(sizeof($connector1NextAct) == 2);
        foreach ($connector1NextAct as $nextAct){
           $this->assertTrue(in_array($nextAct->uriResource, $connector1RealNextAct));
        }
    
        $elseNextAct = $this->service->getNextActivities($elseConnector);

        $this->assertIsA($elseNextAct,'array');
        $this->assertTrue(sizeof($elseNextAct) == 1);
        if(isset($elseNextAct[0]) && $elseNextAct[0] instanceof core_kernel_classes_Resource){
            $this->assertTrue($elseNextAct[0]->uriResource == $activity3->uriResource);
        }

        $thenNextAct = $this->service->getNextActivities($thenConnector);

        $this->assertIsA($thenNextAct,'array');
        $this->assertTrue(sizeof($thenNextAct) == 1);
         if(isset($thenNextAct[0]) && $thenNextAct[0] instanceof core_kernel_classes_Resource){
            $this->assertTrue($thenNextAct[0]->uriResource == $activity3->uriResource);
        }

        
        $myProcessVar1 = $this->authoringService->getProcessVariable('myProcessVarCode1', true);
        $transitionRule = $this->authoringService->createTransitionRule($connector1, '^myProcessVarCode1 == 1');
        

        $connector2 = $this->authoringService->createConnector($activity3);
        $activity4 = $this->authoringService->createActivity($this->processDefinition, 'activity4 for interactive service unit test');
        $connector3 = $this->authoringService->createConnector($activity4);

        $activity5 = $this->authoringService->createActivity($this->processDefinition, 'activity5 for interactive service unit test');
        $connector4 = $this->authoringService->createConnector($activity5);

        $newActivitiesArray = array(
            $activity4->uriResource => 2,
            $activity5->uriResource => 3
        );

        $this->authoringService->setParallelActivities($connector2, $newActivitiesArray);
        $activity6 = $this->authoringService->createJoinActivity($connector3, null, '', $activity4);
        $this->authoringService->createJoinActivity($connector4, null, '', $activity5);
        
        $activity3NextActi = $this->service->getNextActivities($connector2);
        $this->assertIsA($activity3NextActi,'array');
        $this->assertTrue(sizeof($activity3NextActi) == 5);
        $newActivitiesarrayCount = array();
        foreach ($activity3NextActi as $acti){
            $this->assertTrue(array_key_exists($acti->uriResource, $newActivitiesArray));
            if(array_key_exists($acti->uriResource, $newActivitiesArray)){      
                if(isset( $newActivitiesarrayCount[$acti->uriResource])){
                    $newActivitiesarrayCount[$acti->uriResource] ++;
                }
                else{
                    $newActivitiesarrayCount[$acti->uriResource] = 1;
                }  
            }

        }
        $this->assertEqual($newActivitiesarrayCount, $newActivitiesArray);
        
        $activity4NextActi = $this->service->getNextActivities($connector3);

        $activity5NextActi = $this->service->getNextActivities($connector4);
        
        $this->assertTrue(sizeof($activity4NextActi) == 1);
         if(isset($activity4NextActi[0]) && $activity4NextActi[0] instanceof core_kernel_classes_Resource){
                        $activity4NextActi[0]->getLabel();
             $this->assertTrue($activity4NextActi[0]->uriResource == $activity6->uriResource);
        }
        
          $this->assertTrue(sizeof($activity5NextActi) == 1);
         if(isset($activity5NextActi[0]) && $activity5NextActi[0] instanceof core_kernel_classes_Resource){
            $activity5NextActi[0]->getLabel();
             $this->assertTrue($activity5NextActi[0]->uriResource == $activity6->uriResource);
            
        }

        var_dump($activity4NextActi,$activity5NextActi);
        
        $then->delete(true);
        $else->delete(true);
        $activity3->delete(true);
        $activity4->delete(true);
        $activity5->delete(true);
        $activity6->delete(true);

        $transitionRule->delete(true);
        $connector1->delete(true);
        $connector2->delete(true);
        $connector3->delete(true);
        $connector4->delete(true);

    }

}