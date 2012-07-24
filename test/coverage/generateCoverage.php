<?php
require_once dirname(__FILE__) . '/../../../tao/test/TestRunner.php';

//get the test into each extensions
$tests = TestRunner::getTests(array('taoSubjects'));

//create the test sutie
$testSuite = new TestSuite('TAO Subject unit tests');
foreach($tests as $testCase){
	$testSuite->addFile($testCase);
}    

//add the reporter regarding the context
if(PHP_SAPI == 'cli'){
	$reporter = new XmlTimeReporter();
}
else{
	$reporter = new HtmlReporter();
}
require_once  PHPCOVERAGE_HOME. "/CoverageRecorder.php";
require_once PHPCOVERAGE_HOME . "/reporter/HtmlCoverageReporter.php";
//run the unit test suite
$includePaths = array(ROOT_PATH.'wfEngine/models',ROOT_PATH.'wfEngine/helpers');

$excludePaths = array();
$covReporter = new HtmlCoverageReporter("Code Coverage Report wfEngine", "", PHPCOVERAGE_REPORTS."/wfEngine");
$cov = new CoverageRecorder($includePaths, $excludePaths, $covReporter);
//run the unit test suite
$cov->startInstrumentation();
$testSuite->run($reporter);
$cov->stopInstrumentation();
$cov->generateReport();
$covReporter->printTextSummary(PHPCOVERAGE_REPORTS.'/wfEngine_coverage.txt');
?>