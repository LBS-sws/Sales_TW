<div class="box box-primary" >
    <div class="box-header with-border">
        <h3 class="box-title">地区销售人均签单量排行榜(<?php echo date('m', strtotime(date('Y-m-01') )); ?>月)</h3>


        <!--            <div class="box-tools pull-right">-->
        <!--                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->
        <!--            </div>-->
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div id='salelist' class="direct-chat-messages" style="height: 250px;">
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <small>每出现签单时即时刷新数据</small>
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->





<?php
$link = Yii::app()->createAbsoluteUrl("dashboard/salelist");
$js = <<<EOF
	$.ajax({
		type: 'GET',
		url: '$link',
		success: function(data) {
			if (data !== undefined && data.length != 0) {
				var line = '<table class="table table-bordered small">';
                line += '<tr><td><b>排名</b></td><td><b>城市</b></td><td><b>区域</b></td><td><b>参与人数</b></td><td><b>人均签单量</b></td></tr>';
				
				for (var i=0; i < data.length; i++) {
					line += '<tr>';
					style = '';
					switch(i) {
						case 0: style = 'style="color:#FF0000"'; break;
						case 1: style = 'style="color:#871F78"'; break;
						case 2: style = 'style="color:#0000FF"'; break;
					}
					rank = i+1;
					line += '<td '+style+'>'+rank+'</td><td '+style+'>'+data[i].city+'</td><td '+style+'>'+data[i].quyu+'</td><td '+style+'>'+data[i].people+'</td><td '+style+'>'+data[i].renjun+'</td>';
					line += '</tr>';
				}	
				
				line += '</table>';
				$('#salelist').html(line);
			}
		},
		error: function(xhr, status, error) { // if error occured
			var err = eval("(" + xhr.responseText + ")");
			console.log(err.Message);
		},
		dataType:'json'
	});
EOF;
Yii::app()->clientScript->registerScript('salelistDisplay',$js,CClientScript::POS_READY);

?>