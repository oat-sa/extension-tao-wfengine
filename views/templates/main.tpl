<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $GLOBALS['lang']; ?>" lang="<?php echo $GLOBALS['lang']; ?>">
	<head>
		<title><?php echo __("PIAAC Background Questionnaire"); ?></title>
		<script type="text/javascript" src="../../js/jquery.js"/></script>
		<script type="text/javascript" src="../../js/wfEngine.js"/></script>
		<style media="screen">
			@import url(../../views/<?php echo $GLOBALS['dir_theme']; ?>css/main.css);
		</style>
	</head>
	
	<body>
		<div id="process_view"></div>
		
		<ul id="control">
			
			<li>
				<span id="uiLanguages" class="icon"><?php echo __("Languages"); ?> :</span> 
				<?php foreach ($uiLanguages as $lg): ?>
				<a class="language internalLink" href="../../index.php/preferences/switchUiLanguage?lang=<?php echo str_replace("EN_EN","EN",$lg); ?>"><?php echo strtoupper(substr($lg,3)); ?></a> 
				<?php endforeach; ?> <span class="separator" />
			</li>

			    
        	<li>
        		<span id="connecteduser" class="icon"><?php echo __("User Id."); ?> <span id="username"><?php echo $userViewData['username']; ?></span> </span><span class="separator" />
        	</li>
        	
        	<?php if (ENABLE_EXPORT_BUTTON): ?>
			<li>
         		<a id="export" class="action icon" href="#" onclick="document.body.style.cursor = 'wait';jQuery.get('../../../../../piaac/Exchange/Export/index.php','',function (data, textStatus) { alert('<?php echo __("All cases exported !");?>');document.body.style.cursor = 'default';});" ><?php echo __("Export"); ?></a> <span class="separator" />
         	</li>
         	<?php endif; ?>
         	
         	<li>
         		<a class="action icon" id="logout" href="../../index.php/authentication/logout"><?php echo __("Logout"); ?></a>
         	</li>
		</ul>
		
		<div id="content">
			<h1 id="welcome_message"><?php echo __("Welcome to PIAAC Interview System"); ?></h1>	
			<div id="business">
				<h2 class="section_title"><?php echo __("Active Interviews"); ?></h2>
			<table id="active_processes">
				<thead>
					<tr>
						<th><?php echo __("Status"); ?></th>
						<?php if (SHOW_PROCESS_TYPE_VIEW): ?>
						
						<th><?php echo __("Process type"); ?></th>
						<?php endif; ?>
						<th>CI_PersID</th>
						<th><?php echo __("Interview"); ?></th>
						<th><?php echo __("Start/Resume the case"); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($processViewData as $procData): ?>
					<tr>
						<td class="status"><img src="../../views/<?php echo GUIHelper::buildStatusImageURI($procData['status']); ?>"/></td>
						
						<?php if (SHOW_PROCESS_TYPE_VIEW): ?>
						<td class="type"><?php echo GUIHelper::sanitizeGenerisString($procData['type']); ?></td>
						<?php endif; ?>
						<td class="label"><?php echo GUIHelper::sanitizeGenerisString($procData['persid']); ?></td>
						<td class="label"><?php echo GUIHelper::sanitizeGenerisString($procData['label']); ?></td>
		
						<td class="join">
							<?php if ($procData['status'] != 'Finished'): ?>
								<?php foreach ($procData['activities'] as $activity): ?>
									<?php if ($activity['may_participate']): ?>
									<a href="../../index.php/processBrowser/index?processUri=<?php echo urlencode($procData['uri']); ?>"><?php echo $activity['label']; ?></a>
									<?php else: ?>
									<span></span>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php else: ?>
								<span><?php echo __("Finished Interview"); ?></span>
							<?php endif; ?>
						</td>
						<!--<td class="situation"><a href="#"><img onclick="openProcess('../../../WorkFlowEngine/index.php?do=processInstance&param1=<?php echo urlencode($procData['uri']); ?>')" src="../../views/<?php echo $GLOBALS['dir_theme']; ?>img/open_process_view.png"/></a></td>-->
					</tr>
					<?php endforeach;  ?>
				</tbody>
			</table>
			
			<!-- End of Active Processes -->
			<h2 class="section_title"><?php echo __("Initialize new Interview"); ?></h2>
			<input id="new_process" type="button" value="<?php echo __("New Interview") ?>" onclick="window.location.href='../../index.php/processes/authoring?processDefinitionUri=http%3A%2F%2F127.0.0.1%2Fmiddleware%2FInterview.rdf%23i1224080114089468400'"/>
			
			<h2 class="section_title"><?php echo __("My roles"); ?></h2>
			<ul id="roles">
				<?php foreach ($userViewData['roles'] as $role): ?>
					<li><?php echo $role['label']; ?></li>
				<?php endforeach; ?>
			</ul>
			<!-- End of Roles -->
			</div>
			
		</div>
		<!-- End of content -->
	</body>
</html>