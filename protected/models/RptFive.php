<?phpclass RptFive extends CReport {	public function fields() {		return array(			'start_dt'=>array('label'=>Yii::t('report','Start_Dt'),'width'=>30,'align'=>'C'),            'staffs_desc'=>array('label'=>Yii::t('report','Staffs_Desc'),'width'=>30,'align'=>'L'),			'name'=>array('label'=>Yii::t('report','Name'),'width'=>22,'align'=>'C'),            'gangwei'=>array('label'=>Yii::t('report','GanWwei'),'width'=>18,'align'=>'C'),			'five'=>array('label'=>Yii::t('report','Five'),'width'=>10,'align'=>'C'),		);	}	public function retrieveData() {//		$city = Yii::app()->user->city();		$city = $this->criteria->city;        $sql="select a.name,a.code,a.city,a.entry_time,b.name as username				from hrdev.hr_employee a 				left outer join hrdev.hr_dept b on a.position = b.id ";		$where = "where dept_class='Sales' and a.city='".$city."'";		if (isset($this->criteria)) {			if (isset($this->criteria->start_dt))				$where .= (($where=='where') ? " " : " and ")."entry_dt>='".General::toDate($this->criteria->start_dt)."'";		}		if ($where!='where') $sql .= $where;			$sql .= " order by entry_dt desc";		$rows = Yii::app()->db->createCommand($sql)->queryAll();		if (count($rows) > 0) {			foreach ($rows as $row) {                $sql1="SELECT step FROM sal_fivestep aleft outer join hrdev.hr_binding b ON a.username=b.user_idWHERE employee_name='".$row['name']."'";                $arr = Yii::app()->db->createCommand($sql1)->queryAll();                $a=array_column($arr,'step');                if($this->criteria->five=="all"){                    $b=array('1','2','3','4','5');                }                if($this->criteria->five=="one"){                    $b=array('1');                }                if($this->criteria->five=="two"){                    $b=array('1','2');                }                if($this->criteria->five=="three"){                    $b=array('1','2','3');                }                if($this->criteria->five=="four"){                    $b=array('1','2','3','4');                }                $a=array_diff($b,$a);                $comma_separated = implode(",", $a);				$temp = array();				$temp['start_dt'] = General::toDate($row['entry_time']);				$temp['staffs_desc'] = $row['code'];				$temp['name'] = $row['name'];				$temp['gangwei'] = $row['username'];				$temp['five'] = $a;				$this->data[] = $temp;			}		}		return true;	}	public function getReportName() {		$city_name = isset($this->criteria) ? ' - '.General::getCityName($this->criteria->city) : '';		return parent::getReportName().$city_name;	}}?>
