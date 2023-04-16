<?php 
/*
 * obecnie w assets/qr
 * 
 * przygotowane pod files/Item/12932, wymaga utworzonego katalogu
 * 
 * generowanie kodu QR (przez renderPartial)
 * filename - id elementu, np. Item->id
 * $class - typ/katalog w files, np. Item -> files/Item
 * id - id elementu, np. Item->id
 * 
 * TODO: obecnie generuje kod QR przy każdym ładowaniu
 * można sprawdzić istnienie obrazka z kodem poprzez if(file_exists,
 * ale wymaga to mechanizmu aktualizacji przy zmianie adresu firmy, produktu, usługi...
 */
?> 
<?php $this->widget('ext.qrcode.QRCodeGenerator',array(
	'data' => $data,
	'filename' => $filename.'_qrc.png',	
	//'filePath' => Yii::app()->getBasePath().'/../'.Yii::app()->file->filesPath.'/'.$class.'/'.$id,
	//'fileUrl' => Yii::app()->file->filesPath.'/'.$class.'/'.$id,
	'filePath' => Yii::app()->getBasePath().'/../assets/qr',
    //'fileUrl' => 'assets/qr',    
)) ?>