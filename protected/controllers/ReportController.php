<?php
class ReportController extends Controller
{
    protected static $actions = array(
        'five'=>'HB02',
        'visit'=>'HA01',
        'city'=>'HA01',
        'fenxi'=>'HA01',
        'xiazai'=>'HA01',
        'staff'=>'HA02',
        'sale'=>'HA02',
        'down'=>'HA02',
        'turnover'=>'HA07',
        'turnoverdown'=>'HA07',
        'performance'=>'HA04',
        'performancelist'=>'HA04',
        'performancedown'=>'HA04',
        'citys'=>'HA04',
        'overtimelist'=>'YB02',
        'pennantexlist'=>'YB05',
        'pennantculist'=>'YB06',
        'leavelist'=>'YB03',
    );

    public function filters()
    {
        return array(
            'enforceRegisteredStation',
            'enforceSessionExpiration',
            'enforceNoConcurrentLogin',
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        $act = array();
        foreach ($this->action as $key=>$value) { $act[] = $key; }
        return array(
            array('allow',
                'actions'=>$act,
                'expression'=>array('ReportController','allowExecute'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionFive() {
		$this->function_id = self::$actions['five'];
		Yii::app()->session['active_func'] = $this->function_id;
		$model = new ReportH02Form;
        if (isset($_POST['ReportH02Form'])) {
            $model->attributes = $_POST['ReportH02Form'];

            if ($model->validate()) {
                $model->addQueueItem();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Report submitted. Please go to Report Manager to retrieve the output.'));
            }
             else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
            }
        }
        $this->render('form_five',array('model'=>$model));
    }

    public function actionVisit() {
		$this->function_id = self::$actions['visit'];
		Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if (isset($_POST['ReportVisitForm'])) {
            $model->attributes = $_POST['ReportVisitForm'];

        }
        $city=$model->city();
        $saleman=$model->saleman();
        $this->render('form_visit',array('model'=>$model,'city'=>$city,'saleman'=>$saleman));
    }

    public function actionStaff() {
		$this->function_id = self::$actions['staff'];
		Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if (isset($_POST['ReportVisitForm'])) {
            $model->attributes = $_POST['ReportVisitForm'];
        }
        //$city=$model->city();
        //$saleman=$model->saleman();
        $this->render('form_staff',array('model'=>$model));
    }

    public function actionSale(){
		$this->function_id = self::$actions['sale'];
		Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if (isset($_POST['ReportVisitForm'])) {
            $fenxi = $_POST['ReportVisitForm'];
            $model['all'] = $model->sale($fenxi);
        }
//        print_r('<pre/>');
//       print_r($model);
        $this->render('sale',array('model'=>$model,'fenxi'=>$fenxi));
    }

    public function actionPerformance() {
        $this->function_id = self::$actions['performance'];
        Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if (isset($_POST['ReportVisitForm'])) {
            $model->attributes = $_POST['ReportVisitForm'];
        }
        $city=$model->city();
        $saleman=$model->salepeople();
        if(!empty(Yii::app()->session['index'])){
            $model['start_dt']=Yii::app()->session['index']['start_dt'];
            $model['end_dt']=Yii::app()->session['index']['end_dt'];
            $model['city']=Yii::app()->session['index']['city'];
            $model['sort']=Yii::app()->session['index']['sort'];
            if(!empty(Yii::app()->session['index']['sale'])){
                $model['sale']=Yii::app()->session['index']['sale'];
            }

            $saleman=$model->salepeoples($model['city']);
        }

//        print_r('<pre/>');
//        print_r(   $model['sale']);
        $this->render('form_performance',array('model'=>$model,'city'=>$city,'saleman'=>$saleman));
    }

    public function actionPerformancelist() {
        $this->function_id = self::$actions['performancelist'];
        Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        $sum=array();
        if (isset($_POST['ReportVisitForm'])) {
            $post= $_POST['ReportVisitForm'];
            Yii::app()->session['index'] = $post;
            if(!empty($post['sale'])){
                $array=$model->Summary($post);
                $sum['money']= array_sum(array_map(create_function('$val', 'return $val["money"];'), $array));
                $sum['singular']= array_sum(array_map(create_function('$val', 'return $val["singular"];'), $array));
                $sum['svc_A7']= array_sum(array_map(create_function('$val', 'return $val["svc_A7"];'), $array));
                $sum['svc_B6']= array_sum(array_map(create_function('$val', 'return $val["svc_B6"];'), $array));
                $sum['svc_C7']= array_sum(array_map(create_function('$val', 'return $val["svc_C7"];'), $array));
                $sum['svc_D6']= array_sum(array_map(create_function('$val', 'return $val["svc_D6"];'), $array));
                $sum['svc_E7']= array_sum(array_map(create_function('$val', 'return $val["svc_E7"];'), $array));
                $sum['svc_F4']= array_sum(array_map(create_function('$val', 'return $val["svc_F4"];'), $array));
                $sum['svc_G3']= array_sum(array_map(create_function('$val', 'return $val["svc_G3"];'), $array));
                $sum['svc_A7s']= array_sum(array_map(create_function('$val', 'return $val["svc_A7s"];'), $array));
                $sum['svc_B6s']= array_sum(array_map(create_function('$val', 'return $val["svc_B6s"];'), $array));
                $sum['svc_C7s']= array_sum(array_map(create_function('$val', 'return $val["svc_C7s"];'), $array));
                $sum['svc_D6s']= array_sum(array_map(create_function('$val', 'return $val["svc_D6s"];'), $array));
                $sum['svc_E7s']= array_sum(array_map(create_function('$val', 'return $val["svc_E7s"];'), $array));
                $sum['svc_F4s']= array_sum(array_map(create_function('$val', 'return $val["svc_F4s"];'), $array));
                $sum['svc_G3s']= array_sum(array_map(create_function('$val', 'return $val["svc_G3s"];'), $array));
            }else{
                $array=array();
            }
        }else{
            $post=array();
            $array=array();
        }

//
//        print_r('<pre/>');
//        print_r( $sum);
        $this->render('performancelist',array('model'=>$model,'array'=>$array,'post'=>$post,'sum'=>$sum));
    }


    public function actionPerformancedown(){
        $this->function_id = self::$actions['down'];
        Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if(isset($_POST['RptFive'])){
            $arr = $_POST['RptFive'];
            if(isset($arr['sale'])){
                $model['all']=$model->Summary($arr);
                $model->performanceDatas($model);
            }
        }
//        print_r('<pre/>');
//        print_r($model);
    }


    public function actionCity()
    {
        if (isset($_POST['txt'])) {
        $city = $_POST['txt'];
        $suffix = Yii::app()->params['envSuffix'];
//        $sql="select code,name from hr$suffix.hr_employee WHERE  position in (SELECT id FROM hr$suffix.hr_dept where dept_class='sales') AND staff_status = 0 AND city='".$city."'";
//        $records = Yii::app()->db->createCommand($sql)->queryAll();

        $sql1 = "select a.name from hr$suffix.hr_employee a, hr$suffix.hr_binding b, security$suffix.sec_user_access c,security$suffix.sec_user d 
        where a.id=b.employee_id and b.user_id=c.username and c.system_id='sal' and c.a_read_write like '%HK01%' and c.username=d.username and d.status='A' and a.city='" . $city . "'";
        $records = Yii::app()->db->createCommand($sql1)->queryAll();
    }
//        $records=array_merge($records,$name);
        echo (json_encode($records,JSON_UNESCAPED_UNICODE));

    }

    public function actionCitys()
    {
        if (isset($_POST['txt'])) {
            $city = $_POST['txt'];
            $suffix = Yii::app()->params['envSuffix'];
            $city_allow = City::model()->getDescendantList($city);
            $city_allow .= (empty($city_allow)) ? "'$city'" : ",'$city'";

            $sql = "select a.name,d.username from hr$suffix.hr_employee a, hr$suffix.hr_binding b, security$suffix.sec_user_access c,security$suffix.sec_user d 
        where a.id=b.employee_id and b.user_id=c.username and c.system_id='sal' and c.a_read_write like '%HK01%' and c.username=d.username and d.status='A' and d.city in ($city_allow)";

            $records = Yii::app()->db->createCommand($sql)->queryAll();
        }
//        $records=array_merge($records,$name);
        echo (json_encode($records,JSON_UNESCAPED_UNICODE));
    }

    public function actionFenxi(){
		$this->function_id = self::$actions['fenxi'];
		Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if(isset($_POST['ReportVisitForm'])){
            $fenxi=$_POST['ReportVisitForm'];
            if($fenxi['bumen']=='yes'){
                $model['all']=$model->fenxi($fenxi);
            }else{
                $model['all']=array();
            }
            //print_r('<pre/>');
            $city_allow = City::model()->getDescendantList($fenxi['city']);
            if(!empty($city_allow)||!empty($fenxi['sale'])){
                $model['one']=$model->fenxione($fenxi);

            }else{
                $model['one']=array();
            }
        }

//        print_r('<pre/>');
//       print_r($fenxi);
       $this->render('fenxi',array('model'=>$model,'fenxi'=>$fenxi));
    }

    public function actionXiaZai(){
		$this->function_id = self::$actions['xiazai'];
		Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if(isset($_POST['RptFive'])){
            $arr = $_POST['RptFive'];
            if($arr['bumen']=='yes'){
                $model['all']=$model->fenxis($arr);
            }else{
                $model['all']=array();
            }

            $city_allow = City::model()->getDescendantList($arr['city']);
            if(!empty($city_allow)||!empty($fenxi['sale'])){
                $model['one']=$model->fenxiones($arr);
            }else{
                $model['one']=array();
            }
        }

       $model->retrieveDatas($model);
//        print_r('<pre/>');
//        print_r($model);
    }

    public function actionDown(){
		$this->function_id = self::$actions['down'];
		Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if(isset($_POST['RptFive'])){
            $arr = $_POST['RptFive'];
            $model['all']=$model->sales($arr);
            $model->retrieveData($model);
        }
//        print_r('<pre/>');
//        print_r($model);
    }

    public function actionTurnover() {
        $this->function_id = self::$actions['turnover'];
        Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if (isset($_POST['ReportVisitForm'])) {
            $model->attributes = $_POST['ReportVisitForm'];
        }
        $city=$model->city();
        $saleman=$model->salepeople();
        if(!empty(Yii::app()->session['index'])){
            $model['start_dt']=Yii::app()->session['index']['start_dt'];
            $model['end_dt']=Yii::app()->session['index']['end_dt'];
            $model['city']=Yii::app()->session['index']['city'];
            if(!empty(Yii::app()->session['index']['sale'])){
                $model['sale']=Yii::app()->session['index']['sale'];
            }

            $saleman=$model->salepeoples($model['city']);
        }

//        print_r('<pre/>');
//        print_r(   $model['sale']);
        $this->render('turnover',array('model'=>$model,'city'=>$city,'saleman'=>$saleman));
    }

    public function actionTurnoverDown(){
        $this->function_id = self::$actions['turnoverdown'];
        Yii::app()->session['active_func'] = $this->function_id;
        $model = new ReportVisitForm;
        if(isset($_POST['ReportVisitForm']['sale'])){
            $arr = $_POST['ReportVisitForm'];
            $model->TurnoverExcel($arr);
        }else{
            Dialog::message(Yii::t('dialog','Warning'), Yii::t('dialog','Please check the assigned person'));
            $this->redirect(Yii::app()->createUrl('report/turnover'));
        }
    }

    public static function allowExecute() {
        return Yii::app()->user->validFunction(self::$actions[Yii::app()->controller->action->id]);
    }
}
?>
