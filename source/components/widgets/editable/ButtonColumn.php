<?php

Yii::import('bootstrap.widgets.TbButtonColumn');

class ButtonColumn extends TbButtonColumn 
{
	/**
     * @var boolean whether the options values should be evaluated. 
     */
    public $evaluateHtmlOptions = false;
 
    protected function renderButton($id, $button, $row, $data)
	{			
		//przekazanie wartości do obsługi poprzez js z użyciem id elementu 
		if ($this->evaluateHtmlOptions && isset($button['options']['id']))			
			$button['options']['id'] = $this->evaluateExpression($button['options']['id'], 	array('row' => $row, 'data' => $data));			
				
		parent::renderButton($id, $button, $row, $data);
	}
    
     /**
     * Renders a data cell.
     * @param integer $row the row number (zero-based)
     * Overrides the method 'renderDataCell()' of the abstract class CGridColumn
     */
   /* public function renderDataCell($row)
    {
            $data=$this->grid->dataProvider->data[$row];
            if($this->evaluateHtmlOptions) {
                foreach($this->htmlOptions as $key=>$value) {
                    $options[$key] = $this->evaluateExpression($value.$data->id,array('row'=>$row,'data'=>$data));
                }
            }
            else $options=$this->htmlOptions;
            if($this->cssClassExpression!==null)
            {
                    $class=$this->evaluateExpression($this->cssClassExpression,array('row'=>$row,'data'=>$data));
                    if(isset($options['class']))
                            $options['class'].=' '.$class;
                    else
                            $options['class']=$class;
            }
            echo CHtml::openTag('td',$options);
            $this->renderDataCellContent($row,$data);
            echo '</td>';
    }*/
    
}
