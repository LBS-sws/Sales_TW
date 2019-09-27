<?php
$this->pageTitle=Yii::app()->name . ' - Sales Visit Form';
?>
<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'visit-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('sales','Sales Visit one Form'); ?></strong>
    </h1>
</section>

<section class="content">
    <div class="box"><div class="box-body">
            <div class="btn-group" role="group">
                <?php echo TbHtml::button('<span class="fa fa-reply"></span> '.Yii::t('misc','Back'), array(
                    'submit'=>Yii::app()->createUrl('report/visit')));
                ?>
            </div>

            <div class="btn-group pull-right" role="group">
                <?php echo TbHtml::button('<span class="fa fa-download"></span> '.Yii::t('misc','Xiazai'), array(
                    'submit'=>Yii::app()->createUrl('report/down')));
            ?>
        </div>
        </div>
    </div>

    <div class="box box-info">
        <div class="box-body">
            <?php
            echo $form->hiddenField($model, 'scenario');
            echo $form->hiddenField($model, 'id');  
            echo $form->hiddenField($model, 'city');
            ?>
<!--            <input type="text" name="RptFive[city]" value="--><?php //echo $fenxi['city']?><!--" style="display:none"/>-->
            <input type="text" name="RptFive[start_dt]" value="<?php echo $fenxi['start_dt']?>" style="display:none"/>
            <input type="text" name="RptFive[end_dt]" value="<?php echo $fenxi['end_dt']?>" style="display:none"/>
<!--            <input type="text" name="RptFive[bumen]" value="--><?php //echo $fenxi['bumen']?><!--" style="display:none"/>-->

            <?php if(!empty($fenxi['sale'])){foreach ($fenxi['sale'] as $v) {?>
                <input name="RptFive[sale][]" type="checkbox" value="<?php echo $v?>" style="display:none" checked />
            <?php } }?>



            <style type="text/css">
                .tftable {font-size:12px;color:#333333;border-width: 1px;border-color: #729ea5;border-collapse: collapse;}
                .tftable th {font-size:12px;background-color:#acc8cc;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;text-align: center;width: 50px;}
                .tftable tr {background-color:#d4e3e5;}
                .tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;width: 75px;}
                .tftable tr:hover {background-color:#ffffff;}
            </style>
            <style type="text/css">
                .tftable1 {font-size:12px;color:#333333;border-width: 1px;border-color: #9dcc7a;border-collapse: collapse;}
                .tftable1 th {font-size:12px;background-color:#abd28e;border-width: 1px;padding: 8px;border-style: solid;border-color: #9dcc7a;text-align:center;width: 50px;}
                .tftable1 tr {background-color:#bedda7;}
                .tftable1 td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #9dcc7a;width: 75px;}
                .tftable1 tr:hover {background-color:#ffffff;}
            </style>
            <style type="text/css">
                .tftable2 {font-size:12px;color:#333333;border-width: 1px;border-color: #a9a9a9;border-collapse: collapse;}
                .tftable2 th {font-size:12px;background-color:#b8b8b8;border-width: 1px;padding: 8px;border-style: solid;border-color: #a9a9a9;text-align:center;width: 50px;}
                .tftable2 tr {background-color:#cdcdcd;}
                .tftable2 td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #a9a9a9;width: 75px;}
                .tftable2 tr:hover {background-color:#ffffff;}
            </style>
            <style type="text/css">
                .tftable3 {font-size:12px;color:#333333;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
                .tftable3 th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:center;width: 50px;}
                .tftable3 tr {background-color:#f0c169;}
                .tftable3 td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;width: 75px;}
                .tftable3 tr:hover {background-color:#ffffff;}
            </style>
            <?php if(!empty($model['all'])){?>
            <div>   <h4>注: &nbsp; 10/5/30000 表示 总拜访量10个，签单5个，签单金额30000</h4>
                <h4><b>总拜访量:<?php echo $model['all']['money']['all'];?> 签单量：<?php echo $model['all']['money']['sum'];?>  签单金额:<?php echo $model['all']['money']['money'];?> </b></h4>
                <h3><b>个人总数据</b></h3>
                <table class="tftable" border="1">
                    <tr><th rowspan="5" width="100">拜访类型</th><th >陌生開發</th><td ><?php echo $model['all']['mobai'];?></td><th >日常跟進</th><td ><?php echo $model['all']['richanggengjin'];?></td><th >客戶資源</th><td ><?php echo $model['all']['kehuziyuan'];?></td><th >電話上門</th><td ><?php echo $model['all']['dianhuashangmen'];?></td></tr>
                </table>

                <table class="tftable1" border="1">
                    <tr><th rowspan="2" width="100">拜访目的</th><th >新開發</th><td ><?php echo $model['all']['shouci'];?></td><th  >開發中客戶覆訪</th><td ><?php echo $model['all']['huifang'];?></td>
                   <th>停單客戶回訪</th><td><?php echo $model['all']['jiuke'];?></td><th>签单</th><td><?php echo $model['all']['qiandan'];?></td></tr>
                </table>

                <table class="tftable2" border="1">
                    <tr><th rowspan="6" width="100">区域</th><?php for($i=0;$i<count($model['all']['address']);$i++){?><th ><?php echo $model['all']['address'][$i]['name'];?></th><td ><?php echo $model['all']['address'][$i]['0'];?></td><?php  if(($i+1)%7==0){ echo "</tr>";}?><?php }?>
                    <tr></tr>
                </table>

                <table class="tftable3" border="1">
                    <tr><th rowspan="3" width="100">客服类别（餐饮）</th><th >PUB</th><td ><?php echo $model['all']['dongbeicai'];?></td><th >日式</th><td ><?php echo $model['all']['taiguocai'];?></td><th >蛋糕/麵包/甜點</th><td ><?php echo $model['all']['mianbao'];?></td><th >婚宴會館</th><td ><?php echo $model['all']['chuancai'];?></td><th >火锅</th><td ><?php echo $model['all']['huoguo'];?></td><th >西餐</th><td ><?php echo $model['all']['xican'];?></td><th>中餐</th><td><?php echo $model['all']['kafeiting'];?></td></tr>
                    <tr><th>早餐</th><td><?php echo $model['all']['zejiangcai'];?></td><th>廠房餐廳</th><td><?php echo $model['all']['riliao'];?></td><th>燒烤/炸物</th><td><?php echo $model['all']['saokao'];?></td><th>飲料店</th><td><?php echo $model['all']['yuenancai'];?></td><th>咖啡餐廳</th><td><?php echo $model['all']['xiaochi'];?></td><th>異國料理</th><td><?php echo $model['all']['qingzhencai'];?></td></tr>
                    <tr><th rowspan="5" width="100">客服类别（非餐饮）</th><th>健身会所</th><td><?php echo $model['all']['jianshenhuisuo'];?></td><th>旅館業</th><td><?php echo $model['all']['fangdican'];?></td><th >加油站</th><td><?php echo $model['all']['peixunjigou'];?></td><th>商辦</th><td><?php echo $model['all']['xuexiao'];?></td><th>營造</th><td><?php echo $model['all']['shuiliao'];?></td><th >教育業</th><td><?php echo $model['all']['yingyuan'];?></td><th>休閒娛樂</th><td><?php echo $model['all']['xiezilou'];?></td></tr>
                    <tr><th>工廠</th><td><?php echo $model['all']['gongcang'];?></td><th>補教</th><td><?php echo $model['all']['youyong'];?></td><th>醫療</th><td><?php echo $model['all']['wuye'];?></td><th>公家機關</th><td><?php echo $model['all']['yiyuan'];?></td><th>家居/社區</th><td><?php echo $model['all']['yinglou'];?></td></tr>

                </table>
            </div>
            <?php }?>

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


