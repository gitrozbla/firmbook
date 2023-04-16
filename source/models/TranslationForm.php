<?php

class TranslationForm extends CFormModel
{    
	public $title;
	public $content;
	public $language;
    
	//ustawienia formularza tłumaczenia dla różnych modeli 
	public static $modelParams = array(
            'article' => array(
    			'model'=>'Article',
    			'title_column'=>'title',
            	'title'=>'article.title',
    			'content_column'=>'content', 
            	'content'=>'article.content', 
            	'url'=>'/admin/articles',
    			'formTitle'=>'artykułu: '
    		),
            'packageservice' => array(
    			'model'=>'PackageService',
    			'title_column'=>'name',
            	'title'=>'package.service.title', 
    			'content_column'=>'description',
            	'content'=>'package.service.content', 
            	'url'=>'/admin/packagesservices',
    			'formTitle'=>' usługi w pakietach: '
    		),
			'package' => array(
				'model'=>'Package',
				'title_column'=>'name',
				'title'=>'package.title',
				'content_column'=>'description',
				'content'=>'package.content',
				'url'=>'/admin/packages',
				'formTitle'=>' pakietu: '
			),
			'category' => array(
				'model'=>'Category',
				'title_column'=>'name',
				'title'=>'category.name',
				'content_column'=>'alias',
				'content'=>'category.alias',
				'url'=>'/admin/categories',
				'formTitle'=>' kategorii: '
			),
			'ad' => array(
				'model'=>'Ad',
				'title_column'=>'text',
				'title'=>'ad',
				'content_column'=>'alt',
				'content'=>'ad.alt',
				'url'=>'/admin/ads',
				'formTitle'=>' reklam: '
			),
			'adsbox' => array(
				'model'=>'AdsBox',
				'title_column'=>'name',
				'title'=>'adsbox.name',
				'content_column'=>'description',
				'content'=>'adsbox.description',
				'url'=>'/admin/adsboxes',
				'formTitle'=>' boxów: '
			),
        );
		
	
    public function rules()
    {
        return array(
        	array('title, content, language', 'safe'),
        );
    }	
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'title' => 'Tytuł',
            'content' => 'Treść',
        	'language' => 'Język',        	        	    
        );
    }
    
    
}
