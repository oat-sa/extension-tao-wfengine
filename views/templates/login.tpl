<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $GLOBALS['lang']; ?>" lang="<?php echo $GLOBALS['lang']; ?>">
	<head>
		<title><?php echo PROCESS_BROWSER_TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="<?=TAOBASE_WWW?>/css/layout.css" />
		<link rel="stylesheet" type="text/css" href="<?=TAOBASE_WWW?>/css/form.css" />
		<link rel="stylesheet" type="text/css" href="<?=TAOBASE_WWW?>/css/custom-theme/jquery-ui-1.8.custom.css" />
		<style media="screen">
			@import url(<?echo BASE_WWW; ?>/css/login.css);
		</style>
		
		<script type="text/javascript" src="<?=BASE_WWW?>js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="<?=BASE_WWW ?>js/login.js"></script>
	</head>
	
	<body>
		<ul id="control">    
			<li></li>
		</ul>
		<div id="content" class='ui-corner-bottom'>
			<h1 id="welcome_message"><img src="<?=BASE_WWW?>/img/wf_engine_logo.png" /><?= __("TAO Process Engine"); ?></h1>
			<div id="business">
				<div id="login-box">
					<?if(get_data('errorMessage')):?>
						<div class="ui-widget ui-corner-all ui-state-error error-message">
							<?=urldecode(get_data('errorMessage'))?>
						</div>
						<br />
					<?endif?>
					<div id="login-title" class="ui-widget ui-widget-header ui-state-default ui-corner-top">
						<?=__("Please login")?>
					</div>
					<div id="login-form" class="ui-widget ui-widget-content ui-corner-bottom">
						<?=get_data('form')?>
					</div>
				</div>
				                <div class="ui-state-highlight ui-corner-all" style="width:500px; margin: 50px auto 20px auto; padding:5px;text-align:center; font-weight:bold;">
                        <img src="<?=BASE_WWW?>img/warning.png" alt="!" /><h2>Alpha version</h2>
                        Please report bugs, ideas, comments, any feedback on the <a href="http://forge.tao.lu" target="_blank">TAO Forge</a><br /><br />
                        
                </div>

			</div>
		</div>
		<div id="footer">
			TAO<sup>&reg;</sup> - <?=date('Y')?> - A joint initiative of CRP Henri Tudor and the University of Luxembourg
		</div>
	</body>
</html>
