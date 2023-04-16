<?php

class SurveyForm extends CFormModel
{
	public $message;

  public function rules()
  {
      return array(
      	array('message', 'required', 'message'=>Yii::t('surveyForm', 'No message!'))
      );
  }
}
