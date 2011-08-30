<?php
class wfEngine_actions_Main extends wfEngine_actions_WfModule
{


	/**
	 * 
	 * Main page of wfEngine containning 2 sections : 
	 *  - Processes Execution in progress or just started
	 *  - Processes Definition user may instanciate
	 * 
	 * @return void
	 */
	public function index()
	{

		$wfEngineService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_WfEngineService');
		
		$userViewData = UsersHelper::buildCurrentUserForView();
		$this->setData('userViewData',$userViewData);
		
		//list of available process executions:
		$processes = $wfEngineService->getProcessExecutions();
		
		$activityExecutionService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ActivityExecutionService');
		$userService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_UserService');
		$currentUser = $userService->getCurrentUser();
		


		$processViewData 	= array();
		foreach ($processes as $proc){
	
			$type 	= $proc->process->resource->getLabel();
			$label 	= $proc->resource->getLabel();
			$uri 	= $proc->uri;
			$status = $proc->status;
			$persid	= "-";
						
			$activityIsInitialProp = new core_kernel_classes_Property(PROPERTY_ACTIVITIES_ISINITIAL);
	
			$currentActivities = array();

			foreach ($proc->currentActivity as $currentActivity)
			{
				$activity = $currentActivity;
				
				
				$isAllowed = $activityExecutionService->checkAcl($activity->resource, $currentUser, $proc->resource);
				$isFinished = false;
				$execution = $activityExecutionService->getExecution($activity->resource, $currentUser, $proc->resource);
				if(!is_null($execution)){
					$aExecution = new wfEngine_models_classes_ActivityExecution($proc, $execution);
					$isFinished = $aExecution->isFinished();
				}

				$currentActivities[] = array(
					'label'				=> $currentActivity->resource->getLabel(),
					'uri' 				=> $currentActivity->uri,
					'may_participate'	=> (!$proc->isFinished() && $isAllowed),
					'finished'			=> $proc->isFinished(),
					'allowed'			=> $isAllowed,
					'activityEnded'		=> $isFinished
				);
			}
			
			$processViewData[] = array(
				'type' 			=> $type,
		  	   	'label' 		=> $label,
			   	'uri' 			=> $uri,
				'persid'		=> $persid,
		   	  	'activities'	=> $currentActivities,
			   	'status'		=> $status
			);
	
		}
		$processClass = new core_kernel_classes_Class(CLASS_PROCESS);
		
		//list of available process definitions:
		$availableProcessDefinitions = $processClass->getInstances();
		
		//filter process that can be initialized by the current user:
		$processExecutionService = tao_models_classes_ServiceFactory::get('wfEngine_models_classes_ProcessExecutionService');
		$authorizedProcessDefinitions = array();
		foreach($availableProcessDefinitions as $processDefinition){
			$allowed = $processExecutionService->checkAcl($processDefinition, $currentUser);
			if($allowed){
				$authorizedProcessDefinitions[] = $processDefinition;
			}
		}
		
		$this->setData('availableProcessDefinition',$authorizedProcessDefinitions);
		$this->setData('processViewData',$processViewData);
		$this->setView('main.tpl');
	
	}

}
?>