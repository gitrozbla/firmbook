<?php


class SpoolerCommand extends CConsoleCommand {
	public function run($args) {
        set_time_limit(0);
//        echo '<br>SpoolerCommand START';
        Spooler::sendPortion();
//        echo '<br>SpoolerCommand END';
	}
    
    public function run_org($args) {

		Yii::app()->controller->ajaxMode();

		Yii::app()->cron->run();
		echo 'ok';

	}
}

?>
