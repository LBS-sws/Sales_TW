<?php
class SaleCommand extends CConsoleCommand
{
    public function run()
    {
        $suffix = Yii::app()->params['envSuffix'];
        $sql="UPDATE sales$suffix.sal_visit a ,security$suffix.sec_city b ,security$suffix.sec_user c 
              set a.username=b.incharge , a.shift='Y'          
              WHERE a.city=b.code AND a.username=c.username AND a.city=c.city AND c.status='I'";
        $command=Yii::app()->db->createCommand($sql)->execute();
    }
}