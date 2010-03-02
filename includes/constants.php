<?php



define('NS_TAOQUAL',									'http://www.tao.lu/middleware/taoqual.rdf');



define('PROCESS_VARIABLES',								NS_TAOQUAL . '#118589204618246');
define('CURRENT_TOKEN',									NS_TAOQUAL . '#119011853150574');
define('STATUS',										NS_TAOQUAL . '#119011843917578');
define('STATUS_FINISHED',								NS_TAOQUAL . '#119011840560280');
define('EXECUTION_OF',									NS_TAOQUAL . '#119010459643422');
define('PREC_ACTIVITIES',								NS_TAOQUAL . '#118589245545368');
define('NEXT_ACTIVITIES',								NS_TAOQUAL . '#118589252058280');
define('USER_ROLE',										NS_TAOQUAL . '#119012169222836');
define('ACTIVITY_ROLE',									NS_TAOQUAL . '#118588925461850');
define('CLASS_WORKFLOW_USER',							NS_TAOQUAL . '#11859665003194');
define('PROPERTY_USER_ROLE', 							NS_TAOQUAL . '#121930698442742');
define('PROPERTY_USER_LOGIN',							NS_TAOQUAL . '#119012256329986');

define('PROCESS_ACTIVITIES',							NS_TAOQUAL . '#118735548956256');
define('CLASS_PROCESS',									NS_TAOQUAL . '#118588753722590');
define('CLASS_PROCESS_EXECUTIONS',						NS_TAOQUAL . '#119010455660544');
define('CLASS_CONSISTENCY_RULES',						NS_TAOQUAL . '#122206969324866');
define('ACTIVITIES_IS_INITIAL',							NS_TAOQUAL . '#119018447833116');
define('PROPERTY_PROCESS_VARIABLE',						NS_TAOQUAL . '#118589204618246');
define('PROPERTY_PROCESS_ACTIVITIES',					NS_TAOQUAL . '#118735548956256');
define('PROPERTY_ACTIVITIES_ISINITIAL',					NS_TAOQUAL . '#119018447833116');
define('PROPERTY_CONNECTORS_PRECACTIVITIES',			NS_TAOQUAL . '#118589245545368');
define('PROPERTY_ACTIVITIES_ISERVICES',					NS_TAOQUAL . '#118588789618848');
define('PROPERTY_CONENCTORS_NEXTACTIVITIES',			NS_TAOQUAL . '#118589252058280');
define('PROPERTY_CONENCTORS_TYPEOF',					NS_TAOQUAL . '#118595164231830');
define('PROPERTY_PINSTANCES_STATUS',					NS_TAOQUAL . '#119011838314196');
define('PROPERTY_PINSTANCES_EXECUTIONOF',				NS_TAOQUAL . '#119010459643422');
define('PROPERTY_PINSTANCES_TOKEN',						NS_TAOQUAL . '#119011853150574');
define('PROPERTY_PINSTANCES_PROCESSPATH',				NS_TAOQUAL . '#processPath');
define('PROPERTY_PINSTANCES_FULLPROCESSPATH',			NS_TAOQUAL . '#processFullPath');

define('PROPERTY_ACTIVITIES_HYPERCLASSES',				NS_TAOQUAL . '#12193017041224');
define('PROPERTY_ACTIVITIES_INFERENCERULE',				NS_TAOQUAL . '#activityInferenceRule');
define('PROPERTY_ACTIVITIES_ONBEFOREINFERENCERULE',		NS_TAOQUAL . '#activityOnBeforeInferenceRule');
define('PROPERTY_CALLOFSERVICES_SERVICEDEFINITION',		NS_TAOQUAL . '#11859509039346');
define('PROPERTY_CALLOFSERVICES_ACTUALPARAMETERIN',		NS_TAOQUAL . '#118595099928140');
define('PROPERTY_CALLOFSERVICES_ACTUALPARAMETEROUT',	NS_TAOQUAL . '#118596586150000');
define('PROPERTY_SERVICEDEFINITIONS_URL',				NS_TAOQUAL . '#11858886911216');
define('PROPERTY_ACTUALPARAMETER_PROCESSVARIABLE',		NS_TAOQUAL . '#11858901499008');
define('PROPERTY_ACTUALPARAMETER_QUALITYMETRIC',		NS_TAOQUAL . '#118589023027962');
define('PROPERTY_ACTUALPARAMETER_CONSTANTVALUE',		NS_TAOQUAL . '#1185890127346');
define('PROPERTY_ACTUALPARAMETER_FORMALPARAMETER',		NS_TAOQUAL . '#118588973457282');
define('PROPERTY_PROCESSINSTANCE_CURRENTPATHITEM',		NS_TAOQUAL . '#122182453333834');
define('PROPERTY_PATHITEM_ACTIVITY',					NS_TAOQUAL . '#122182427816968');
define('PROPERTY_PATHITEM_NEXTPATHITEM',				NS_TAOQUAL . '#122182434950916');
define('PROPERTY_CONNECTOR_TYPEOFCONNECTOR',			NS_TAOQUAL . '#118595164231830');
define('PROPERTY_CONNECTOR_TRANSITIONRULE',				NS_TAOQUAL . '#122207114241798');
define('PROPERTY_TRANSITIONRULES_THEN',					NS_TAOQUAL . '#122207070428322');
define('PROPERTY_TRANSITIONRULES_ELSE',					NS_TAOQUAL . '#122207096147834');
define('PROPERTY_RULE_THEN',							NS_TAOQUAL . '#122207070428322');
define('PROPERTY_RULE_ELSE',							NS_TAOQUAL . '#122207096147834');
define('PROPERTY_ACTIVITIES_ISHIDDEN',					NS_TAOQUAL . '#isHiddenActivity');
define('CLASS_CONSISTENCYRULES',						NS_TAOQUAL . '#122346531841048');
define('CLASS_CALLOFSERVICES',							NS_TAOQUAL . '#118595077025536');
define('CLASS_DEFINITIONOFSUPPORTSERVICES',				NS_TAOQUAL . '#118588779325312');
define('PROPERTY_CONSISTENCYRULES_INVOLVEDACTIVITIES',	NS_TAOQUAL . '#122346533932400');
define('PROPERTY_CONSISTENCYRULES_SUPPRESSABLE',		NS_TAOQUAL . '#122347196613578');
define('PROPERTY_ACTIVITIES_CONSISTENCYRULE',			NS_TAOQUAL . '#122346640532066');
define('CLASS_PATHITEM',								NS_TAOQUAL . '#122182410160922');
define('RESOURCE_PROCESSSTATUS_RESUMED',				NS_TAOQUAL . '#119011839432700');
define('RESOURCE_PROCESSSTATUS_STARTED',				NS_TAOQUAL . '#119011838314196');
define('RESOURCE_PROCESSSTATUS_FINISHED',				NS_TAOQUAL . '#119011840560280');
define('RESOURCE_PROCESSSTATUS_PAUSED',					NS_TAOQUAL . '#119011862341174');
define('CLASS_TRANSITIONRULES',							NS_TAOQUAL . '#122206969324866');
define('CLASS_CONNECTORS',								NS_TAOQUAL . '#118589215756172');
define('CLASS_ACTIVITIES',								NS_TAOQUAL . '#118588757437650');
define('CLASS_EXITCODE',								NS_TAOQUAL . '#exitCode');
define('CLASS_ACTIONCODE',								NS_TAOQUAL . '#actionCode');
define('PROPERTY_PROCESSINSTANCE_EXITCODE',				NS_TAOQUAL . '#procExitCode');
define('PROPERTY_PROCESSINSTANCE_ACTIONCODE',			NS_TAOQUAL . '#procActionCode');
define("CONNECTOR_SEQ",									NS_TAOQUAL . '#118589243226718');
define("CONNECTOR_SPLIT",								NS_TAOQUAL . '#118589220353990');
define("CONNECTOR_LIST",								NS_TAOQUAL . '#11858924147584');
define('CONNECTOR_LIST_UP',								NS_TAOQUAL . '#i1246279182028411900');

define("INTERVIEWER_ROLE",								NS_TAOQUAL.'#121930698442742');
define('CLASS_INFERENCERULES',							NS_TAOQUAL.'#inferenceRule');
define('PROPERTY_INFERENCERULES_THEN',					NS_TAOQUAL.'#inferenceRuleThen');
define('PROPERTY_INFERENCERULES_ELSE',					NS_TAOQUAL.'#inferenceRuleElse');
define('PROPERTY_ACTIVITIES_DISPLAYCALENDAR',			NS_TAOQUAL.'#123383820311354');
define('PROPERTY_ACTIVITIES_LIST',						NS_TAOQUAL.'#124326462812324');
define('PROPERTY_ACTIVITIES_SELECTOR',					NS_TAOQUAL.'#124299860710746');

define('PROPERTY_ACTIVITIES_LIST_PARENT',				NS_TAOQUAL.'#124748211635858');



define('RESOURCE_ACTIVITIES_SELECTOR_SEQ',				NS_TAOQUAL.'#12429977703072');
define('RESOURCE_ACTIVITIES_SELECTOR_RAND',				NS_TAOQUAL.'#124299784247410');
define('RESOURCE_ACTIVITIES_SELECTOR_RAND_1',			NS_TAOQUAL.'#124653558221396');

define('RESOURCE_ACTIVITIES_SELECTOR_DICO',				NS_TAOQUAL.'#124299788641244');

define('CLASS_ACTIVITIES_LIST',							NS_TAOQUAL.'#12429950482224');
define('CLASS_ACTIVITIES_LIST_EXECUTION',				NS_TAOQUAL.'#12445408585808');

define('CURRENT_TOKEN_EXECUTION',						NS_TAOQUAL.'#124454126218994');
define('ACTIVITY_EXECUTION_HISTORY',					NS_TAOQUAL.'#124653558221396');
define('PROPERTY_ACTIVITIES_LIST_EXECUTION_UP',			NS_TAOQUAL.'#124705207723679');
define('PROPERTY_ACTIVITIES_LIST_EXECUTION_ISFINISHED',	NS_TAOQUAL.'#124705207723677');

define('PROPERTY_CODE', 								NS_TAOQUAL . '#code');

define('PROPERTY_REMAINING_ACTIVITIES',					NS_TAOQUAL.'#124705207723678');
define('PROPERTY_REMAINING_ACTIVITIES_ARRAY',			NS_TAOQUAL.'#124705207523877');
define('PROPERTY_FINISHED_ACTIVITIES',					NS_TAOQUAL.'#12525951541482');



$todefine = array(
	// 'CLASS_PROCESS' => NS_TAOQUAL . '#118588753722590',
	// 'CLASS_CALLOFSERVICES' => NS_TAOQUAL . '#118595077025536',
	// 'CLASS_TRANSITIONRULES' => NS_TAOQUAL . '#122206969324866',
	// 'CLASS_CONNECTORS' => NS_TAOQUAL . '#118589215756172',
	// 'CLASS_ACTIVITIES' => NS_TAOQUAL . '#118588757437650',
	'CLASS_SERVICESDEFINITION' => NS_TAOQUAL . '#118588759532084',
	'CLASS_ROLE' => NS_TAOQUAL . '#118588820437156',
	'CLASS_FORMALPARAMETER' => NS_TAOQUAL . '#118588904546812',
	'CLASS_WEBSERVICES' => NS_TAOQUAL . '#118588763446870',
	'CLASS_SUPPORTSERVICES' => NS_TAOQUAL . '#118588779325312',
	'CLASS_ACTUALPARAMETER' =>  NS_TAOQUAL . '#118588960462136',
	'CLASS_PROCESSVARIABLES' => NS_TAOQUAL . '#118589004639950',
	'CLASS_PROCESSINSTANCE' => NS_TAOQUAL . '#119010455660544',
	// 'PROPERTY_PROCESS_ACTIVITIES' => NS_TAOQUAL . '#118735548956256',
	// 'PROPERTY_CALLOFSERVICES_SERVICEDEFINITION' => NS_TAOQUAL . '#11859509039346',
	'PROPERTY_CALLOFSERVICES_ACTUALPARAMOUT' => NS_TAOQUAL . '#118596586150000',//Used for saving the param value
	'PROPERTY_CALLOFSERVICES_ACTUALPARAMIN' => NS_TAOQUAL . '#118595099928140',//Used for saving the param value
	'PROPERTY_SERVICESDEFINITION_FORMALPARAMOUT' => NS_TAOQUAL . '#118588897651172',
	'PROPERTY_SERVICESDEFINITION_FORMALPARAMIN' => NS_TAOQUAL . '#118588892919658',
	'PROPERTY_ACTUALPARAM_FORMALPARAM' => NS_TAOQUAL . '#118588973457282',
	'PROPERTY_ACTUALPARAM_CONSTANTVALUE' => NS_TAOQUAL . '#1185890127346',
	'PROPERTY_ACTUALPARAM_PROCESSVARIABLE' => NS_TAOQUAL . '#11858901499008',
	'PROPERTY_ACTUALPARAM_QUALITYMETRIC' => NS_TAOQUAL . '#118589023027962',
	'PROPERTY_FORMALPARAM_DEFAULTVALUE' => NS_TAOQUAL . '#118588964565322',
	'PROPERTY_CONNECTORS_TYPE' => NS_TAOQUAL . '#118595164231830',
	'PROPERTY_CONNECTORS_TRANSITIONRULE' => NS_TAOQUAL . '#122207114241798',
	// 'PROPERTY_CONNECTORS_PRECACTIVITIES' => NS_TAOQUAL . '#118589245545368',
	'PROPERTY_CONNECTORS_NEXTACTIVITIES' => NS_TAOQUAL . '#118589252058280',
	'PROPERTY_ACTIVITIES_INTERACTIVESERVICES' => NS_TAOQUAL . '#118588789618848',
	// 'PROPERTY_ACTIVITIES_CONSISTENCYRULE' => NS_TAOQUAL . '#122346640532066',
	'PROPERTY_ACTIVITIES_ONAFTERINFERENCERULE' => NS_TAOQUAL . '#activityInferenceRule',
	// 'PROPERTY_ACTIVITIES_ONBEFOREINFERENCERULE' => NS_TAOQUAL . 'activityOnBeforeInferenceRule',
	'PROPERTY_CONNECTORS_ACTIVITYREFERENCE' => NS_TAOQUAL . '#activityReference',
	'CLASS_TYPEOFCONNECTORS' => NS_TAOQUAL . '#118589088163970',
	// 'PROPERTY_TRANSITIONRULES_THEN' => NS_TAOQUAL . '#122207070428322',
	// 'PROPERTY_TRANSITIONRULES_ELSE' => NS_TAOQUAL . '#122207096147834',
	// 'PROPERTY_CODE' => NS_TAOQUAL . '#code',
	'INSTANCE_TYPEOFCONNECTORS_SPLIT' => NS_TAOQUAL . '#118589220353990',
	'INSTANCE_TYPEOFCONNECTORS_SEQUENCE' => NS_TAOQUAL . '#118589243226718',
	'PROPERTY_SUPPORTSERVICES_URL' => NS_TAOQUAL .'#11858886911216'
);

foreach($todefine as $constName => $constValue){
	if(!defined($constName)){
		define($constName, $constValue);
	}
}
unset($todefine);
?>