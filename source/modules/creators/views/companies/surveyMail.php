<?php
echo '<p>'.Yii::t('surveyForm', 'From').': '.$user->forename.' '.$user->surname.' ('.$user->email.')</p>';
echo '<p>'.str_replace("\n", '<br />', htmlentities($surveyForm->message)).'</p>';
