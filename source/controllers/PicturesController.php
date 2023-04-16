<?php
/**
 * Kontroler akcji dla zdjęcia.
 * 
 * @category controllers
 * @package product
 * @author 
 * @copyright (C) 2015
 */
class PicturesController extends Controller
{
    /**
     * Domyślna akcja.
     * @var string
     */
    public $defaultAction = 'show';
    
    /**
     * Akcje, do których użytkownik zawsze ma dostęp.
     * @return string
     */
    public function allowedActions()
    {
        return 'show, add';
    }
    
    public function actionAdd()
    {
    	$userfile = new UserFile('create');
    	
    	if(isset($_POST['UserFile'])) {
    		echo '<br/>wyslano plik';
    		print_r($_POST['UserFile']);
    		$userfile->attributes = $_POST['UserFile'];
    		echo '<br/>wyslano plik';
    		print_r($_FILES['UserFile']);
            // validate user input and redirect to the previous page if valid
            if ($userfile->validate()) {
            	echo '<br/>zapis pliku';
            	$userfile->save();
            }
    	}
    	
    	$this->render('picture-form', compact('userfile'));
    }
    
}