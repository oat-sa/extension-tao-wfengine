<?php
class wfEngine_actions_Processes extends wfEngine_actions_WfModule
{
	public function authoring($processDefinitionUri)
	{
		// This action is not available when running
		// the service mode !

			$processDefinitionUri = urldecode($processDefinitionUri);
				
			$userViewData 		= UsersHelper::buildCurrentUserForView();
			$this->setData('userViewData',$userViewData);
			$process 			= new wfEngine_models_classes_Process(urldecode($processDefinitionUri));
		
			$processAuthoringData 	= array();
			$processAuthoringData['processUri'] 	= $processDefinitionUri;
			$processAuthoringData['processLabel']	= "Process' variables initialization";
			$processAuthoringData['variables']		= array();
				
			// Process variables retrieving.
			$variables = $process->getProcessVars();

			foreach ($variables as $key => $variable)
			{
				$name 			= $variable[0];
				$propertyKey	= $key;


				$processAuthoringData['variables'][] = array('name'		=> $name,															
															'key' => 	$key
														   	 );
			}

			$this->setData('processAuthoringData',$processAuthoringData);
			$this->setView('process_authoring.tpl');
	}

	public function add($posted)
	{
		ini_set('max_execution_time', 200);


			$processExecutionFactory = new wfEngine_models_classes_ProcessExecutionFactory();
			
						
			$processExecutionFactory->name = $posted["variables"][RDFS_LABEL];
			if(empty($processExecutionFactory->name)){
				$processExecutionFactory->name = "Process execution of ".urldecode($posted['executionOf']);
			}
			$processExecutionFactory->comment = 'Created ' . date(DATE_ISO8601);
			
			$processExecutionFactory->execution = urldecode($posted['executionOf']);
			
		
			
			$processExecutionFactory->variables = $posted["variables"];
			
			//inital tokens created, assign user input process variables to initial tokens
			$newProcessExecution = $processExecutionFactory->create();
			

			$newProcessExecution->feed();
				
			// We build the next url for view state. Two possibilities :
			// 1. We go back to the main.
			// 2. We begin the newly created process.
			$viewState = '';
			$processUri = urlencode($newProcessExecution->uri);
			$viewState = _url('index', 'processBrowser', null, array('processUri' => $processUri));
			$this->redirect($viewState);

	}
}
?>