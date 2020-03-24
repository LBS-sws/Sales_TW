<?php

class DashboardController extends Controller
{
	public $interactive = false;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl - checksession', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('notify','salepeople','Salelist','Salelists'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionNotify($id=-1) {
		$rtn = array();
		if ($id >= 0) {
			$model = new Notification();
			$rtn = $model->getNewMessageById($id);
		}
		echo json_encode($rtn);
	}


	public function actionSalepeople() {
		$suffix = Yii::app()->params['envSuffix'];
		$models = array();
		$time= date('Y-m-d', strtotime(date('Y-m-01') ));
		$obj_where = $this->getDealString("a.visit_obj");
		$sql = "select a.city, a.username, sum(convert(b.field_value, decimal(12,2))) as money
				from sal_visit a force index (idx_visit_02), sal_visit_info b
				where a.id=b.visit_id and b.field_id in ('svc_A7','svc_B6','svc_C7','svc_D6','svc_E7','svc_F4','svc_G3') 
				and a.visit_dt >= '$time' and  ($obj_where)
				group by a.city, a.username
			";
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($records as $record) {
			$temp = array();
			$temp['user']=$record['username'];
			$temp['money']=$record['money'];


            $sql = "select name from hr$suffix.hr_employee where id=(SELECT employee_id from hr$suffix.hr_binding WHERE user_id='".$record['username']."')";
			$row = Yii::app()->db->createCommand($sql)->queryRow();
			$temp['name']= $row!==false ? $row['name'] : $record['username'];
		
			$sql = "select a.name as city_name, b.name as region_name 
					from security$suffix.sec_city a
					left outer join security$suffix.sec_city b on a.region=b.code
					where a.code='".$record['city']."'
				";
			$row = Yii::app()->db->createCommand($sql)->queryRow();
			$temp['city'] = $row!==false ? $row['city_name'] : $record['city'];
			$temp['quyu'] = $row!==false ? str_replace(array('1','2','3','4','5','6','7','8','9','0'),'',$row['region_name']) : '空';

			$models[] = $temp;
		}
		$last_names = array_column($models,'money');
		array_multisort($last_names,SORT_DESC,$models);
		$models = array_slice($models, 0, 20);


		echo json_encode($models);
	}


    public function actionSalelist() {
        $suffix = Yii::app()->params['envSuffix'];
        $models = array();
        $cities = General::getCityListWithNoDescendant();
        $time= date('Y-m-d', strtotime(date('Y-m-01') ));
        foreach ($cities as $code=>$name) {
            if (strpos("/'CN'/'HK'/","'".$code."'")===false) {
                $sql = "select a.name as city_name, b.name as region_name 
						from security$suffix.sec_city a
						left outer join security$suffix.sec_city b on a.region=b.code
						where a.code='$code'
					";
                $row = Yii::app()->db->createCommand($sql)->queryRow();
                $temp = $row!==false ? str_replace(array('1','2','3','4','5','6','7','8','9','0'),'',$row['region_name']) : '空';

                //人数
                $sql1="select distinct  username FROM sal_visit  WHERE city='$code' and visit_dt >='".$time."'";
                $people = Yii::app()->db->createCommand($sql1)->queryAll();
                $peoples=count($people);
                //总单数
				$obj_where = $this->getDealString("visit_obj");
                $sql2="select id from sal_visit where city='$code' and  ($obj_where) and visit_dt >='".$time."'";
                $sum = Yii::app()->db->createCommand($sql2)->queryAll();
                $sums=count($sum);
                //人均签单数
                $sale=$sums/($peoples==0?1:$peoples);
                $sale=round($sale,2);
                $models[$code] = array('city'=>$name, 'renjun'=>$sale, 'quyu'=>$temp,'people'=>$peoples);

            }
        }
foreach ($models as $key=>$item) {
    $result[] = $item;
}

        $arraycol = array_column($result,'renjun');
        array_multisort($arraycol,SORT_DESC,$result);
//print_r('<pre>');
//print_r($models);
        echo json_encode($result);
    }

    public function actionSalelists() {
        $suffix = Yii::app()->params['envSuffix'];
        $models = array();
        $cities = General::getCityListWithNoDescendant();
        $time= date('Y-m-d', strtotime(date('Y-m-01') ));
        foreach ($cities as $code=>$name) {
            if (strpos("/'CN'/'HK'/","'".$code."'")===false) {
                $sql = "select a.name as city_name, b.name as region_name 
						from security$suffix.sec_city a
						left outer join security$suffix.sec_city b on a.region=b.code
						where a.code='$code'
					";
                $row = Yii::app()->db->createCommand($sql)->queryRow();
                $temp = $row!==false ? str_replace(array('1','2','3','4','5','6','7','8','9','0'),'',$row['region_name']) : '空';

                //人数
                $sql1="select distinct  username FROM sal_visit  WHERE city='$code' and visit_dt >='".$time."'";
                $people = Yii::app()->db->createCommand($sql1)->queryAll();
                $peoples=count($people);
                //总单数
				$obj_where = $this->getDealString("visit_obj");
                $sql2="select id from sal_visit where city='$code' and  ($obj_where) and visit_dt >='".$time."'";
                $sum = Yii::app()->db->createCommand($sql2)->queryAll();
                $sums=count($sum);
                //人均签单数
                $sale=$sums/($peoples==0?1:$peoples);
                $sale=round($sale,2);

                //总金额
                $money=0;
                foreach ($sum as $b){
                    $sql3="select field_id, field_value from sal_visit_info where field_id in ('svc_A7','svc_B6','svc_C7','svc_D6','svc_E7','svc_F4','svc_G3') and visit_id = '".$b['id']."'";
                    $array = Yii::app()->db->createCommand($sql3)->queryAll();
                    $summoney = 0;
                    foreach($array as $item){
                        $summoney += $item['field_value'];
                    }
                    //总金额
                    $money+=$summoney;
                }
                $money=$money/($peoples==0?1:$peoples);
                $money=round($money,2);
                $models[$code] = array('city'=>$name, 'money'=>$money, 'quyu'=>$temp,'people'=>$peoples);
            }
        }
        foreach ($models as $key=>$item) {
            $result[] = $item;
        }

        $arraycol = array_column($result,'money');
        array_multisort($arraycol,SORT_DESC,$result);
//print_r('<pre>');
//print_r($result);
        echo json_encode($result);
    }
	
	private function getDealString($field) {
		$rtn = '';
		$sql = "select id from sal_visit_obj where rpt_type='DEAL'";
		$rows = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($rows as $row) {
			$rtn .= ($rtn=='' ? '' : ' or ').$field." like '%\"".$row['id']."\"%'";
		}
		return ($rtn=='' ? "$field='0'" : $rtn);
	}
}

?>