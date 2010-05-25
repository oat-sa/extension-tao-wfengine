<?php
// require_once dirname(__FILE__) . '/../../tao/test/TestRunner.php';
// set_include_path(get_include_path().';'.dirname(__FILE__).'/../..');

require_once dirname(__FILE__) . '/../../generis/common/inc.extension.php';
require_once dirname(__FILE__) . '/../includes/common.php';
require_once INCLUDES_PATH.'/simpletest/autorun.php';

if(!defined("LOGIN")){
	define("LOGIN", "generis", true);
}
/**
* @constant password for the module you wish to connect to 
*/
if(!defined("PASS")){
	define("PASS", "g3n3r1s", true);
}
/**
* @constant module for the module you wish to connect to 
*/
if(!defined("MODULE")){
	define("MODULE", "tao", true);
}

error_reporting(E_ALL);

class ProcessAuthoringServiceTestCase extends UnitTestCase {
	
	
	protected $authoringService = null;
	protected $proc = null;
	protected $apiModel = null;
	
	/**
	 * tests initialization
	 */
	public function setUp(){
		// TestRunner::initTest();
		$this->apiModel = core_kernel_impl_ApiModelOO::singleton();
		$this->apiModel->logIn(LOGIN,md5(PASS),DATABASE_NAME,true);
		$processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
		$processDefinition = $processDefinitionClass->createInstance('processForUnitTest','created for the unit test of process authoring service');
		if($processDefinition instanceof core_kernel_classes_Resource){
			$this->proc = $processDefinition;
		}
	}
	
	/**
	 * Test the service implementation
	 */
	public function testService(){
		
		$authoringService = new wfEngine_models_classes_ProcessAuthoringService();
		$this->assertIsA($authoringService, 'tao_models_classes_Service');
		$this->assertIsA($authoringService, 'wfEngine_models_classes_ProcessAuthoringService');

		$this->authoringService = $authoringService;
	}
	
	
	public function testDeleteProcess(){
		
		$processDefinitionClass = new core_kernel_classes_Class(CLASS_PROCESS);
		$processDefinition = $processDefinitionClass->createInstance('myProcess','created for the unit test of process authoring service');
		if($processDefinition instanceof core_kernel_classes_Resource){
			$this->assertTrue($this->authoringService->deleteProcess($processDefinition));
			$this->assertTrue($this->apiModel->getSubject(RDFS_LABEL, 'myProcess')->isEmpty());
		}
		
	}
	
	public function testCreateActivity(){
		
			$activity1 = $this->authoringService->createActivity($this->proc);
			$this->assertEqual($activity1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_ACTIVITIES_ISINITIAL))->uriResource, GENERIS_TRUE);
			$this->assertEqual($activity1->getLabel(), 'Activity_1');
			$this->assertEqual($activity1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_ACTIVITIES_ISHIDDEN))->uriResource, GENERIS_FALSE);
			
			$activity2 = $this->authoringService->createActivity($this->proc, 'myActivity');
			$this->assertEqual($activity2->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_ACTIVITIES_ISINITIAL))->uriResource, GENERIS_FALSE);
			
			$activity1->delete();
			$activity2->delete();
		
	}
	
	public function testIsActivity(){
		$activity1 = $this->authoringService->createActivity($this->proc);
		
		$this->assertTrue(wfEngine_models_classes_ProcessAuthoringService::isActivity($activity1));
		
		$activity1->delete();
	}
	
	public function testIsConnector(){
		$activity1 = $this->authoringService->createActivity($this->proc, 'myActivity');
		$connector1 = $this->authoringService->createConnector($activity1);
		
		$this->assertTrue(wfEngine_models_classes_ProcessAuthoringService::isConnector($connector1));
		
		$activity1->delete();
		$connector1->delete();
	}
	
	public function testCreateConnector(){
		
		$activity1 = $this->authoringService->createActivity($this->proc, 'myActivity');
		$connector1 = $this->authoringService->createConnector($activity1);
		$this->assertEqual($connector1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_PRECACTIVITIES))->uriResource, $activity1->uriResource);
		$this->assertEqual($connector1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_ACTIVITYREFERENCE))->uriResource, $activity1->uriResource);
		
		//create a connector of a connector:
		$connector2 = $this->authoringService->createConnector($connector1);
		$this->assertEqual($connector2->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_ACTIVITYREFERENCE))->uriResource, $activity1->uriResource);
		
		$activity1->delete();
		$connector1->delete();
		$connector2->delete();
	}
	
	public function testAnalyseExpression(){
		
		$conditionDom = $this->authoringService->analyseExpression('(3*(^var +  1) = 2 or ^var > 7)', true);
		$this->assertIsA($conditionDom, 'DOMDocument');
		$isCondition = false;
		foreach ($conditionDom->childNodes as $childNode) {
			foreach ($childNode->childNodes as $childOfChildNode) {
				if ($childOfChildNode->nodeName == "condition"){
					$isCondition = true;
					break 2;//once is enough...
				
				}
			}
		}
		$this->assertTrue($isCondition);
		
		$assignmentDom = $this->authoringService->analyseExpression('^var = ^var*32 + ^SCR');
		$isAssignment = false;
		foreach ($assignmentDom->childNodes as $childNode) {
			foreach ($childNode->childNodes as $childOfChildNode) {
				if ($childOfChildNode->nodeName == "then"){
					$isAssignment = true;
					break 2;//stop at the first occurence of course
				}
			}
		}
		$this->assertTrue($isAssignment);
		
	}

	public function testCreateSequenceActivity(){
		$activity1 = $this->authoringService->createActivity($this->proc, 'myActivity');
		$connector1 = $this->authoringService->createConnector($activity1);
		$followingActivity1 = $this->authoringService->createSequenceActivity($connector1);
		$this->assertIsA($followingActivity1, 'core_kernel_classes_Resource');
		$this->assertEqual($followingActivity1->getLabel(), 'Activity_2');
		$this->assertEqual($connector1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_TYPE))->uriResource, INSTANCE_TYPEOFCONNECTORS_SEQUENCE);
		
		$followingConnector1 = $this->apiModel->getSubject(PROPERTY_CONNECTORS_PRECACTIVITIES, $followingActivity1->uriResource)->get(0);
		$this->assertIsA($followingConnector1, 'core_kernel_classes_Resource');

		$shouldBeActivity1 = null;
		$shouldBeActivity1 = $this->authoringService->createSequenceActivity($followingConnector1, $activity1);
		$this->assertEqual($activity1->uriResource, $shouldBeActivity1->uriResource);
		
		$shouldBeActivity1 = null;
		$shouldBeActivity1 = $followingConnector1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_NEXTACTIVITIES));
		$this->assertEqual($activity1->uriResource,$shouldBeActivity1->uriResource);
		
		$activity1->delete();
		$connector1->delete();
		$followingActivity1->delete();
		$followingConnector1->delete();
	}
		
	public function testCreateSplitActivity(){
		$activity1 = $this->authoringService->createActivity($this->proc, 'myActivity');
		$connector1 = $this->authoringService->createConnector($activity1);
		
		$then = $this->authoringService->createSplitActivity($connector1, 'then');//create "Activity_2"
		$else = $this->authoringService->createSplitActivity($connector1, 'else', null, '', true);//create another connector
		$this->assertEqual($connector1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_TYPE))->uriResource, INSTANCE_TYPEOFCONNECTORS_SPLIT);
		$this->assertTrue(wfEngine_models_classes_ProcessAuthoringService::isActivity($then));
		$this->assertTrue(wfEngine_models_classes_ProcessAuthoringService::isConnector($else));
		
		$transitionRule = $connector1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_TRANSITIONRULE));
		$this->assertEqual($then->uriResource, $transitionRule->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_TRANSITIONRULES_THEN))->uriResource);
		$this->assertEqual($else->uriResource, $transitionRule->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_TRANSITIONRULES_ELSE))->uriResource);
		
		$activity1->delete();
		$connector1->delete();
		$transitionRule->delete();
		$then->delete();
		$else->delete();
	}
	
	
	public function testDeleteConnectorNextActivity(){
		$activity1 = $this->authoringService->createActivity($this->proc, 'myActivity');
		$connector1 = $this->authoringService->createConnector($activity1);
		$this->authoringService->createSequenceActivity($connector1, null, '2ndActivityForUnitTest');
		
		$nextActivitiesProp = new core_kernel_classes_Property(PROPERTY_CONNECTORS_NEXTACTIVITIES);
		
		$activity2 = $connector1->getUniquePropertyValue($nextActivitiesProp);
		$this->assertIsA($activity2 , 'core_kernel_classes_Resource');
		
		$this->authoringService->deleteConnectorNextActivity($connector1, 'next');
		$followingActivity1 = $connector1->getOnePropertyValue($nextActivitiesProp);
		$this->assertNull($followingActivity1);
		
		$connector2 = $this->apiModel->getSubject(PROPERTY_CONNECTORS_PRECACTIVITIES, $activity2->uriResource)->get(0);
		$then = $this->authoringService->createSplitActivity($connector2, 'then');//create "Activity_2"
		$else = $this->authoringService->createSplitActivity($connector2, 'else', null, '', true);//create another connector
		
		$this->authoringService->deleteConnectorNextActivity($connector2, 'then');
		$this->authoringService->deleteConnectorNextActivity($connector2, 'else');
		
		$transitionRule = $connector2->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_CONNECTORS_TRANSITIONRULE));
		
		$this->assertNull($transitionRule->getOnePropertyValue(new core_kernel_classes_Property(PROPERTY_TRANSITIONRULES_THEN)));
		$this->assertNull($transitionRule->getOnePropertyValue(new core_kernel_classes_Property(PROPERTY_TRANSITIONRULES_ELSE)));
		$this->assertTrue($this->apiModel->getSubject(RDFS_LABEL, '2ndActivityForUnitTest_c_c')->isEmpty());
		
		$activity1->delete();
		$connector1->delete();
		$activity2->delete();
		$connector2->delete();
		$transitionRule->delete();
		$then->delete();
		$else->delete();
	}
	
	public function testCreateJoinActivity(){
		$parallelActivity1 = $this->authoringService->createActivity($this->proc, 'myActivity1');
		$connector1 = $this->authoringService->createConnector($parallelActivity1);
		
		$parallelActivity2 = $this->authoringService->createActivity($this->proc, 'myActivity2');
		$connector2 = $this->authoringService->createConnector($parallelActivity2);
		
		$joinActivity = $this->authoringService->createActivity($this->proc, 'joinActivity');
		
		//join parallel Activity 1 and 2 to "joinActivity"
		$this->assertIsA($this->authoringService->createJoinActivity($connector1, $joinActivity), 'core_kernel_classes_Resource');
		$this->authoringService->createJoinActivity($connector2, $joinActivity);
		
		//both connectors joined to the same activity have the same transition rule?
		$propTransitionRule = new core_kernel_classes_Property(PROPERTY_CONNECTORS_TRANSITIONRULE);
		$transitionRule1 = $connector1->getUniquePropertyValue($propTransitionRule);
		$this->assertIsA($transitionRule1, 'core_kernel_classes_Resource');
		$transitionRule2 = $connector2->getUniquePropertyValue($propTransitionRule);
		$this->assertEqual($transitionRule1->uriResource, $transitionRule2->uriResource);
		
		//same activity in 'then' property?
		$propThen = new core_kernel_classes_Property(PROPERTY_TRANSITIONRULES_THEN);
		$this->assertEqual($transitionRule1->getUniquePropertyValue($propThen)->uriResource, $joinActivity->uriResource);
		
		//test update of the joined activity after a connector has been disonnected from it:
		$oldConditonIf = $transitionRule1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_RULE_IF));
		$connector2->removePropertyValues(new core_kernel_classes_Property(PROPERTY_CONNECTORS_NEXTACTIVITIES));
		$this->authoringService->updateJoinedActivity($joinActivity);
		
		//the condition of transition rule of the connector 1 has been modified?
		$newConditionIf = $transitionRule1->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_RULE_IF));
		$this->assertNotEqual($oldConditonIf->uriResource, $newConditionIf->uriResource);
		
		
		$parallelActivity1->delete();
		$connector1->delete();
		$parallelActivity2->delete();
		$connector2->delete();
		$transitionRule1->delete();//TODO test all delete methods:
	}
	
	
	public function tearDown() {
        $this->proc->delete();
    }

}
?>