<?php
class ProcessBrowser extends Module
{
	public function index($processUri)
	{
		UsersHelper::checkAuthentication();


		$processUri 		= urldecode($processUri); // parameters clean-up.
		$userViewData 		= UsersHelper::buildCurrentUserForView(); // user data for browser view.
		$browserViewData 	= array(); // general data for browser view.

		$process 			= new ProcessExecution($processUri);
		$activity 			= $process->currentActivity[0];
		$activityPerf 		= new Activity($activity->uri, false); // Performance WA
		$activityExecution 	= new ActivityExecution($process, $activity);

		$browserViewData['activityContentLanguages'] = array();

		// If paused, resume it.
		if ($process->status == 'Paused')
		$process->resume();

		// Browser view main data.
		$browserViewData['isHyperView']				= false;
		$browserViewData['isInteractiveService']	= false;
		$browserViewData['processLabel'] 			= $process->process->label;
		$browserViewData['processExecutionLabel']	= $process->label;
		$browserViewData['activityLabel'] 			= $activity->label;
		$browserViewData['isBackable']				= (FlowHelper::isProcessBackable($process) and !(isPiaacNotBackableItemList($activity->uri)));
		$browserViewData['uiLanguage']				= $GLOBALS['lang'];
		$browserViewData['contentlanguage']			= $_SESSION['taoqual.serviceContentLang'];
		$browserViewData['processUri']				= $processUri ;

		$browserViewData['uiLanguages']				= I18nUtil::getAvailableLanguages();
		$browserViewData['activityContentLanguages'] = I18nUtil::getAvailableServiceContentLanguages();

		$browserViewData['showCalendar']			= $activityPerf->showCalendar;

		// process variables data.
		$variablesViewData = array();
		$variables = $process->getVariables();

		foreach ($process->getVariables() as $var)
		{
			$variablesViewData[$var->name] = array('uri' 	=> $var->uri,
												   'value' 	=> $var->value);
		}

		// consistency data.
		$consistencyViewData = array();
		if (isset($_SESSION['taoqual.flashvar.consistency']))
		{
			$consistencyException 		= $_SESSION['taoqual.flashvar.consistency'];
			$involvedActivities 		= $consistencyException['involvedActivities'];

			$consistencyViewData['isConsistent']		= false;
			$consistencyViewData['suppressable']		= $consistencyException['suppressable'];
			$consistencyViewData['notification']		= str_replace(array("\r", "\n"), '', $consistencyException['notification']);
			$consistencyViewData['processExecutionUri'] = urlencode($processUri);
			$consistencyViewData['activityUri']			= urlencode($activity->uri);
			$consistencyViewData['source']				= $consistencyException['source'];

			$consistencyViewData['involvedActivities']	= array();

			foreach ($involvedActivities as $involvedActivity)
			{
				$consistencyViewData['involvedActivities'][] = array('uri' => $involvedActivity['uri'],
																	 'label' => $involvedActivity['label'],
																	 'processUri' => $processUri);
			}

			// Clean flash variables.
			$_SESSION['taoqual.flashvar.consistency'] = null;
		}
		else
		{
			// Everything is allright with data consistency for this process.
			$consistencyViewData['isConsistent'] = true;
			$_SESSION['taoqual.flashvar.consistency'] = null;
		}



		//The following takes about 0.2 seconds -->cache

		//retrieve activities

		if (!($qSortedActivities = common_Cache::getCache("aprocess_activities")))
		{

			$processDefinition = new core_kernel_classes_resource($process->process->uri);
			$activities = $processDefinition->getPropertyValues(new core_kernel_classes_Property(PROPERTY_PROCESS_ACTIVITIES));

			//sort the activities
			$qSortedActivities =array();
			foreach ($activities as $key=>$val)
			{
				$activity_res = new core_kernel_classes_resource($val);
				$label = $activity_res->label;
				$qSortedActivities[$label] = $val;

			}
			ksort($qSortedActivities);
			common_Cache::setCache($qSortedActivities,"aprocess_activities");
		}

		$browserViewData['annotationsResourcesJsArray'] = array();
		foreach ($qSortedActivities as $key=>$val)
		{
			$browserViewData['annotationsResourcesJsArray'][]= array($val,$key);
		}

		$browserViewData['active_Resource']="'".$activity->uri."'" ;
		$this->setData($browserViewData);
		$this->setView('process_browser.tpl');




	}

	public function back($processUri)
	{
		UsersHelper::checkAuthentication();

		$processUri 	= urldecode($processUri);
		$processExecution = new ProcessExecution($processUri);
		$activity = $processExecution->currentActivity[0];
		$processExecution->performBackwardTransition($activity);
		$processUri 	 = urlencode($processUri);

		if (!ENABLE_HTTP_REDIRECT_PROCESS_BROWSER)
		{
			$this->index($processUri);
		}
		else
		{
			$processUri = urlencode($processUri);
			GenerisFC::redirection("processBrowser/index?processUri=${processUri}");
	}
}

public function next($processUri, $ignoreConsistency = 'false')
{
	UsersHelper::checkAuthentication();

	PiaacDataHolder::build($processUri);

	$processUri 	= urldecode($processUri);
	$processExecution = new ProcessExecution($processUri);

	try
	{
		$processExecution->performTransition(($ignoreConsistency == 'true') ? true : false);

		if (!$processExecution->isFinished())
		{
			$processUri = urlencode($processUri);

			if (!ENABLE_HTTP_REDIRECT_PROCESS_BROWSER)
			$this->index($processUri);
			else
			{
				$processUri = urlencode($processUri);
				GenerisFC::redirection("processBrowser/index?processUri=${processUri}");
		}
	}
	else
	{
		if (defined('PIAAC_ENABLED') && SERVICE_MODE && USE_CALLBACK_URL_ON_PROCESS_FINISHED)
		{
			header('Location: ' . CALLBACK_URL_ON_PROCESS_FINISHED);
		}
		else
		{
			GenerisFC::redirection('main/index');
		}
	}
}
catch (ConsistencyException $consistencyException)
{
	// A consistency error occured when trying to go
	// forward in the process. Let's try to get useful
	// information from the exception.

	// We need to tell the "index" action of the "ProcessBrowser" controller
	// that a consistency exception occured. To do so, we will use the concept
	// of flash variable. This kind of variable survives during one and only one
	// HTTP request lifecycle. So that in the "index" action, the session variable
	// depicting the error will be systematically erased after each processing.
	//$_SESSION['taoqual.flashvar.consistency'] = $consistencyException;
	$consistency = ConsistencyHelper::BuildConsistencyStructure($consistencyException);
	$_SESSION['taoqual.flashvar.consistency'] = $consistency;

	$processUri = urlencode($processUri);
	GenerisFC::redirection("processBrowser/index?processUri=${processUri}");
		}
	}

	public static function pause($processUri)
	{
		UsersHelper::checkAuthentication();

		$processUri 	= urldecode($processUri);
		$processExecution = new ProcessExecution($processUri);

		$processExecution->pause();

		GenerisFC::redirection((FORCE_PAUSE_LOGOUT) ? 'authentication/logout' : 'main/index');
	}

	public function jumpBack($processUri, $activityUri, $testing="",$ignoreHidden=false)
	{
		UsersHelper::checkAuthentication();

		$processUri = urldecode($processUri);
		$activityUri = urldecode($activityUri);

		$processExecution = new ProcessExecution($processUri);
		$newActivity = new Activity($activityUri);
		$processExecution->jumpBack(new Activity($activityUri), $testing);

		if ($ignoreHidden == true)
		{
			$newActivity->feedFlow(1);
			if ($newActivity->isHidden)
			{
				$this->next(urlencode($processUri));
				die();
			}
		}


		$processUri = urlencode($processUri);
		GenerisFC::redirection("processBrowser/index?processUri=${processUri}");
	}

	public function breakOff($processUri)
	{
		UsersHelper::checkAuthentication();
		PiaacDataHolder::build($processUri);

		$processUri = urldecode($processUri);
		$process = new ProcessExecution($processUri);
		$activityUri = $process->currentActivity[0]->uri;

		//returns uri of activity to jump to if the user want to break off
		$endingActivityUri = getBreakOffEndingActivityUri($activityUri);

		$this->jumpBack($processUri, $endingActivityUri, '', true);
	}

	public function jumpLast($processUri)
	{
		UsersHelper::checkAuthentication();
		PiaacDataHolder::build($processUri);

		$processUri = urldecode($processUri);
		$processExecution = new ProcessExecution($processUri);

		try
		{
			$processExecution->performTransitionToLast();
			$this->index($processUri);
		}
		catch (ConsistencyException $e)
		{
			$consistency = ConsistencyHelper::BuildConsistencyStructure($e);
			$_SESSION['taoqual.flashvar.consistency'] = $consistency;

			$processUri = urlencode($processUri);
			GenerisFC::redirection("processBrowser/index?processUri=${processUri}");
	}
	}
}
?>
