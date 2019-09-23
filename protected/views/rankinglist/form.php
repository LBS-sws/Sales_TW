<?php
$this->pageTitle=Yii::app()->name . ' - Month Report';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'monthly-list',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true,),
'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('report','Sales ranking list'); ?></strong>
	</h1>
<!--
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Layout</a></li>
		<li class="active">Top Navigation</li>
	</ol>
-->
</section>
<div class="box"><div class="box-body">
        <div class="btn-group" role="group">

            <?php echo TbHtml::button('<span class="fa fa-reply"></span> '.Yii::t('misc','Back'), array(
                'submit'=>Yii::app()->createUrl('rankinglist/index')));
            ?>

        </div>
<!--        <div class="btn-group pull-right" role="group">-->
<!--            --><?php //echo TbHtml::button('<span class="fa fa-download"></span> '.Yii::t('misc','xiazai'), array(
//                'submit'=>Yii::app()->createUrl('rankinglist/downs')));
//            ?>
<!--        </div>-->
    </div></div>

<section class="content" >
    <div class="row"></div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">销售个人签单总金额排行榜(<?php echo $model['scenario']['start_dt']."/".$model['scenario']['start_dt1'];?>)</h3>
                </div>
                <div class="box-body">
                    <div id="salepeople" class="direct-chat-messages" style="height: 250px;">
                        <table class="table table-bordered small">
                            <tbody>
                            <tr><td><b>排名</b></td><td><b>城市</b></td><td><b>区域</b></td><td><b>姓名</b></td><td><b>签单总金额</b></td></tr>
                            <?php for ($i=0;$i<count($peopel);$i++){ ?>
                            <tr <?php if($i==0){ echo "style='color:#FF0000'";}if($i==1){ echo "style='color:#871F78'";}if($i==2){ echo "style='color:#0000FF'";}?>><td><?php echo $i+1;?></td><td><?php echo $peopel[$i]['city'];?></td><td><?php echo $peopel[$i]['quyu'];?></td><td><?php echo $peopel[$i]['name'];?></td><td><?php echo $peopel[$i]['money'];?></td></tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
<!--                <div class="box-footer">-->
<!--                    <small>每出现签单时即时刷新数据</small>-->
<!--                </div>-->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">地区销售人均签单量排行榜(<?php echo $model['scenario']['start_dt']."/".$model['scenario']['start_dt1'];?>)</h3>
                </div>
                <div class="box-body">
                    <div id="salelist" class="direct-chat-messages" style="height: 250px;">
                        <table class="table table-bordered small">
                            <tbody>
                            <tr><td><b>排名</b></td><td><b>城市</b></td><td><b>区域</b></td><td><b>参与人数</b></td><td><b>人均签单量</b></td></tr>
                            <?php for ($i=0;$i<count($list);$i++){ ?>
                                <tr <?php if($i==0){ echo "style='color:#FF0000'";}if($i==1){ echo "style='color:#871F78'";}if($i==2){ echo "style='color:#0000FF'";}?>><td><?php echo $i+1;?></td><td><?php echo $list[$i]['city'];?></td><td><?php echo $list[$i]['quyu'];?></td><td><?php echo $list[$i]['people'];?></td><td><?php echo $list[$i]['renjun'];?></td></tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">地区销售人均签单金额排行榜(<?php echo $model['scenario']['start_dt']."/".$model['scenario']['start_dt1'];?>)</h3>
                </div>
                <div class="box-body">
                    <div id="salelists" class="direct-chat-messages" style="height: 250px;">
                        <table class="table table-bordered small">
                            <tbody>
                            <tr><td><b>排名</b></td><td><b>城市</b></td><td><b>区域</b></td><td><b>参与人数</b></td><td><b>人均签单金额</b></td></tr>
                            <?php for ($i=0;$i<count($lists);$i++){ ?>
                                <tr <?php if($i==0){ echo "style='color:#FF0000'";}if($i==1){ echo "style='color:#871F78'";}if($i==2){ echo "style='color:#0000FF'";}?>><td><?php echo $i+1;?></td><td><?php echo $lists[$i]['city'];?></td><td><?php echo $lists[$i]['quyu'];?></td><td><?php echo $lists[$i]['people'];?></td><td><?php echo $lists[$i]['money'];?></td></tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
//	echo $form->hiddenField($model,'pageNum');
//	echo $form->hiddenField($model,'totalRow');
//	echo $form->hiddenField($model,'orderField');
//	echo $form->hiddenField($model,'orderType');
//?>
<?php $this->endWidget(); ?>

<?php
	$js = Script::genTableRowClick();
	Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

