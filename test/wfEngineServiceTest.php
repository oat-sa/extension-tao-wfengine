<?php
require_once dirname(__FILE__) . '/../../tao/test/TestRunner.php';
include_once dirname(__FILE__) . '/../includes/raw_start.php';

/**
 * Test the services of wfEngine
 * 
 * @author Somsack Sipasseuth, <taosupport@tudor.lu>
 * @package wfEngine
 * @subpackage test
 */
class wfEngineServiceTest extends UnitTestCase {
	
	/**
	 * CHANGE IT MANNUALLY to see step by step the output
	 * @var boolean
	 */
	protected $OUTPUT = true;
	
	/**
	 * @var wfEngine_models_classes_UserService
	 */
	protected $userService = null;
	
	/**
	 * @var core_kernel_classes_Resource
	 */
	protected $currentUser = null;
	
	/**
	 * initialize a test method
	 */
	public function setUp(){
		
		TestRunner::initTest();
		
		error_reporting(E_ALL);
		
		$this->userPassword = '123456';
			
		if(is_null($this->userService)){
			$this->userService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_UserService');
		}
		
	}
	
	public function tearDown() {
		
    }
	
	/**
	 * output messages
	 * @param string $message
	 * @param boolean $ln
	 * @return void
	 */
	protected function out($message, $ln = false){
		if($this->OUTPUT){
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
	
	protected function createUser($login){
		
		$returnValue = null;
		
		$userData = array(
			PROPERTY_USER_LOGIN		=> 	$login,
			PROPERTY_USER_PASSWORD	=>	md5($this->userPassword),
			PROPERTY_USER_DEFLG		=>	'EN'
		);
		
		$user = $this->userService->getOneUser($login);
		if(is_null($user)){
			$this->userService->saveUser(null, $userData, new core_kernel_classes_Resource(CLASS_ROLE_WORKFLOWUSERROLE));
			$returnValue = $this->userService->getOneUser($login);
		}else{
			$returnValue = $user;
		}
		
		if(is_null($returnValue)){
			throw new Exception('cannot get the user with login '.$login);
		}
		
		return $returnValue;
	}
	
	protected function changeUser($login){
		
		$returnValue = false;
		
		//Login another user to execute parallel branch
		core_kernel_users_Service::logout();
		$loginProperty = new core_kernel_classes_Property(PROPERTY_USER_LOGIN);
		if(!is_null($this->currentUser)){
			$this->out("logout ". $this->currentUser->getOnePropertyValue($loginProperty) . ' "' . $this->currentUser->uriResource . '"', true);
		}else{
			$this->out("logout ");
		}
		
		if($this->userService->loginUser($login, md5($this->userPassword))){
			$this->userService->connectCurrentUser();
			$this->currentUser = $this->userService->getCurrentUser();
			$returnValue = true;
			$this->out("new user logged in: ".$this->currentUser->getOnePropertyValue($loginProperty).' "'.$this->currentUser->uriResource.'"');
		}else{
			$this->fail("unable to login user $login<br>");
		}
		
		$activityExecutionService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ActivityExecutionService');
		$activityExecutionService->clearCache('wfEngine_models_classes_ActivityExecutionService::checkAcl');
		
		return $returnValue;
	}
	
	protected function checkAccessControl($activityExecution){
		
		$activityExecutionService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ActivityExecutionService');
		
		$aclMode = $activityExecutionService->getAclMode($activityExecution);
		$restricedRole = $activityExecutionService->getRestrictedRole($activityExecution);
		$restrictedTo = !is_null($restricedRole) ? $restricedRole : $activityExecutionService->getRestrictedUser($activityExecution);
		
		$this->assertNotNull($aclMode);
		$this->assertNotNull($restrictedTo);
		$this->out("ACL mode: {$aclMode->getLabel()}; restricted to {$restrictedTo->getLabel()}", true);
	}
	
}
?>