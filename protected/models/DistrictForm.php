<?php

class DistrictForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $name;
	public $city;

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>Yii::t('code','Description'),
			'city'=>Yii::t('sales','City'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('name,city','required'),
			array('id','safe'), 
		);
	}

	public function retrieveData($index)
	{
		$sql = "select * from sal_cust_district where id=".$index." ";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		if ($row!==false) {
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->city = $row['city'];
		}
		return true;
	}
	
	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->save($connection);
			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update.');
		}
	}

	protected function save(&$connection)
	{
		$sql = '';
		switch ($this->scenario) {
			case 'delete':
				$sql = "delete from sal_cust_district where id = :id";
				break;
			case 'new':
				$sql = "insert into sal_cust_district(
						name, city, lcu, luu) values (
						:name, :city, :lcu, :luu)";
				break;
			case 'edit':
				$sql = "update sal_cust_district set 
					name = :name, 
					city = :city,
					luu = :luu
					where id = :id";
				break;
		}

		$uid = Yii::app()->user->id;

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':name')!==false)
			$command->bindParam(':name',$this->name,PDO::PARAM_STR);
		if (strpos($sql,':city')!==false)
			$command->bindParam(':city',$this->city,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);
		$command->execute();

		if ($this->scenario=='new')
			$this->id = Yii::app()->db->getLastInsertID();
		return true;
	}

	public function getCityList() {
		$suffix = Yii::app()->params['envSuffix'];
		$sql = "select code, name from security$suffix.sec_city order by name";
		$rows = Yii::app()->db->createCommand($sql)->queryAll();
		$rtn = array();
		foreach ($rows as $row) {
			$rtn[$row['code']] = $row['name'];
		}
		return $rtn;
	}
	
	public function isOccupied($index) {
		$rtn = false;
		$sql = "select a.id from sal_visit a where a.district=".$index." limit 1";
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		$rtn = ($row !== false);
		return $rtn;
	}
}
