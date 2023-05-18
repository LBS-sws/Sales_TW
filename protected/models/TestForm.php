<?php
class TestForm
{
    public function run($nowDate="",$emailBool=false)
    {
        $suffix = Yii::app()->params['envSuffix'];
        $firstDay = !empty($nowDate)?date("Y-m-d",strtotime($nowDate)):date("Y-m-d");
        $arr['start_dt'] = date("Y-m-d", strtotime("$firstDay - 6 day"));
        $arr['end_dt'] = $firstDay;
        $month = date("m",strtotime($arr['start_dt']));
        $month = intval($month);
        $integralList = $this->getIntegralList($arr['start_dt']);
        $Day = date("Y-m-01");
        $dateList = $this->getDateList($firstDay);
        $minVisit = $this->getSalesMin();//最小拜访数量
        $comparisonHtmlData = $this->getComparisonHtmlData($arr);
        $staffMonthData = $this->getStaffOldMonthData($arr);
        //收件人
        $sql = "select a.username,a.email,a.city,c.name from  security$suffix.sec_user a 
              inner join security$suffix.sec_user_access b on a.username = b.username 
              inner join security$suffix.sec_city c on a.city = c.code 
              where b.system_id='sal' and b.a_control like '%CN08%' and a.status='A'
              ";
        $records = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($records as $Addressee) {
            //城市
            $model = new City();
            $record = $model->getDescendant($Addressee['city']);
            array_unshift($record, $Addressee['city']);
            foreach ($record as $k) {
                $nocity = array('CN', 'CS', 'H-N', 'HB', 'HD', 'HD1', 'HK', 'HN', 'HN1', 'HN2', 'HX', 'HXHB', 'JMS',  'MO', 'MY', 'RN', 'XM', 'ZS1', 'ZY');
                $sql_city = "select name from security$suffix.sec_city where code='$k'";
                $city = Yii::app()->db->createCommand($sql_city)->queryScalar();
                if (in_array($k, $nocity, true)) {
                } else {
                    //需要的销售
/*                    $sql_people = "select a.name,e.username from hr$suffix.hr_employee a
                              inner join  hr$suffix.hr_binding b on a.id=b.employee_id 
                              inner join  security$suffix.sec_user_access c on b.user_id=c.username  
                              inner join  security$suffix.sec_user d on c.username=d.username 
                              inner join  sales$suffix.sal_visit e on b.user_id=e.username
        where  c.system_id='sal' and c.a_read_write like '%HK01%' and  d.status='A' and a.city='$k' and   e.visit_dt >= '" . $arr['start_dt'] . "'and e.visit_dt <= '" . $arr['end_dt'] . "' and  a.staff_status =0";
                    $people = Yii::app()->db->createCommand($sql_people)->queryAll();*/
                    $people = Yii::app()->db->createCommand()->select("a.name,d.user_name as username")
                        ->from("hr{$suffix}.hr_binding d")
                        ->leftJoin("hr{$suffix}.hr_employee a","d.employee_id=a.id")
                        ->leftJoin("hr{$suffix}.hr_dept b","a.position=b.id")
                        ->where("a.staff_status=0 AND b.manager_type=1 AND a.city=:city",
                            array(":city"=>$k)
                        )->queryAll();
                    //邮件数据
                    if (!empty($people)) {
                        $people = array_unique($people, SORT_REGULAR);
                        $arr['sale'] = array_column($people, 'username');
                        $arr['sort'] = 'singular';
                        $arr_email = ReportVisitForm::Summary($arr);
                        $sumIntegral = 0;
                        $sum['money'] = array_sum(array_map(create_function('$val', 'return $val["money"];'), $arr_email));
                        $sum['visit'] = array_sum(array_map(create_function('$val', 'return $val["visit"];'), $arr_email));
                        $sum['singular'] = array_sum(array_map(create_function('$val', 'return $val["singular"];'), $arr_email));
                        $sum['svc_A7'] = array_sum(array_map(create_function('$val', 'return $val["svc_A7"];'), $arr_email));
                        $sum['svc_B6'] = array_sum(array_map(create_function('$val', 'return $val["svc_B6"];'), $arr_email));
                        $sum['svc_C7'] = array_sum(array_map(create_function('$val', 'return $val["svc_C7"];'), $arr_email));
                        $sum['svc_D6'] = array_sum(array_map(create_function('$val', 'return $val["svc_D6"];'), $arr_email));
                        $sum['svc_E7'] = array_sum(array_map(create_function('$val', 'return $val["svc_E7"];'), $arr_email));
                        $sum['svc_F4'] = array_sum(array_map(create_function('$val', 'return $val["svc_F4"];'), $arr_email));
                        $sum['svc_G3'] = array_sum(array_map(create_function('$val', 'return $val["svc_G3"];'), $arr_email));
                        $sum['svc_A7s'] = array_sum(array_map(create_function('$val', 'return $val["svc_A7s"];'), $arr_email));
                        $sum['svc_B6s'] = array_sum(array_map(create_function('$val', 'return $val["svc_B6s"];'), $arr_email));
                        $sum['svc_C7s'] = array_sum(array_map(create_function('$val', 'return $val["svc_C7s"];'), $arr_email));
                        $sum['svc_D6s'] = array_sum(array_map(create_function('$val', 'return $val["svc_D6s"];'), $arr_email));
                        $sum['svc_E7s'] = array_sum(array_map(create_function('$val', 'return $val["svc_E7s"];'), $arr_email));
                        $sum['svc_F4s'] = array_sum(array_map(create_function('$val', 'return $val["svc_F4s"];'), $arr_email));
                        $sum['svc_G3s'] = array_sum(array_map(create_function('$val', 'return $val["svc_G3s"];'), $arr_email));
                        $sumColor=$sum['visit']>=$minVisit?"":"color:red";
                        //发送邮件
                        $from_addr = "it@lbsgroup.com.hk";
                        $email_addr=array();
                        $email_addr[]=$Addressee['email'];
                        $to_addr = json_encode($email_addr);
                        $subject = $city . "地区每周体检报告" . $arr['start_dt'] . "至" . $arr['end_dt'];
                        $description = "<br/>".$arr['start_dt'] . "-" . $arr['end_dt'];
                        $url = Yii::app()->params['webroot'];
                        $url .= "/visit/index?start=" . $arr['start_dt'] . "&end=" . $arr['end_dt'] . "&city=" . $city;
                        $message="<br/>地区签单明细".$arr['start_dt'] . "至" . $arr['end_dt'];
                        $message.= <<<EOF
                       
<table cellpadding="10" cellspacing="1" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
	<tbody>
		<tr>
			<td>&nbsp;
			<table border="1" cellpadding="0" cellspacing="0" height="345" style="border-collapse:collapse;width:1300.28pt;" width="1559">
				<colgroup>
					<col span="3" style="width:75.75pt;" width="101" />
					<col style="width:163.00pt;" width="148" />
					<col style="width:108.00pt;" width="132" />
					<col span="4" style="width:75.75pt;" width="101" />
					<col span="10" style="width:54.00pt;" width="72" />
				</colgroup>
				<tbody>
					<tr height="28" style="height:5px;">
						<td class="et3" height="56" rowspan="2" style="height: 20px; width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;"><strong><span style="font-size:18px;">姓名</span></strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>地区</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>段位</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>过去4个月签单情况</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><strong><span style="font-size:18px;">{$month}月积分</span></strong></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><strong><span style="font-size:18px;">当周拜访数量</span></strong></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><strong><span style="font-size:18px;">当周签单数量</span></strong></span></td>
						<td class="et3" rowspan="2" style="width: 110pt; text-align: center;" width="132"><span style="color:#000000;"><strong><span style="font-size:18px;">服务签单总金额</span></strong></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 151.5pt; text-align: center;" width="202"><span style="color:#000000;"><strong><span style="font-size:18px;">清洁</span></strong></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 151.5pt; text-align: center;" width="202"><span style="color:#000000;"><span style="font-size:18px;"><strong>租赁机器</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>灭虫</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>飘盈香</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>甲醛</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>纸品</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>一次性售卖</strong></span></span></td>
					</tr>
					<tr height="28" style="height: 21pt; text-align: center;">
					</tr>
					<tr height="28" style="height:5px;">
						<td class="et3" colspan="2" height="56" rowspan="2" style="height: 20px; width: 151.5pt; text-align: center;" width="202"><span style="color:#000000;"><span style="font-size:16px;"><span style="font-size:18px;"><strong>总金额/总数量</strong></span></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>/</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>/</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>:sumIntegral:</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;{$sumColor}"><strong>{$sum['visit']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['singular']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 110pt; text-align: center;" width="132"><span style="color:#000000;"><span style="font-size:16px;"><span style="font-size:18px;"><strong>{$sum['money']}</strong></span></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;"><span style="font-size:18px;"><strong>{$sum['svc_A7']}</strong></span></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_A7s']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_B6']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_B6s']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_C7']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_C7s']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_D6']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_D6s']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_E7']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_E7s']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_F4']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_F4s']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_G3']}</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>{$sum['svc_G3s']}</strong></span></span></td>
					</tr>
					<tr height="28" style="height: 21pt; text-align: center;">
					</tr>
EOF;
                        foreach ($arr_email as $value) {
                            $numIntegral = key_exists($value["username"],$integralList)?$integralList[$value["username"]]:0;
                            $sumIntegral+=$numIntegral;
                            $visitColor=$sum['visit']>=$minVisit?"":"color:red";
                            $message.= <<<EOF
					<tr>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><a target="_Blank" data-user="{$value['username']}" href="$url&sales={$value['names']}"><span style="font-size:16px;">{$value['names']}</span></a></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$value['cityname']}</span></span></td>
							<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$value['rank']}</span></span></td>

EOF;
                            $message.="<td height='15px' style='height: 15px; text-align: left;'><strong>{$dateList[0]}：</strong>".$this->getArrToStaffAndStr($staffMonthData,$value['username'],$dateList[0])."</td>";
                            $message.= <<<EOF
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$numIntegral}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;{$visitColor}">{$value['visit']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$value['singular']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 110pt; text-align: center;" width="132"><span style="color:#000000;"><span style="font-size:16px;">{$value['money']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_A7']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_A7s']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_B6']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_B6s']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_C7']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_C7s']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_D6']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_D6s']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_E7']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_E7s']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_F4']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_F4s']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_G3']}</span></span></td>
						<td class="et5" rowspan="4" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:16px;">{$value['svc_G3s']}</span></span></td>
					</tr>
EOF;
                            foreach ($dateList as $i_num=> $dateStr){
                                if($i_num>0){
                                    $message.="<tr>";
                                    $message.="<td height='15px' style='height: 15px; text-align: left;'><strong>".$dateStr."：</strong>".$this->getArrToStaffAndStr($staffMonthData,$value['username'],$dateStr)."</td>";
                                    $message.="</tr>";
                                }
                            }
                            
                        }

                        $message.= <<<EOF
                        </tbody>
			</table>
			<span style="color:#000000;"><!--[if !mso]>
<style>
</style>
<![endif]--></span>
			<table border="1" cellpadding="1" cellspacing="1" style="height:345px;border-collapse:collapse;width:1169.28pt;" width="1559">
				<colgroup>
					<col span="3" style="width:75.75pt;text-align:center;" width="101" />
					<col style="width:99pt;text-align:center;" width="132" />
					<col span="4" style="width:75.75pt;text-align:center;" width="101" />
					<col span="10" style="width:54pt;text-align:center;" width="72" />
				</colgroup>
			</table>
			<span style="color:#000000;"> &nbsp;</span>

			<p><br />
			<span style="color:rgb(119,119,119);"><span style="font-size:16px;">请点击员工姓名查看签单记录及合同附件等信息。</span></span></p>
			</td>
		</tr>
	</tbody>
</table>
EOF;
                        $message = str_replace(':sumIntegral:',$sumIntegral,$message);
                        $lcu = "admin";
                        $comparisonHtml = $this->getHtmlForCity($comparisonHtmlData,$k,$city);

                        echo $subject."<br/>";
                        echo $comparisonHtml.$message;
                        echo "<br/>toAdr:".$to_addr."<br/>";
                        if($emailBool){
                            Yii::app()->db->createCommand()->insert("swoper$suffix.swo_email_queue", array(
                                'request_dt' => date('Y-m-d H:i:s'),
                                'from_addr' => $from_addr,
                                'to_addr' => $to_addr,
                                'subject' => $subject,//郵件主題
                                'description' => $description,//郵件副題
                                'message' => $comparisonHtml.$message,//郵件內容（html）
                                'status' => "P",
                                'lcu' => $lcu,
                                'lcd' => date('Y-m-d H:i:s'),
                            ));
                        }
                        echo "end!<br/>";
                    }else{
                        //发送邮件
                        $from_addr = "it@lbsgroup.com.hk";
                        $email_addr=array();
                        $email_addr[]=$Addressee['email'];
                        $to_addr = json_encode($email_addr);
                        $subject =$city . "地区每周体检报告" . $arr['start_dt'] . "至" . $arr['end_dt'];
                        $description = "</<br>".$arr['start_dt'] . "-" . $arr['end_dt'];
                        $sumColor=0>=$minVisit?"":"color:red";
                     //   $url = Yii::app()->params['webroot'];
                     //   $url .= "/visit/index?start=" . $arr['start_dt'] . "&end=" . $arr['end_dt'] . "&city=" . $city;
                        $message="<br/>地区签单明细".$arr['start_dt'] . "至" . $arr['end_dt'];
                        $message.= <<<EOF
<table cellpadding="10" cellspacing="1" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
	<tbody>
		<tr>
			<td>&nbsp;
			<table border="1" cellpadding="0" cellspacing="0" height="220" style="border-collapse:collapse;width:1300.28pt;" width="1559">
				<colgroup>
					<col span="3" style="width:75.75pt;" width="101" />
					<col style="width:163.00pt;" width="148" />
					<col style="width:108.00pt;" width="132" />
					<col span="4" style="width:75.75pt;" width="101" />
					<col span="10" style="width:54.00pt;" width="72" />
				</colgroup>
				<tbody>
					<tr height="28" style="height:5px;">
						<td class="et3" height="56" rowspan="2" style="height: 20px; width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;"><strong><span style="font-size:18px;">姓名</span></strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>地区</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>段位</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>过去4个月签单情况</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><strong><span style="font-size:18px;">{$month}月积分</span></strong></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><strong><span style="font-size:18px;">当周拜访数量</span></strong></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><strong><span style="font-size:18px;">当周签单数量</span></strong></span></td>
						<td class="et3" rowspan="2" style="width: 110pt; text-align: center;" width="132"><span style="color:#000000;"><strong><span style="font-size:18px;">服务签单总金额</span></strong></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 151.5pt; text-align: center;" width="202"><span style="color:#000000;"><strong><span style="font-size:18px;">清洁</span></strong></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 151.5pt; text-align: center;" width="202"><span style="color:#000000;"><span style="font-size:18px;"><strong>租赁机器</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>灭虫</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>飘盈香</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>甲醛</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>纸品</strong></span></span></td>
						<td class="et3" colspan="2" rowspan="2" style="width: 108pt; text-align: center;" width="144"><span style="color:#000000;"><span style="font-size:18px;"><strong>一次性售卖</strong></span></span></td>
					</tr>
					<tr height="28" style="height: 21pt; text-align: center;">
					</tr>
					<tr height="28" style="height:5px;">			
						<td class="et3" colspan="2" height="56" rowspan="2" style="height: 20px; width: 151.5pt; text-align: center;" width="202"><span style="color:#000000;"><span style="font-size:16px;"><span style="font-size:18px;"><strong>总金额/总数量</strong></span></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>/</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>/</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;{$sumColor}"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 110pt; text-align: center;" width="132"><span style="color:#000000;"><span style="font-size:16px;"><span style="font-size:18px;"><strong>0</strong></span></span></span></td>
						<td class="et3" rowspan="2" style="width: 110pt; text-align: center;" width="132"><span style="color:#000000;"><span style="font-size:16px;"><span style="font-size:18px;"><strong>0</strong></span></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:16px;"><span style="font-size:18px;"><strong>0</strong></span></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="101"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
						<td class="et3" rowspan="2" style="width: 75.75pt; text-align: center;" width="72"><span style="color:#000000;"><span style="font-size:18px;"><strong>0</strong></span></span></td>
					</tr>
					<tr height="28" style="height: 21pt; text-align: center;">
					</tr>
EOF;
                        $message.= <<<EOF
                        </tbody>
			</table>
			<span style="color:#000000;"><!--[if !mso]>
<style>
</style>
<![endif]--></span>
			<table border="1" cellpadding="1" cellspacing="1" style="height:345px;border-collapse:collapse;width:1169.28pt;" width="1559">
				<colgroup>
					<col span="3" style="width:75.75pt;text-align:center;" width="101" />
					<col style="width:99pt;text-align:center;" width="132" />
					<col span="4" style="width:75.75pt;text-align:center;" width="101" />
					<col span="10" style="width:54pt;text-align:center;" width="72" />
				</colgroup>
			</table>
			<span style="color:#000000;"> &nbsp;</span>

			<p><br />
			</td>
		</tr>
	</tbody>
</table>
EOF;
                        $lcu = "admin";
                        $comparisonHtml = $this->getHtmlForCity($comparisonHtmlData,$k,$city);
                        echo $subject."<br/>";
                        echo $comparisonHtml.$message;
                        echo "<br/>toAdr:".$to_addr."<br/>";
                        if($emailBool){
                            Yii::app()->db->createCommand()->insert("swoper$suffix.swo_email_queue", array(
                                'request_dt' => date('Y-m-d H:i:s'),
                                'from_addr' => $from_addr,
                                'to_addr' => $to_addr,
                                'subject' => $subject,//郵件主題
                                'description' => $description,//郵件副題
                                'message' => $comparisonHtml.$message,//郵件內容（html）
                                'status' => "P",
                                'lcu' => $lcu,
                                'lcd' => date('Y-m-d H:i:s'),
                            ));
                        }
                        echo "end!<br/><br/><br/>";
                        }
                    }
                }
            }
    }

    private function getSalesMin(){
        $row = Yii::app()->db->createCommand()->select("min_num")->from("sal_sales_min")
            ->where("id>0")->order("id desc")->queryRow();
        if($row){
            return intval($row["min_num"]);
        }else{
            return 0;
        }
    }


    private function getComparisonHtmlData($arr){
        $model = new ComparisonForm();
        $model->start_date = date("Y-m-01",strtotime($arr["end_dt"]));
        $model->end_date = $arr["end_dt"];
        $model->week_start_date = $arr["start_dt"];
        $model->retrieveData();
        $dataHtml = $model->getDataToHtml();
        return array("dataHtml"=>$dataHtml,"defaultTable"=>$model->defaultTable);
    }

    private function getStaffOldMonthData($arr){
        $date = date("Y/m/01",strtotime($arr["end_dt"]));
        $startDate = date("Y/m/01",strtotime($date." -4 month"));
        $endDate = date("Y/m/t",strtotime($date." -1 month"));
        $list = array();
        $rows = Yii::app()->db->createCommand()->select("b.username,DATE_FORMAT(b.visit_dt,'%Y/%m') as yearMonth,sum(convert(a.field_value, decimal(12,2))) as money")
            ->from("sal_visit_info a")
            ->leftJoin("sal_visit b","a.visit_id=b.id")
            ->where("(b.shift<>'Z' or b.shift is null) and a.field_id in ('svc_A7','svc_B6','svc_C7','svc_D6','svc_E7','svc_F4','svc_G3') and b.visit_dt BETWEEN '{$startDate}' AND '{$endDate}' and b.visit_obj like '%10%'")
            ->group("b.username,yearMonth")->queryAll();
        if($rows){
            foreach ($rows as $row){
                if(!key_exists($row['username'],$list)){
                    $list[$row['username']]=array();
                }
                $list[$row['username']][$row['yearMonth']]=floatval($row['money']);
            }
        }
        return $list;
    }

    private function getHtmlForCity($comparisonHtmlData,$city,$city_name=""){
        $list = array(
            "table"=>"",
            "twoGross"=>"",
            "stopWeekSum"=>0,
            "stopMonthSum"=>0,
            "stopRate"=>0,
            "uServiceMoney"=>0,
            "stopListOnly"=>array()
        );
        if(key_exists($city,$comparisonHtmlData["dataHtml"])){
            $list = $comparisonHtmlData["dataHtml"][$city];
        }
        if(empty($list["table"])){//如果該地區沒有數據，調用默認表格
            $list["table"] = str_replace(':city_name:',$city_name,$comparisonHtmlData["defaultTable"]);
        }
        $html=$list["table"];
        $html.="<p>每月新生意额要求:{$list["twoGross"]}</p>";
        $html.="<p style='color: red;'>本周停单年金额:{$list["stopWeekSum"]}</p>";
        $html.="<p style='color: red;'>本周停单月金额:{$list["stopMonthSum"]}</p>";
        $html.="<p style='color: red;' data-u='{$list["uServiceMoney"]}'>本月停单率:{$list["stopRate"]}</p>";
        $html.="<p>月金额超过4000/月的停单客户明细如下：</p>";
        $html.=self::stopTableHtmlForMonth($list["stopListOnly"]);
        $html.="<br/>";

        return $html;
    }

    public static function stopTableHtmlForMonth($stopList=array()){
        $table = "";
        $table.= '<table border="1" cellpadding="0" cellspacing="0" style="width: 100%;">';
        $table.='<thead><tr>';
        $table.='<th>终止日期</th>';
        $table.='<th>客户编号及名称</th>';
        $table.='<th>客户类别</th>';
        $table.='<th>性质</th>';
        $table.='<th>服务内容</th>';
        $table.='<th>月金额</th>';
        $table.='<th>年金额</th>';
        $table.='<th>变动原因</th>';
        $table.='</tr></thead><tbody>';
        if(!empty($stopList)){
            foreach ($stopList as $row){
                $table.='<tr>';
                $table.='<td>'.General::toMyDate($row["status_dt"]).'</td>';
                $table.='<td>'.$row["code"].$row["name"].'</td>';
                $table.='<td>'.$row["type_name"].'</td>';
                $table.='<td>'.$row["nature_name"].'</td>';
                $table.='<td>'.$row["service"].'</td>';
                $table.='<td>'.$row["stopMoneyForMonth"].'</td>';
                $table.='<td>'.$row["stopMoneyForYear"].'</td>';
                $table.='<td>'.$row["reason"].'</td>';
                $table.='</tr>';
            }
        }
        $table.='</tbody></table>';
        return $table;
    }

    private function getIntegralList($date){
        $year = date("Y", strtotime($date));
        $month = date("m", strtotime($date));
        $month = intval($month);
        $rows = Yii::app()->db->createCommand()->select("username,all_sum")->from("sal_integral")
            ->where("year='{$year}' and month='{$month}'")->queryAll();
        if($rows){
            $arr = array();
            foreach ($rows as $row){
                $arr[$row["username"]] = is_numeric($row["all_sum"])?floatval($row["all_sum"]):0;
            }
            return $arr;
        }else{
            return array();
        }
    }

    //获取前四个月的日期
    private function getDateList($date){
        $date = date("Y/m/01",strtotime($date));
        $list=array();
        for($i=1;$i<=4;$i++){
            $list[] = date("Y/m",strtotime($date."- {$i} month"));
        }
        return $list;
    }

    private function getArrToStaffAndStr($staffMonthData,$username,$dateStr){
        if(key_exists($username,$staffMonthData)){
            if(key_exists($dateStr,$staffMonthData[$username])){
                return $staffMonthData[$username][$dateStr];
            }
        }
        return 0;
    }
}
