<?php
$this->pageTitle=Yii::app()->name . ' - Report';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'report-form',
'action'=>Yii::app()->createUrl('rankinglist/view'),
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true,),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
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

<section class="content">
	<div class="box"><div class="box-body">
	<div class="btn-group" role="group">
		<?php echo TbHtml::button(Yii::t('misc','Submit'), array(
				'submit'=>Yii::app()->createUrl('rankinglist/view')));
		?>
	</div>
	</div></div>
	<div class="box box-info">
		<div class="box-body">

		<?php if ($model->showField('end_dt')): ?>
			<div class="form-group">
				<?php echo $form->labelEx($model,'查看日期',array('class'=>"col-sm-2 control-label")); ?>
				<div >
                    <select id="city" class="select" name="ReportRankinglistForm[start_dt]" style="width:80px;height: 35px">
                        <?php foreach ($model->date as $v){?>
                            <option value="<?php echo $v;?>"><?php echo $v;?>年</option>
                        <?php }?>
                    </select>
                                <select id="city" class="select" name="ReportRankinglistForm[start_dt1]"  style="width:50px;height: 35px">
                                    <option value="1">1月</option>
                                    <option value="2">2月</option>
                                    <option value="3">3月</option>
                                    <option value="4">4月</option>
                                    <option value="5">5月</option>
                                    <option value="6">6月</option>
                                    <option value="7">7月</option>
                                    <option value="8">8月</option>
                                    <option value="9">9月</option>
                                    <option value="10">10月</option>
                                    <option value="11">11月</option>
                                    <option value="12">12月</option>
                                </select>
				</div>
			</div>
		<?php else: ?>
			<?php echo $form->hiddenField($model, 'end_dt'); ?>
		<?php endif ?>



		</div>
	</div>
</section>

<?php
$js = "
showEmailField();
$(document).ready(function(){
  $('#year').click(function(){
alert(111)
  });
});
$('#ReportForm_format').on('change',function() {
	showEmailField();
});
function test(){
var tes=document.getElementById(\"tes\");//获取select元素
alert(tes.options[\"内容值：\"+tes.selectedIndex].innerHTML+\"元素值\"+tes.options[tes.selectedIndex].value);
}
function showEmailField() {
	$('#email_div').css('display','none');
	if ($('#ReportForm_format').val()=='EMAIL') $('#email_div').css('display','');
}

";
Yii::app()->clientScript->registerScript('changestyle',$js,CClientScript::POS_READY);
Yii::app()->clientScript->registerScript('calculate',$js,CClientScript::POS_READY);
$datefields = array();
if ($model->showField('start_dt')) $datefields[] = 'ReportForm_start_dt';
if ($model->showField('end_dt')) $datefields[] = 'ReportForm_end_dt';
if ($model->showField('target_dt')) $datefields[] = 'ReportForm_target_dt';
if (!empty($datefields)) {
	$js = Script::genDatePicker($datefields);
	Yii::app()->clientScript->registerScript('datePick',$js,CClientScript::POS_READY);
}
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

