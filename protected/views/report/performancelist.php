    <?php
$this->pageTitle=Yii::app()->name . ' - performance Form';
?>
<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'performance-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('sales','Sales Performance Form'); ?></strong>
    </h1>
</section>

<section class="content">
    <div class="box"><div class="box-body">
            <div class="btn-group" role="group">
                <?php echo TbHtml::button('<span class="fa fa-reply"></span> '.Yii::t('misc','Back'), array(
                    'submit'=>Yii::app()->createUrl('report/performance')));
                ?>
<!--                <input class="btn btn-default" type="button" name="Submit" onclick="javascript:history.back(-1);" value="返回">-->
            </div>
            <?php if(!empty($post['sale'])){?>
            <div class="btn-group pull-right" role="group">
                <?php echo TbHtml::button('<span class="fa fa-download"></span> '.Yii::t('misc','Xiazai'), array(
                    'submit'=>Yii::app()->createUrl('report/performancedown')));
                ?>
            </div>
            <?php }?>
        </div>
    </div>

    <div class="box box-info">
        <div class="box-body">
            <?php
//            echo $form->hiddenField($model, 'scenario');
//            echo $form->hiddenField($model, 'id');
//            echo $form->hiddenField($model, 'city');
//            ?>
            <?php if(!empty($post)){?>
            <input type="text" name="RptFive[city]" value="<?php echo $post['city']?>" style="display:none"/>
            <input type="text" name="RptFive[start_dt]" value="<?php echo $post['start_dt']?>" style="display:none"/>
            <input type="text" name="RptFive[end_dt]" value="<?php echo $post['end_dt']?>" style="display:none"/>
            <input type="text" name="RptFive[sort]" value="<?php echo $post['sort']?>" style="display:none"/>
            <?php if(!empty($post['sale'])){ foreach ($post['sale'] as $v){?>
            <input name="RptFive[sale][]" type="checkbox" value="<?php echo $v ;?>" checked="checked" style="display:none"/>
            <?php }}}?>

            <table class="table table-bordered small" style="text-align: center;">
              <tbody>
                <tr>
                    <td style="width: 9%;"><h4><b>姓名</b></h4></td><td style="width: 9%;"><h4><b>城市</b></h4></td style="width: 9%;"><td><h4><b>签单数量</b></h4></td><td style="width: 9%;"><h4><b>服务签单总金额</b></h4></td><td style="background-color: #9acfea;width: 9%;text-align: center;" colspan="2"><h4><b>清洁</b></h4></td><td style="background-color: #9acfea;width: 9%;text-align: center;" colspan="2"><h4><b>租赁机器</b></h4></td><td style="background-color: #9acfea;width: 9%;text-align: center;" colspan="2"><h4><b>灭虫</b></h4></td><td style="background-color: #9acfea;width: 9%;text-align: center;" colspan="2"><h4><b>飘盈香</b></h4></td><td style="background-color: #9acfea;width: 9%;text-align: center;" colspan="2"><h4><b>	甲醛</b></h4></td><td style="background-color: #9acfea;width: 9%;text-align: center;" colspan="2"><h4><b>纸品</b></h4></td><td style="background-color: #9acfea;width: 9%;text-align: center;" colspan="2"><h4><b>一次性售卖</b></h4></td>
                </tr>
                <?php if(!empty($sum)){?>
                <tr >
                    <td colspan="2" ><h4><b>总金额/总数量</b></h4></td>  <td><h4><b><?php echo $sum['singular'];?></b></h4></td> <td><h4><b><?php echo $sum['money'];?></b></h4></td> <td style="background-color: #9acfea"><h4><b><?php echo $sum['svc_A7'];?></b></h4></td><td style="background-color: #9acfea;width: 4%;"><h4><b><?php echo $sum['svc_A7s'];?></b></h4></td> <td style="background-color: #9acfea;"><h4><b><?php echo $sum['svc_B6'];?></b></h4></td> <td style="background-color: #9acfea;width: 4%;"><h4><b><?php echo $sum['svc_B6s'];?></b></h4></td> <td style="background-color: #9acfea"><h4><b><?php echo $sum['svc_C7'];?></b></h4></td> <td style="background-color: #9acfea;width: 4%;"><h4><b><?php echo $sum['svc_C7s'];?></b></h4></td> <td style="background-color: #9acfea"><h4><b><?php echo $sum['svc_D6'];?></b></h4></td> <td style="background-color: #9acfea;width: 4%;"><h4><b><?php echo $sum['svc_D6s'];?></b></h4></td> <td style="background-color: #9acfea"><h4><b><?php echo $sum['svc_E7'];?></b></h4></td><td style="background-color: #9acfea;width: 4%;"><h4><b><?php echo $sum['svc_E7s'];?></b></h4></td> <td style="background-color: #9acfea"><h4><b><?php echo $sum['svc_F4'];?></b></h4></td> <td style="background-color: #9acfea;width: 4%;"><h4><b><?php echo $sum['svc_F4s'];?></b></h4></td> <td style="background-color: #9acfea"><h4><b><?php echo $sum['svc_G3'];?></b></h4></td><td style="background-color: #9acfea;width: 4%;"><h4><b><?php echo $sum['svc_G3s'];?></b></h4></td>
                </tr>
                <?php }?>
                <?php if(!empty($array)){ foreach ($array as $a){ ?>
                <tr>
                    <td><?php echo $a['names'];?></td> <td><?php echo $a['cityname'];?></td> <td><?php echo $a['singular'];?></td> <td><?php echo $a['money'];?></td> <td style="background-color: #9acfea"><?php echo $a['svc_A7'];?></td><td style="background-color: #9acfea;width: 4%;"><?php echo $a['svc_A7s'];?></td> <td style="background-color: #9acfea;"><?php echo $a['svc_B6'];?></td> <td style="background-color: #9acfea;width: 4%;"><?php echo $a['svc_B6s'];?></td> <td style="background-color: #9acfea"><?php echo $a['svc_C7'];?></td> <td style="background-color: #9acfea;width: 4%;"><?php echo $a['svc_C7s'];?></td> <td style="background-color: #9acfea"><?php echo $a['svc_D6'];?></td> <td style="background-color: #9acfea;width: 4%;"><?php echo $a['svc_D6s'];?></td> <td style="background-color: #9acfea"><?php echo $a['svc_E7'];?></td><td style="background-color: #9acfea;width: 4%;"><?php echo $a['svc_E7s'];?></td> <td style="background-color: #9acfea"><?php echo $a['svc_F4'];?></td> <td style="background-color: #9acfea;width: 4%;"><?php echo $a['svc_F4s'];?></td> <td style="background-color: #9acfea"><?php echo $a['svc_G3'];?></td><td style="background-color: #9acfea;width: 4%;"><?php echo $a['svc_G3s'];?></td>
                </tr>
                <?php }}?>
              </tbody>
            </table>

        </div>
    </div>


</section>



<?php
$js = Script::genDeleteData(Yii::app()->createUrl('visit/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>


