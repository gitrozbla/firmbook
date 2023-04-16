<?php
/**
 * Kontroler testowy.
 * 
 * Nie implementuje on żadnych funkcjonalności.
 * Nie są to testy jednostkowe ani akceptacyjne.
 * Akcje służą do testowania nowych linijek skryptu
 * oraz do generowania wpisów w bazie i plików użytkownika.
 * 
 * Akcje powinny być wywoływanie ze szczególnym zachowaniem ostrożności!
 * Dwukrotne wywołanie niektórych operacji może spowodować zduplikowanie
 * rekordów w bazie lub uszkodzenie bazy!
 * 
 * @category controllers
 * @package test
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class TestController extends Controller
{
    public function allowedActions()
    {
//        return 'cache, newemail, contact, phpinfo, alerts';
//        return 'test';
    }
    
    public function actionTest()
    {
        $date = new DateTime('2019-11-20');
        

        $date->modify('-1 day');
        $params = ['date'=>$date->format('Y-m-d')];
        $condition = 'active = 1 AND date >= :date';
//        $items = Item::model()->with('company', 'product', 'service', 'category', 'package', 'product.company', 'service.company', 'product.company.item', 'service.company.item')->findAll($condition, $params);
        $items = Item::model()->with('company', 'product', 'service', 'category', 'package', ['product.company' => 'product_company'], ['product_company.item' => 'product_company_item'], ['service.company' => 'service_company'], ['service_company.item' => 'service_company_item'])->findAll($condition, $params);
        var_dump($items);
        return '';
    }        
    
//    public function actionAlerts($jsload=false)
//    {       	
//		$alert = new Alert();
//        $alert->unsetAttributes();
//        
//        if(isset($_GET['Alert']))
//            $alert->attributes = $_GET['Alert'];        
//        
//        $alert->user_id = Yii::app()->user->id;        
//        
//        $params =array(
//            'alert'=>$alert,
//        );
//        
//        if($jsload=='true')
//        	Yii::app()->clientscript->scriptMap['jquery-2.1.0.min.js'] = false;
//        else
//        	Yii::app()->clientscript->scriptMap['*.js'] = false;        		
//        
//        $this->renderPartial('/alerts/show', $params, false, true);
//
//    }
    
    /**
     * Akcja domyślna.
     */
    public function actionIndex()
    {error_log('test');
        /*$user = User::model()->findByPk('1');
        
        $this->render('editable', compact('user'));*/
    }
    
    /**
     * Akcja testowa dla EditableSaver.
     * @see EditableSaver
     */
    public function actionUpdate()
    {
        Yii::import('application.components.widgets.EditableSaver', true);
        $es = new EditableSaver('User');
        $es->scenario = 'update';
        $es->update();
    }
    /**
     * Testuje połączenie z serwerem pocztowym
     * (wysyła mail testowy).
     */
    public function actionMail()
    {
        // debug mode
        Yii::app()->mailer->SMTPDebug = true;
        
        // confirm email
        $this->layout = 'mail';
        Yii::app()->mailer->systemMail(
            Yii::app()->params['admin']['email'],
            'Test mail',
            'Content'
        );
    }
    
    /**
     * Wyświetla informacje o php.
     */
    public function actionPhpinfo()
    {
        echo phpinfo();
    }
    
    public function actionJavascriptTest()
    {
        $this->render('javascriptTest');
    }
	
	public function actionFix_items()
	{
		$items = Item::model()->findAll();
		//$item = Item::model()->findByPk(37359);
		foreach($items as $item) {
			$item->name = $item->name;
			$item->description = $item->description;
			$item->save(false);
		}
		echo (count($items));
	}
	
	
    // płatność gotówką - osobne pole
    /*public function actionConvertCashPayment()
    {
        Yii::app()->db->createCommand()
             ->update('tbl_company', array(
               'payment_cash' => 1
        ), 'payment_type LIKE "%8%"');

        echo 'done';
    }*/
	
	// darmowa wysyłka - osobne pole
    /*public function actionConvertFreeDelivery()
    {
        Yii::app()->db->createCommand()
             ->update('tbl_company', array(
               'free_delivery' => 1
        ), 'delivery_type LIKE "%4%"');
    }*/
	
    
    /**
     * Generowanie kategorii.
     */
    public function actionCategory() 
    {    
        // rebuild category table
        /*
        ini_set('max_execution_time', 3000);
        $categories = Yii::app()->db->createCommand()
            ->select('id, name, parent')
            ->from('category c')
            //->where('id=:id', array(':id'=>$id))
            ->queryAll();
        
        $counter = 0;
        
        foreach($categories as $c) {
            echo $c['name'];
            
            $category = Category::model()->findByPk($c['id']);
            if (!$category) {
                
                // parent category
                if ($c['parent'] != null) {
                    $parent = Category::model()->findByPk($c['parent']);
                    if ($parent) {   
                        $category = new Category;
                        $category->id = $c['id'];
                        $category->name = $c['name'];
                        $category->appendTo($parent);
                        $counter++;
                    }
                } else {
                    $category = new Category;
                    $category->id = $c['id'];
                    $category->name = $c['name'];
                    $category->saveNode();
                    $counter++;
                }
            }
        }
        
        echo $counter.' inserted';
         */
        
        
        //move home and garden nested content level up
        /*
        ini_set('max_execution_time', 30);
        $homeGarden = Category::model()->findByPk(2729);
        $homeGarden2 = Category::model()->findByPk(2730);
        $children = $homeGarden2->children()->findAll();
        foreach ($children as $child) {
            echo $child->name.'<br />';
            $child->moveAsLast($homeGarden);
        }
        $homeGarden2->deleteNode();
        */
        
        
        // remove brackets
        /*
        ini_set('max_execution_time', 30);
        $categories = Yii::app()->db->createCommand()
            ->select('id, name')
            ->from('tbl_category')
            ->queryAll();
        foreach($categories as $c) {
            $name = $c['name'];
            if(Func::startsWith($name, '[')) {
                echo $name.' ';
                $name = substr($name, 1);
                if(Func::endsWith($name, ']')) {
                    $name = substr($name, 0, -1);
                }
                
                Yii::app()->db->createCommand()
                    ->update('tbl_category', array(
                        'name'=>$name,
                    ), 'id=:id', array(':id'=>$c['id']));
            }  
        }
         */
        
        
        // generate aliases
        /*ini_set('max_execution_time', 300);
        $categories = Yii::app()->db->createCommand()
            ->select('id, name, alias')
            ->from('tbl_category')
            ->queryAll();
        foreach($categories as $c) {
            $alias = $c['alias'];
            if (is_numeric($alias)) {
                echo $c['alias'].' ';
                $alias = $c['name'];
                $alias = str_replace(
                        array('&', ' ', ','), 
                        array('and', '-', ''),
                        $alias);
                $alias = strtolower($alias);
                
                // search
                $row = Yii::app()->db->createCommand()
                    ->select('id')
                    ->where('alias=:alias', array(':alias'=>$alias))
                    ->from('tbl_category')
                    ->queryRow();
                if ($row) {
                    $i=1;
                    $aliasPart = $alias;
                    do {
                        $i++;
                        $alias = $aliasPart.'-'.$i;
                        $row = Yii::app()->db->createCommand()
                            ->select('id')
                            ->where('alias=:alias', array(':alias'=>$alias))
                            ->from('tbl_category')
                            ->queryRow();
                    } while ($row);
                }
                Yii::app()->db->createCommand()
                    ->update('tbl_category', array(
                        'alias'=>$alias,
                    ), 'id=:id', array(':id'=>$c['id']));
            }
        }*/
        
        
        // copy translations for level 1 and 2
        /*ini_set('max_execution_time', 30);
        $maxId = Yii::app()->db->createCommand()
            ->select('MAX(id)')
            ->from('tbl_source_message')
            ->queryScalar();
        echo 'MaxId: .'.$maxId.' ';
        $categories = Yii::app()->db->createCommand()
            ->select('id, name, alias')
            ->from('tbl_category')
            ->where('level=1 or level=2')
            ->queryAll();
        foreach($categories as $c) {
            $translation = Yii::app()->db->createCommand()
                ->select('')
                ->from('tbl_source_message')
                ->where("category='category.alias' and message=:message", array(
                    ':message'=>$c['alias']
                ))
                ->queryRow();
            if (!$translation) {
                echo $c['name'].' ';
            
                $maxId++;
                Yii::app()->db->createCommand()
                    ->insert('tbl_source_message', array(
                        'id'=>$maxId,
                        'category'=>'category.name',
                        'message'=>$c['name']
                    ));
                Yii::app()->db->createCommand()
                    ->insert('tbl_message', array(
                        'id'=>$maxId,
                        'language'=>'pl',
                        'translation'=>$c['name']
                    ));
                $maxId++;
                Yii::app()->db->createCommand()
                    ->insert('tbl_source_message', array(
                        'id'=>$maxId,
                        'category'=>'category.alias',
                        'message'=>$c['alias']
                    ));
                Yii::app()->db->createCommand()
                    ->insert('tbl_message', array(
                        'id'=>$maxId,
                        'language'=>'pl',
                        'translation'=>$c['alias']
                    ));
            }
        }*/
        
        // sort categories
        /*ini_set('max_execution_time', 30000);
        $roots = Category::model()->roots()->findAll();
        // root are sorted by query (order by name)
        foreach($roots as $c) {
            $this->sortCategories($c);
        }*/
        
        // update category id in messages
        /*ini_set('max_execution_time', 3000);
        $reader = Yii::app()->db->createCommand()
            ->select('id, message')
            ->from('tbl_source_message')
            ->where('category="category.name" and object_id is null')
            //->limit(3)
            ->query();
        foreach($reader as $row) {
            //var_dump($row);
            $id = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('tbl_category')
                    ->where('name=:name', array(':name'=>$row['message']))
                    ->queryScalar();
            //var_dump($id);
            Yii::app()->db->createCommand()
                ->update('tbl_source_message', array(
                    'object_id'=>$id,
                ), 'id=:id', array(':id'=>$row['id']));
        }*/
    	
    	
    	// usunuwanie tlumaczen do nieistniejacych kategorii    	
    	/*$reader = Yii::app()->db->createCommand()
    		->from('tbl_source_message')
    		->where('category="category.alias"')
    		->limit(100)
    		->queryAll();
    	
    	foreach($reader as $row) {
    		echo '<br/>';
    		echo '<br/>';
    		echo 'alias:'.$row['message'];
    		
    		$cat = Yii::app()->db->createCommand()
    		->from('tbl_category')
    		->where('id='.$row['object_id'])
    		->queryRow();
    		
    		if($cat) {
    			echo '<br/>';
    			echo 'kategoria istnieje: '.$cat['id'];
    		} else {
    			echo '<br/>';
    			echo 'kategoria NIE istnieje';
    			
    			Yii::app()->db->createCommand()
    				->delete('tbl_message', 'id=:id', array(':id'=>$row['id']));
    			
    			Yii::app()->db->createCommand()
    				->delete('tbl_source_message', 
    					'object_id=:object_id and (category="category.alias" or category="category.name")', 
    					array(':object_id'=>$row['object_id']));    			
    		}    		
    	}*/	
    	// KONIEC - usunuwanie tlumaczen do nieistniejacych kategorii
    	
    	// modyfikacja wartości source_message.message dla nazw kategorii z nazwy na alias 
    	/*$reader = Yii::app()->db->createCommand()
    	 ->from('tbl_source_message')
    	 ->where('category="category.alias"')
    	 ->limit(500)
    	 ->queryAll();
    	
    	foreach($reader as $row) {
    		echo '<br/>';
    		echo '<br/>';
    		echo 'alias:'.$row['message'];
    		echo 'object_id:'.$row['object_id'];
    	
    		Yii::app()->db->createCommand()
    			->update('tbl_source_message', 
    				array('message'=>$row['message']),
    				'object_id=:object_id and category="category.name"',
    				array(':object_id'=>$row['object_id']));
    					
    	}  */  	
    	// KONIEC
    }
    
    /**
     * Sortowanie kategorii (nested set).
     * @see NestedSetBehavior
     * @param type $category
     */
    public function sortCategories($category) {
        /*echo '<br />'.$category->id.' ';
        
        $categories = $category->children()->findAll();
        // convert
        $tab = array();
        foreach($categories as $c) {
            $tab[$c->name] = $c->id;
        }
        ksort($tab);
        foreach($tab as $key=>$c) {
            echo $key.' ';
            $subcategory = Category::model()->findByPk($c);
            $subcategory->moveAsLast($category);
            $this->sortCategories($subcategory);
        }*/
    }
    
    /**
     * Generowanie obiektó Item.
     */
    public function actionItem() 
    {
        // generate aliases
        /*ini_set('max_execution_time', 30);
        $items = Yii::app()->db->createCommand()
            ->select('id, name, alias')
            ->from('tbl_item')
            ->queryAll();
        foreach($items as $item) {
            $alias = $item['alias'];
            if (is_numeric($alias)) {
                echo $item['id'].' ';
                $alias = $item['name'];
                $alias = str_replace(
                        array('&', ' ', ','), 
                        array('and', '-', ''),
                        $alias);
                $alias = strtolower($alias);
                
                // search
                $row = Yii::app()->db->createCommand()
                    ->select('id')
                    ->where('alias=:alias', array(':alias'=>$alias))
                    ->from('tbl_item')
                    ->queryRow();
                if ($row) {
                    $i=1;
                    $aliasPart = $alias;
                    do {
                        $i++;
                        $alias = $aliasPart.'-'.$i;
                        $row = Yii::app()->db->createCommand()
                            ->select('id')
                            ->where('alias=:alias', array(':alias'=>$alias))
                            ->from('tbl_item')
                            ->queryRow();
                    } while ($row);
                }
                Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'alias'=>$alias,
                    ), 'id=:id', array(':id'=>$item['id']));
            }
        }*/
        
        // generate items
        /*ini_set('max_execution_time', 3000);
        $items = Yii::app()->db->createCommand()
            ->select('id')
            ->from('tbl_item')
            ->where('type="product"')
            ->queryAll();
        foreach($items as $item) {

            // search
            $row = Yii::app()->db->createCommand()
                ->select('id')
                ->from('tbl_product')
                ->where('item_id=:item_id', array(':item_id'=>$item['id']))
                ->queryRow();
            if (!$row) {
                echo $item['id'].' ';
                Yii::app()->db->createCommand()
                    ->insert('tbl_product', array(
                        'item_id'=>$item['id']
                    ));
            }
        }*/
        
        // fix aliases
        /*ini_set('max_execution_time', 30);
        $reader = Yii::app()->db->createCommand()
            ->select('id, alias')
            ->from('tbl_item')
            ->query();
        foreach($reader as $row) {
            $newAlias = str_replace(',', '-', $row['alias']);
            if ($newAlias != $row['alias']) {
                echo $row['id'].' '.$row['alias'].' ';
                Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'alias'=>$newAlias,
                    ), 'id=:id', array(':id'=>$row['id']));
            }
        }*/
        
        // seocnd fix aliases
        /*ini_set('max_execution_time', 30);
        $reader = Yii::app()->db->createCommand()
            ->select('id, name, alias')
            ->from('tbl_item')
            ->order('id desc')
            ->query();
        foreach($reader as $row) {
            if (is_numeric($row['alias'])) {
                $newAlias = strtolower(str_replace(' ', '-', $row['name']));
                echo ', '.$row['id'].' '.$row['name'].' '.$row['alias'].' ';
                
                $duplicate = Yii::app()->db->createCommand()
                ->select('id')
                ->from('tbl_item')
                ->where('alias=:alias', array('alias'=>$newAlias))
                ->queryRow();
                if (!empty($duplicate)) {
                    Yii::app()->db->createCommand()
                    ->delete('tbl_item', 'id=:id', array(':id'=>$row['id']));
                    echo "[DEL]";
                } else {
                    Yii::app()->db->createCommand()
                        ->update('tbl_item', array(
                            'alias'=>$newAlias,
                        ), 'id=:id', array(':id'=>$row['id']));
                }
            }
        }*/
        
        //$this->disableWebLogs();
        
        // add messages for searching (old version)
        /*ini_set('max_execution_time', 3000);
        $reader = Yii::app()->db->createCommand()
            ->select('id, name')
            ->from('tbl_item')
            //->limit(3)
            ->query();
        $maxId = Yii::app()->db->createCommand()
                ->select('MAX(id)')
                ->from('tbl_source_message')
                ->queryScalar();
        echo $maxId.'<br />';
        foreach($reader as $row) {
            $duplicate = Yii::app()->db->createCommand()
                ->select('id')
                ->from('tbl_source_message')
                ->where('category=:category and object_id=:object_id', array(
                    ':category'=>'item.id',
                    ':object_id'=>$row['id'],
                    ))
                ->queryRow();
            if (!$duplicate) {
                echo $row['name'].'<br />';
                $maxId++;
                Yii::app()->db->createCommand()
                    ->insert('tbl_source_message', array(
                        'id'=>$maxId,
                        'category'=>'item.id',
                        'object_id'=>$row['id'],
                        'message'=>'{name}'
                    ));
                Yii::app()->db->createCommand()
                    ->insert('tbl_message', array(
                        'id'=>$maxId,
                        'language'=>'en',
                        'translation'=>$row['name']
                    ));
            }
        }*/
        
        // fix item description
        /*ini_set('max_execution_time', 3000);
        $prefixes = array('<td style="padding-left:15px;">', '<br />');
        $postfixes = array('</td>', '<br />');
        $reader = Yii::app()->db->createCommand()
            ->select('id, description')
            ->from('tbl_item')
            //->limit(3)
            ->query();
        foreach($reader as $row) {
            $changed = false;
            $newDescription = $row['description'];
            foreach($prefixes as $prefix) {
                if (substr($newDescription, 0, strlen($prefix)) == $prefix) {
                    $changed = true;
                    $newDescription = substr($newDescription, strlen($prefix));
                    break;
                }
            }
            foreach($postfixes as $postfix) {
                if (substr($newDescription, -strlen($postfix)) == $postfix) {
                    $changed = true;
                    $newDescription = substr($newDescription, 0, -strlen($postfix));
                    break;
                }
            }
            if ($changed) {
                //var_dump($newDescription);
                echo $row['id'].'   ';
                Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'description'=>$newDescription,
                    ), 'id=:id', array(':id'=>$row['id']));
                
            }
        }*/
    }
    
    /**
     * Generowanie plików użytkownika.
     */
    public function actionFiles() {
        
        // convert blobs to files
        /*ini_set('max_execution_time', 3000);
        
        $reader = Yii::app()->db->createCommand()
            ->select('*')
            ->from('files')
            ->where('converted=0')
            ->limit(2000)
            //->where('converted=0')->queryRow();
            ->query();
        
        foreach($reader as $row) {
            $hash = Func::randomString(16);
            
            // save files
            $sizes = array(
                'original'=>'', 
                'micro'=>'_xs', 
                'small'=>'_s', 
                'medium'=>'_m', 
                'large'=>'_l', 
            );
            foreach($sizes as $key=>$value) {
                $file = Yii::app()->file->set('Product/'.$row['id'].'/'.$hash.$value.'.jpg');
                $file->create();
                $file->setContents($row[$key], true, FILE_BINARY);
            }
            
            // save row
            Yii::app()->db->createCommand()
                    ->insert('tbl_files', array(
                        'path'=>'Product/'.$row['id'],
                        'filename'=>$hash,
                        'extension'=>'jpg',
                        'x_small'=>'1',
                        'small'=>'1',
                        'medium'=>'1',
                        'large'=>'1',
                        'original'=>'1',
                    ));
            
            // check as converted
            Yii::app()->db->createCommand()
                    ->update('files', array(
                        'converted'=>'1',
                    ), 'id=:id', array(':id'=>$row['id']));
        }*/
        
        
        // check data
        /*ini_set('max_execution_time', 30);
        
        Yii::app()->file->filesPath;    // force set cwd
        
        $reader = Yii::app()->db->createCommand()
            ->select('id, class, data_id, filename, extension')
            ->from('tbl_file')
            //->where('converted=0')->queryRow();
            ->query();
        
        foreach($reader as $row) {
            $path = 
            $row['class'].'/'.
            $row['data_id'].'/'.
            $row['filename'].
            '.'.$row['extension'];
            if (!file_exists($path)) {
                echo $row['id'].' ';
                echo getcwd().'/'.$path;
                exit();
            }
        }*/
        
        // shift folders
        /*ini_set('max_execution_time', 3000);
        
        Yii::app()->file->filesPath;    // force set cwd
        //
        for ($i=798; $i<=17918; $i++) {
            $oldName = 'Item/'.($i+1);
            $newName = 'Item/'.$i;
            rename($oldName, $newName);
        }*/
        
        // remove xs size item files
        /*ini_set('max_execution_time', 3000);
        
        Yii::app()->file->filesPath;    // force set cwd
        
        $reader = Yii::app()->db->createCommand()
            ->select('id, filename, extension')
            ->from('tbl_file')
            ->where('id>:min', array(
                ':min' => 10000
                ))
            //->where('converted=0')->queryRow();
            ->query();
        
        foreach($reader as $row) {
            $path = 'Item/'.$row['id'].'/'.$row['filename'].'_xs.'.$row['extension'];
            $file = Yii::app()->file->set($path);
            if ($file->exists) {
                echo $path.'<br />';
            }
            $file->delete();
        }*/
        
    }
    
    /**
     * Generowanie firm.
     */
    public function actionCompany() {
        
        // generate companies
        /*ini_set('max_execution_time', 3000);
        $maxId = Yii::app()->db->createCommand()
            ->select('MAX(id)')
            ->from('tbl_item')
            ->queryScalar();
        
        $reader = Yii::app()->db->createCommand()
            ->select('id, buyer, seller, company_name, category1, description')
            ->from('customer')
            //->limit(1000)
            ->query();
        foreach($reader as $customer) {
            $inserted = Yii::app()->db->createCommand()
                ->select('id')
                ->from('tbl_item')
                ->where('old_company_id=:id', array('id'=>$customer['id']))
                ->queryRow();
            if (!$inserted) {
                echo $customer['id'].' ';
                $maxId++;
                
                Yii::app()->db->createCommand()
                    ->insert('tbl_item', array(
                        'id'=>$maxId,
                        'old_company_id'=>$customer['id'],
                        'buy'=>$customer['buyer'],
                        'sell'=>$customer['seller'],
                        'category_id'=>$customer['category1'],
                        'name'=>$customer['company_name'],
                        'alias'=>$customer['id'],
                        'description'=>$customer['description']
                    ));
                
                Yii::app()->db->createCommand()
                    ->insert('tbl_company', array(
                        'item_id'=>$maxId
                    ));
            }
        }*/
        
        // description fix
        /*ini_set('max_execution_time', 30);
        $reader = Yii::app()->db->createCommand()
            ->select('id, description')
            ->from('tbl_item')
            ->query();
        foreach($reader as $item) {
            if (strpos($item['description'], '?') != false) {
                echo $item['id'].' ';
                $newDescription = str_replace('?á', '<br />', $item['description']);
                Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'description'=>$newDescription,
                    ), 'id=:id', array(':id'=>$item['id']));
            } 
        }*/
        
        // description fix
        /*ini_set('max_execution_time', 30);
        $reader = Yii::app()->db->createCommand()
            ->select('id, description')
            ->from('tbl_item')
            ->query();
        foreach($reader as $item) {
            if (strpos($item['description'], '?') != false) {
                echo $item['id'].' ';
                $newDescription = str_replace('?á', '<br />', $item['description']);
                Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'description'=>$newDescription,
                    ), 'id=:id', array(':id'=>$item['id']));
            } 
        }*/
        
        // copy data
        /*ini_set('max_execution_time', 3000);
        $reader = Yii::app()->db->createCommand()
            ->select('c.id, c.company_phone, i.id item_id')
            ->from('customer c')
            ->join('tbl_item i', 'i.old_company_id=c.id')
            ->where('c.id > 0')
            ->query();
        foreach($reader as $item) {echo $item['id'].' ';
            Yii::app()->db->createCommand()
                ->update('tbl_company', array(
                    'phone'=>$item['company_phone'],
                ), 'item_id=:item_id', array(':item_id'=>$item['item_id']));
        }*/
        
    }
    
    /**
     * Konwersja opisów - usuwanie 'amps;'.
     */
    public function actionAmps() {
        return;
        ini_set('max_execution_time', 300);
        
        $categories = Category::model()->findAll();
        foreach($categories as $category) {
            echo $category->name;
            $category->name = str_replace('&amp;', '&', $category->name);
            $category->saveNode();
        }
    }
    
    public function actionCache() {
        return;
        echo 'actionCache';
        
//        $cache = Yii::app()->getComponent('cache');
        $cache = Yii::app()->spool_cache;
//        var_dump(Yii::app()->cache);
//        Yii::app()->cache->set('mojeimie', 'Blazej');
        $cache->set('mojeimie', 'Blazej2');
        echo '<br>mojeimie: '.$cache->get('mojeimie');
        $cache->delete('mojeimie');
        echo '<br>mojeimie: '.$cache->get('mojeimie');
    }
    
    public function actionSpool()
    {
        return;
        $spoolProvider = new ActiveDataProvider('Spool', array(
            'criteria' => array(
                'order' => 't.date',
                'limit' => 3,                                                
            ),
            'pagination' => false            
        ));
        $messages = $spoolProvider->getData();
        foreach($messages as $message)
        {
            echo '<br>';
            echo '<br>id: '.$message->id;
        }    
    }        
    
    public function actionNewEmail() {
        return;
        echo 'actionNewEmail';
//        $data = new ActiveDataProvider('Item', array(
//            'criteria' => array(
//                'order' => 't.cache_package_id DESC, t.id DESC',// same order as sorting by date
//                'limit' => 40,
//                'with' => array(
//                    //$type
//                    'thumbnail',
//                ),
//                'together' => true,
//                'condition' => 't.'.$action.'=1'
//                    .' and t.active=1'
//                    ." and t.cache_type='".$type[0]."'"
//            		." and t.cache_package_id != 1",
//                    //." and t.cache_package_id != 0",
//            ),
//            'pagination' => false,
//            'groupSize' => 8,
//        ));
//        $criteria = new CDbCriteria
        $date = new DateTime('2019-10-02');
        $date->modify('-1 day');
//        $condition = 'date > :date';
        $params = ['date'=>$date->format('Y-m-d')];
//        $condition = 'active=0';
        $condition = 'active = 1 AND date >= :date';
//        $items = Item::model()->findAll($condition);
        $items = Item::model()->with('company', 'product', 'service')->findAll($condition, $params);
//        var_dump($items);
        foreach($items as $item)
        {
            echo '<br>';
            echo '<br>id: '.$item->id;
            echo '<br>date: '.$item->date;
            echo '<br>cache_type: '.$item->cache_type;
            if($item->cache_type == 'c')
                $object = $item->company;
            elseif($item->cache_type == 'p')
                $object = $item->product;
            else                
                $object = $item->service;
            echo '<br>item_id: '.$object->item_id;
        }    
        
        echo '<br><br><br>uzytkownicy:';
        $condition = 'active = 1 AND verified=1 AND ban = 0';
        $users = User::model()->findAll($condition);
        echo '<br>ilosc: '.count($users);
        foreach($users as $user)
        {
            echo '<br>email: '.$user->email;
        }
        
        $user = User::model()->findByPk(Yii::app()->user->id);
        
        $spool = new Spool;
        $spool->spool_cache_key = 'new-items-email-'.$user->id;
        $spool->email = 'seointercode@gmail.com';
        $spool->subject = 'Test spoolera';
        $spool->save();
                
        echo '<br>actionNewEmail end';
        
        
        
        Yii::app()->mailer->systemMail(
            'seointercode@gmail.com',    
            Yii::t('elists', 'Added to elist', [], null, $user->language),
            $this->render('/other/newItemsEmail', compact('user'), true, true)        
//            $emailData
        );
    }
    
    public function actionEmailCache() {
        return;
        echo 'actionEmailCache';
        $this->layout = 'mail';      
        echo '<br>email: '.Yii::app()->user->id;
        $user = User::model()->findByPk(Yii::app()->user->id);
        var_dump($user);
        Yii::app()->mailer->systemMail(
            'seointercode@gmail.com',    
            Yii::t('elists', 'Added to elist', [], null, $user->language),
            $this->render('/other/newItemsEmail', compact('user'), true, true)        
//            $emailData
        );
        
        echo '<br>actionEmailCache end';
    }
    
    /**
     * Usuwa cache frameworku.
     */
    public function actionClear_cache() {
//        return;
        $cache = Yii::app()->getComponent('cache');
        if($cache !== null){
            $cache->flush();
            Yii::app()->user->setFlash('success', 'Cache został usunięty.');
        }
        else {
            Yii::app()->user->setFlash('error', 'Wystąpił błąd podczas usuwania cache!');
        }
        
        $this->redirect(Yii::app()->homeUrl);
    }
    
    /*
     * ustawia pakiet dla obietków item
     */
    public function actionItemPackage() {
    	/*$users = User::model()->findAll();
    	foreach($users as $user)
    	{
    		// update items package cache
            	Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'cache_package_id'=>$user->package_id
                    ), 'user_id=:user_id', array('user_id'=>$user->id));		
    	}
    	Yii::app()->db->createCommand()
                    ->update('tbl_item', array(
                        'cache_package_id'=>1
                    ), 'cache_package_id=0');
        */            
    }
    
    
    /**
     * Czyszczenie bazy danych z zasobów użytkownika system - id = 2
     */
    public function actionNowe_kategorie() {
    	return;
    	//ini_set('max_execution_time', 3000);
    	
    	//local
    	/*$userId = 2;    	
    	$categorySystemId = 5275;
    	$categoryOtherId = 5276;*/
    	    	     	
    	//public
    	$userId = 2;
    	$categorySystemId = 5380;
    	$categoryOtherId = 5381;
    	
    	    	
    	//krok 1 - zmiana kategorii dla obiektow uzytkownika system na kategorie Temp system
    	
    	/*echo 'krok 1 - start';
    	
    	Yii::app()->db->createCommand()
	    	->update('tbl_item', array(
	    			'category_id'=>$categorySystemId
	    	), 'user_id='.$userId);
	    
	    echo 'krok 1 - koniec';*/
    	
    	
    	//krok 2 - zmiana kategorii dla pozostałych obiektow ze "starych kategorii" na Temp other
    	
    	/*echo 'krok 2 - start';
    	     	 
    	 $categoryOldMaxId = 5269;
    	 
    	 Yii::app()->db->createCommand()
	    	 ->update('tbl_item', array(
	    	 	'category_id'=>$categoryOtherId
    	 ), 'category_id<='.$categoryOldMaxId);
    	 
    	 echo 'krok 2 - koniec';*/
    	
    	
    	//krok 3 - usuniecie starych importowanych kategorii, do ktorych nic juz nie jest przypisane
    	
    	/*echo 'krok 3 - start';
    	
    	Yii::app()->db->createCommand()
    		->delete('tbl_category', 'imported = 1');
    	
	    
	    echo 'krok 3 - koniec';*/
    	
    	
    	//krok 4 - zmiana kategorii dla nowych obiektow z nowych ale usuwanych kategorii na Temp other
    	//!!!!!!!!!!!!!!!! TYLKO LOKALNIE
    	
    	/*echo 'krok 4 - start';
    	
    	$categoryNewMaxId = 5274;
    	
    	Yii::app()->db->createCommand()
    		->update('tbl_item', array(
    			'category_id'=>$categoryOtherId
    	), 'category_id<='.$categoryNewMaxId);
    	
    	Yii::app()->db->createCommand()
    		->delete('tbl_category', 'id<='.$categoryNewMaxId);
    	
    	echo 'krok 4 - koniec';*/
    	
    	//krok 5 - wprowadzenie mechanizmu sortowania kategorii root
    	//!!!!!!!!!!!!!!!! TYLKO LOKALNIE
    	 
    	/*echo 'krok 5 - start';
    	  
    	
 		$roots= Yii::app()->db->createCommand(array(
        	'select' => 'root',
            'distinct' => 'true',
            'from' => 'tbl_category',
        ))->queryColumn();
    	 
 		
 		$order_index = 1; 
 		
 		foreach($roots as $root) {
 			
 			echo '<br />'.$root;
 		
	 		Yii::app()->db->createCommand()
	 		->update('tbl_category', array(
	 				'order_index'=>$order_index++
	 		), 'root='.$root);
 		}
 		//print_r($roots);
 		
    	echo 'krok 5 - koniec';*/
    	
    }
    
    
    /**
     * Usuwanie plików użytkownika system - id = 2
     */
    public function actionDeleteItems() {
    	/*
    	ini_set('max_execution_time', 3000);
    	
    	$userId = 2;    	
    	
    	$criteria = new CDbCriteria(array(    			
    			'condition' => 'user_id='.$userId,
    			'limit' => 300
    	));
    	 
    	$items = Item::model()->findAll($criteria);
    	echo '<br />'.count($items);
    	foreach($items as $item) {
    		echo '<br />';
	    	echo 'obiekt '.$item->id.' '.$item->name;
    	    		
    		Yii::app()->file->filesPath;    // force set cwd
    			    	
	    	$item->delete();
	    	echo '<br />usunieto';
	    	    	
	    	//echo '<br />'.getcwd();    	
	    	
    	}
    	*/
    } 
    
    // wypełnienie tabeli państw
    public function actionCountry() {
    	
    	//return;
    	 
    	/*ini_set('max_execution_time', 3000);
    	
    	$reader = Yii::app()->db->createCommand()
    		->from('iso_panstwa')    		
    		->queryAll();
    	
    	foreach($reader as $countryPL) {
    		echo '<br />'; echo '<br />';
    		echo $countryPL['nazwa'];
    		
    		$countryEN = Yii::app()->db->createCommand()
    			->from('tbl_country')
    			->where('code=:code', array(':code'=>$countryPL['kod']))
    			->queryRow();
    		
    		echo '<br />';
    		echo $countryEN['name'];
    		
    		$maxId = Yii::app()->db->createCommand()
	    		->select('MAX(id)')
	    		->from('tbl_source_message')
	    		->queryScalar();
    		
    		echo '<br />';
    		echo $maxId.'<br />';
    		
    		Yii::app()->db->createCommand()
    			->insert('tbl_source_message', array(
    				'id'=>++$maxId,
    				'category'=>'country.name',
    				'object_id'=>$countryEN['id'],
    				'message'=>$countryEN['name']
    				//'message'=>'{name}'
    		));
    			
    		Yii::app()->db->createCommand()
    			->insert('tbl_message', array(
    				'id'=>$maxId,
    				'language'=>'pl',
    				'translation'=>$countryPL['nazwa']
    		));
    	}*/
    	
    	// usuniecie tlumaczen
    	/*
    	$reader = Yii::app()->db->createCommand()
    			->from('tbl_source_message')
    			->where('category=\'country.name\'')
    			->queryAll();
    	
    	foreach($reader as $source) {
    		echo '<br />'; echo '<br />';
    		echo $source['message'];
    		
    		Yii::app()->db->createCommand()
    			->delete('tbl_message', 'id=:id', array(':id'=>$source['id']));    		
    	}
    	
    	Yii::app()->db->createCommand()
    		->delete('tbl_source_message',
    			'category="country.name"');
    	*/		
    }
    
    public function actionContact() {
//        return;
        echo 'actionContact';
        $model = new Contact();
        $model->setScenario('compose');
        
        $model->forename = 'Blazej';
            $model->surname = 'Roza';
            $model->subject = 'test recaptcha';
            $model->message = 'dziala re';
//            $model->phone = '645345234';
            $model->email = 'seointercode@gmail.com';
        if($model->save()) {
            echo '<br>zapisano';
        }
        else
            echo '<br>nie zapisano';
        echo '<br>actionContact end';
    }
}