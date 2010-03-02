<div class="ui-widget-content ui-corner-bottom">
	
	<div id="tree-activity" ></div>
	
</div>
			
<script type="text/javascript">
	$(function(){
		initActivityTree();
	});
	
	function initActivityTree(){
		new ActivityTreeClass('#tree-activity', authoringControllerPath+"getActivities", {
			processUri: processUri,
			formContainer: "#activity_form",
			createActivityAction: authoringControllerPath+"addActivity",
			createInteractiveServiceAction: authoringControllerPath+"addInteractiveService",
			editInteractiveServiceAction: authoringControllerPath+"editCallOfService",
			editActivityPropertyAction: authoringControllerPath+"editActivityProperty",
			editConnectorAction: authoringControllerPath+"editConnector",
			deleteConnectorAction: authoringControllerPath+"deleteConnector",
			deleteActivityAction: authoringControllerPath+"deleteActivity",
			deleteInteractiveServiceAction: authoringControllerPath+"deleteCallOfService"
		});
	}
	
	function refreshActivityTree(){
		$.tree.reference('#tree-activity').refresh();
		$.tree.reference('#tree-activity').reselect();
	}
	
	function reselectActivityTree(){
		$.tree.reference('#tree-activity').reselect();
	}
	
</script>