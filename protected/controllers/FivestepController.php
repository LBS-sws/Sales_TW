<?php

class FivestepController extends Controller 
{
	public $function_id='HK03';

	private $_allowedFiles = array(
			'bmp'  => 'image/bmp',
			'gif'  => 'image/gif',
			'jpeg' => 'image/jpeg',
			'jpg'  => 'image/jpeg',
			'png'  => 'image/png',
			'tif'  => 'image/tiff',
			'tiff' => 'image/tiff',

			'pdf' => 'application/pdf',		//'application/x-pdf',
			'txt' => 'text/plain',
			'rtf' => 'application/rtf',		//'text/rtf',

			'odt' => 'application/vnd.oasis.opendocument.text',
			'ott' => 'application/vnd.oasis.opendocument.text-template',
			'odp' => 'application/vnd.oasis.opendocument.presentation',
			'otp' => 'application/vnd.oasis.opendocument.presentation-template',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			'ots' => 'application/vnd.oasis.opendocument.spreadsheet-template',
			'odc' => 'application/vnd.oasis.opendocument.chart',
			'odf' => 'application/vnd.oasis.opendocument.formula',

			'doc'  => 'application/x-msword',	//'application/msword', 
			'xls'  => 'application/vnd.ms-excel',	//'application/excel', 
			'xlsm' => 'application/vnd.ms-excel.sheet.macroenabled.12',
			'ppt'  => 'application/vnd.ms-powerpoint',

			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

			'avi' => 'video/x-msvideo',
			'flv' => 'video/x-flv',
			'mov' => 'video/quicktime',
			'mp4' => 'video/vnd.objectvideo',
			'mpg' => 'video/mpeg',
			'wmv' => 'video/x-ms-wmv',

			'7z'  => 'application/x-7z-compressed', 	//'application/7z',
			'rar' => 'application/x-rar-compressed', 	//'application/rar',
			'zip' => 'application/x-zip-compressed', 	//'application/zip',
			'gz'  => 'application/x-gzip',				//'application/gzip',
			'tar' => 'application/x-tar', 				//'application/tar',
			'tgz' => 'application/gzip', 				//'application/tar', 'application/tar+gzip',

			'mp3' => 'audio/mpeg',
			'ogg' => 'application/ogg',
			'wma' => 'audio/x-ms-wma',
	);
	
	public function filters()
	{
		return array(
			'enforceRegisteredStation',
			'enforceSessionExpiration', 
			'enforceNoConcurrentLogin',
			'accessControl', // perform access control for CRUD operations
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
//			array('allow', 
//				'actions'=>array('pass','fail'),
//				'expression'=>array('FivestepController','allowApproval'),
//			),
//			array('allow', 
//				'actions'=>array('new','edit','delete','save','submit'),
//				'expression'=>array('FivestepController','allowGeneralUse'),
//			),
			array('allow', 
				'actions'=>array('new','edit','delete','save','ajaxsave'),
				'expression'=>array('FivestepController','allowReadWrite'),
			),
			array('allow', 
				'actions'=>array('index','view','showmedia','downloadmedia'),
				'expression'=>array('FivestepController','allowReadOnly'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($pageNum=0) 
	{

        $suffix = Yii::app()->params['envSuffix'];
        $firstDay = date("Y/m/d");
        $arr['start_dt'] = date("Y/m/d", strtotime("$firstDay - 6 day"));
        $arr['end_dt'] = $firstDay;
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
                $nocity = array('CN', 'CS', 'H-N', 'HB', 'HD', 'HD1', 'HK', 'HN', 'HN1', 'HN2', 'HX', 'HXHB', 'JMS', 'MO', 'MY', 'RN', 'TY', 'XM', 'ZS1', 'ZY');
                $sql_city = "select name from security$suffix.sec_city where code='$k'";
                $city = Yii::app()->db->createCommand($sql_city)->queryScalar();
                if (in_array($k, $nocity, true)) {
                } else {
                    //需要的销售
                    $sql_people = "select a.name,e.username from hr$suffix.hr_employee a
                              inner join  hr$suffix.hr_binding b on a.id=b.employee_id 
                              inner join  security$suffix.sec_user_access c on b.user_id=c.username  
                              inner join  security$suffix.sec_user d on c.username=d.username 
                              inner join  sales$suffix.sal_visit e on b.user_id=e.username
       where  c.system_id='sal' and c.a_read_write like '%HK01%' and  d.status='A' and a.city='$k' and   e.visit_dt >= '" . $arr['start_dt'] . "'and e.visit_dt <= '" . $arr['end_dt'] . "' and  a.staff_status =0";
                    $people = Yii::app()->db->createCommand($sql_people)->queryAll();
                    //邮件数据
                    if (!empty($people)) {
                        $people = array_unique($people, SORT_REGULAR);
                        $arr['sale'] = array_column($people, 'username');
                        $arr['sort'] = 'singular';
                        $arr_email = ReportVisitForm::Summary($arr);
                        $sum['money'] = array_sum(array_map(create_function('$val', 'return $val["money"];'), $arr_email));
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
                    }
                    print_r('<pre>');  print_r($people);  print_r('---');  print_r($arr_email);
                }
            }
        }

//		$model = new FivestepList;
//		if (isset($_POST['FivestepList'])) {
//			$model->attributes = $_POST['FivestepList'];
//		} else {
//			$session = Yii::app()->session;
//			if (isset($session[$model->criteriaName()]) && !empty($session[$model->criteriaName()])) {
//				$criteria = $session[$model->criteriaName()];
//				$model->setCriteria($criteria);
//			}
//		}
//		$model->determinePageNum($pageNum);
//		$model->retrieveDataByPage($model->pageNum);
//		$this->render('index',array('model'=>$model));
	}

/*
	public function actionPass() {
		if (isset($_POST['FivestepForm'])) {
			$model = new FivestepForm($_POST['FivestepForm']['scenario']);
			$model->attributes = $_POST['FivestepForm'];
			if ($model->validate()) {
				if ($model->status=='P') {
					$model->status = 'C';
					$model->saveData();
				}
				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
				$this->redirect(Yii::app()->createUrl('fivestep/view',array('index'=>$model->id)));
			} else {
				$message = CHtml::errorSummary($model);
				Dialog::message(Yii::t('dialog','Validation Message'), $message);
				$this->render('form',array('model'=>$model,));
			}
		}
	}

	public function actionFail() {
		if (isset($_POST['FivestepForm'])) {
			$model = new FivestepForm($_POST['FivestepForm']['scenario']);
			$model->attributes = $_POST['FivestepForm'];
			if ($model->validate()) {
				if ($model->status=='P') {
					$model->status = 'F';
					$model->saveData();
				}
				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
				$this->redirect(Yii::app()->createUrl('fivestep/view',array('index'=>$model->id)));
			} else {
				$message = CHtml::errorSummary($model);
				Dialog::message(Yii::t('dialog','Validation Message'), $message);
				$this->render('form',array('model'=>$model,));
			}
		}
	}

	public function actionSubmit() {
		if (isset($_POST['FivestepForm'])) {
			$model = new FivestepForm($_POST['FivestepForm']['scenario']);
			$model->attributes = $_POST['FivestepForm'];
			if ($model->validate()) {
				if ($model->status!='F' && $model->status!='C') {
					$model->status = 'P'
					$model->saveData();
				}
				Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Submission Done'));
				$this->redirect(Yii::app()->createUrl('fivestep/view',array('index'=>$model->id)));
			} else {
				$message = CHtml::errorSummary($model);
				Dialog::message(Yii::t('dialog','Validation Message'), $message);
				$this->render('form',array('model'=>$model,));
			}
		}
	}
*/
	public function actionSave() {
		if (isset($_POST['FivestepForm'])) {
			$model = new FivestepForm($_POST['FivestepForm']['scenario']);
			$model->attributes = $_POST['FivestepForm'];
			if ($model->validate()) {
				$flag = true;
				if ($file = CUploadedFile::getInstance($model,'filename')) {
//					$model->file_type = $file->type;
					$phyname = $file->tempName;
					if (!empty($phyname)) {
						$filename = hash_file('md5',$phyname);
						$ext = pathinfo($file->name,PATHINFO_EXTENSION);
						$filename .= '.'.$ext;
						$base = Yii::app()->params['docmanPath'];
						$path = $this->hashDirectory($base, $filename);
						if (rename($phyname, $path.'/'.$filename)) {
							$model->filename = $path.'/'.$filename;
							$model->filetype = $file->type;
						} else {
							$flag = false;
							Dialog::message(Yii::t('dialog','Error'), Yii::t('sales','Uploaded file cannot be saved'));
							$this->render('form',array('model'=>$model,));
						}
					}
				}
				if ($flag) {
					$model->saveData();
					Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
					$this->redirect(Yii::app()->createUrl('fivestep/edit',array('index'=>$model->id)));
				}
			} else {
				$message = CHtml::errorSummary($model);
				Dialog::message(Yii::t('dialog','Validation Message'), $message);
				$this->render('form',array('model'=>$model,));
			}
		}
	}

	public function actionAjaxsave() {
		$rtn = array('code'=>0);
		if (isset($_POST['FivestepForm'])) {
			$code = 0;
			$id = 0;
			$model = new FivestepForm($_POST['FivestepForm']['scenario']);
			$model->attributes = $_POST['FivestepForm'];
			if ($model->validate()) {
				$flag = true;
				if ($file = CUploadedFile::getInstance($model,'filename')) {
					$phyname = $file->tempName;
					if (!empty($phyname)) {
						$filename = hash_file('md5',$phyname);
						$ext = pathinfo($file->name,PATHINFO_EXTENSION);
						$filename .= '.'.$ext;
						$base = Yii::app()->params['docmanPath'];
						$path = $this->hashDirectory($base, $filename);
						if (rename($phyname, $path.'/'.$filename)) {
							$model->filename = $path.'/'.$filename;
							$model->filetype = $file->type;
						} else {
							$flag = false;
							$code = 3;
							$message = Yii::t('sales','Uploaded file cannot be saved');
						}
					}
				}
				if ($flag) {
					$model->saveData();
					Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
					$code = 1;
					$id = $model->id;
					$message = Yii::t('dialog','Save Done');
				}
			} else {
				$code = 2;
				$message = CHtml::errorSummary($model);
			}
			$rtn = array('code'=>$code, 'message'=>$message, 'id'=>$model->id);
		}
		echo json_encode($rtn);
	}

	public function actionView($index)
	{
		$model = new FivestepForm('view');
		if (!$model->retrieveData($index)) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else {
			$this->render('form',array('model'=>$model,));
		}
	}
	
	public function actionNew()
	{
		$model = new FivestepForm('new');
		$this->render('form',array('model'=>$model,));
	}
	
	public function actionEdit($index)
	{
		$model = new FivestepForm('edit');
		if (!$model->retrieveData($index)) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else {
            if($model['sup_score']==-1||$model['mgr_score']==-1||$model['dir_score']==-1){
                $model->toEmail($model['username']);
            }

			$this->render('form',array('model'=>$model,));
		}
	}
	
	public function actionDelete()
	{
		$model = new FivestepForm('delete');
		if (isset($_POST['FivestepForm'])) {
			$model->attributes = $_POST['FivestepForm'];
			$model->saveData();
			Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
			$this->redirect(Yii::app()->createUrl('fivestep/index'));
		}
	}
	
	public function actionShowmedia($index) {
		$model = new FivestepForm('view');
		if (!$model->retrieveData($index)) {
			throw new CHttpException(404,'The requested page does not exist.');
		} else {
			$content = $model->getMediaFile();
			switch($model->filetype) {
				case 'video/quicktime':
				case 'video/x-quicktime':
				case 'video/3gpp':
					$mediatype = 'video/mp4';
					break;
				default:
					$mediatype = $model->filetype;
			}
			echo "<source src='$content' type='$mediatype'>";
		}
	}

	public function actionDownloadmedia($index) {
		$model = new FivestepForm('view');
		if (!$model->retrieveData($index)) {
			throw new CHttpException(404,'File not found.');
		} else {
			$path = pathinfo($model->filename);
			$ext = $path['extension'];
			$filename = 'media.'.$ext;
			$file = $model->getMediaFile(true); // Raw Format
			$type = isset($this->_allowedFiles[$ext]) ? $this->_allowedFiles[$ext] : $model->filetype;
			header("Content-type:".$type); 
			header('Content-Disposition: attachment; filename="'.$filename.'"'); 
			header('Content-Length: ' . strlen($file));
			echo $file;
			Yii::app()->end();
		}
	}
	
	public static function allowReadWrite() {
		return Yii::app()->user->validRWFunction('HK03');
	}
	
	public static function allowReadOnly() {
		return Yii::app()->user->validFunction('HK03');
	}

//	public static function allowApproval() {
//		return Yii::app()->user->validFunction('CN01') && $this->allowReadWrite();
//	}
//
//	public static function allowGenralUse() {
//		return !Yii::app()->user->validFunction('CN01') && $this->allowReadWrite();
//	}
	
	protected function hashDirectory($basedir, $filename) {
		$hashcode = hash('md5',$filename);
		$firstDir = $hashcode & 255;
		$tmp = sprintf("%x",$firstDir);
		$path = $basedir.'/'.$tmp;
		if (!file_exists($path)) mkdir($path);
		$secondDir = ($hashcode >> 8) & 255;
		$tmp = sprintf("%x",$secondDir);
		$path .= '/'.$tmp;
		if (!file_exists($path)) mkdir($path);
		return $path;
	}

}
?>