<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php 
		Yii::app()->bootstrap->bootstrapPath = Yii::app()->basePath.'/../../bootstrap-3.3.7-dist';
		Yii::app()->bootstrap->adminLtePath = Yii::app()->basePath.'/../../AdminLTE-2.3.7';
		Yii::app()->bootstrap->register(); 

		$sfile = Yii::app()->baseUrl.'/js/dms.js';
		Yii::app()->clientScript->registerScriptFile($sfile,CClientScript::POS_HEAD);
	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="language" content="<?php echo Yii::app()->language; ?>" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
<?php 
$rights = array('CN04',);
$grant = array();
foreach ($rights as $right) {
	if (Yii::app()->user->validFunction($right)) $grant[] = Yii::app()->params['systemId'].'_'.$right;
}
?>
	<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
	<script>
		var OneSignal = window.OneSignal || [];
		var useragentid = '';
		OneSignal.push(function() {
			OneSignal.init({
				appId: <?php echo '"'.Yii::app()->params['onesignal'].'"'; ?>,
				autoRegister: false,
				notifyButton: {
					enable: false 
				},
				persistNotification: false
			});
		});
		OneSignal.push(function() {
			OneSignal.sendTags({
					userId: <?php echo "'".Yii::app()->user->id."'"; ?>,
					<?php 
						foreach ($rights as $right) {
							if (Yii::app()->user->validFunction($right)) 
								echo Yii::app()->params['systemId'].'_'.$right.": 'Y',";
							else
								echo Yii::app()->params['systemId'].'_'.$right.": '',";
						}
					?>
			});
		});
		OneSignal.push(function() {
			OneSignal.getUserId().then(function(userId) {                
				if (userId == null){
					<?php echo !empty($grant) ? 'OneSignal.registerForPushNotifications({modalPrompt: true});' : 'var x = true;'; ?>
				} else {
					useragentid = userId;
				}
			});
		});
	</script>
</head>

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<header class="main-header">
		<nav class="navbar navbar-static-top">
			<div class="container">
				<div class="navbar-header">
					<a href="<?php echo Yii::app()->baseUrl; ?>" class="navbar-brand">
						<b><?php echo CHtml::encode(Yii::t('app',Yii::app()->name)); ?></b> <small><?php echo '('.Yii::app()->user->city_name().')'; ?></small>
					</a>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
						<i class="fa fa-bars"></i>
					</button>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
				<?php
					$sysId = Yii::app()->session['system'];
					$sysTitle = Yii::app()->params['systemMapping'][$sysId]['name'];
					$sysIcon = Yii::app()->params['systemMapping'][$sysId]['icon'];
					echo "<button id='btnSysChange' type='button' 
						class='btn btn-default navbar-btn navbar-left' data-toggle='tooltip' data-placement='bottom' title='".Yii::t('app','System Change')."'>"
						.Yii::t('app',$sysTitle)."</button>";
					$this->widget('ext.layout.MenuWidget', array('config'=>Yii::app()->basePath.'/config/menu.php',));
				?>
				</div>
				<!-- /.navbar-collapse -->
				<!-- Navbar Right Menu -->
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
				<?php
					$this->widget('ext.layout.UserMenuWidget');
				?>
					</ul>
				</div>
			</div>
			<!-- /.container-fluid -->
		</nav>
	</header>

	<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
	
	<!-- Full Width Column -->
	<div class="content-wrapper">
		<div class="container">
			<?php echo $content; ?>
		</div>
		<!-- /.container -->
	</div>
	<!-- /.content-wrapper -->

	<?php $this->renderPartial('//site/dialog'); ?>
	<?php $this->renderPartial('//site/system'); ?>

	<footer class="main-footer">
		<div class="container">
			<div class="pull-right hidden-xs">
				<b><?php echo Yii::t('app',$sysTitle);?></b> <b>Version</b> <?php echo Yii::app()->params['version'];?>
			</div>
			<strong>Copyright &copy; 2016-2017 <a href="http://www.lbsgroup.com.hk">LBS Group</a>.</strong> <?php echo Yii::t('misc', 'All rights reserved'); ?>
		</div>
		<!-- /.container -->
	</footer>
</div>
<!-- ./wrapper -->

</body>
<?php
if (!Yii::app()->user->isGuest) {
	$checkurl = Yii::app()->createUrl("ajax/checksession");
	$loginurl = Yii::app()->createUrl("site/logout");
	$js = <<<EOF
var checkLogin = function() {
    $.ajax({
		type: 'GET', 
		url: '$checkurl',
		dataType: 'json', 
		success: function(json) {
			var x = json;
			var data = json;
			if (!data.loggedin) {
				clearInterval(logincheckinterval);
				window.location = '$loginurl';
			}
		},
		error: function(xhr, status, error) {
			skip = 1;
		}
	});
};
var logincheckinterval = setInterval(checkLogin, 30000);
EOF;
	Yii::app()->clientScript->registerScript('checksession',$js,CClientScript::POS_READY);
	$js = <<<EOF
$(function () {
  $('[data-toggle=\"tooltip\"]').tooltip()
});

$('#btnSysChange').on('click',function() {
	$('#syschangedialog').modal('show');
});
EOF;
	$incl_js = false;
	foreach (Yii::app()->params['systemMapping'] as $id=>$value) {
		if (Yii::app()->user->validSystem($id)) {
			$oid = 'btnSys'.$id;
			$url = $value['webroot'];
			if (!isset($value['script'])) {
				$temp = '$("#'.$oid.'").on("click",function(){$("#syschangedialog").modal("hide");window.location="'.$url.'";});';
			} else {
				$func_name = $value['script'];
				$lang = Yii::app()->language;
				$homeurl = Yii::app()->createUrl("");
				$incl_js = true;
				$temp = '$("#'.$oid.'").on("click",function(){$("#syschangedialog").modal("hide");'.$func_name.'("'.$id.'","'.$url.'","'.$homeurl.'");});';
			}
			$js .= $temp;
		}
	}
	
	if ($incl_js) {
		$sfile = Yii::app()->baseUrl.'/js/systemlink.js';
		Yii::app()->clientScript->registerScriptFile($sfile,CClientScript::POS_HEAD);
	}
	Yii::app()->clientScript->registerScript('systemchange',$js,CClientScript::POS_READY);
}
?>
</script>
</html>
