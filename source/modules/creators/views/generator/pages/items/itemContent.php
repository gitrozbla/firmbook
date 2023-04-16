<?php
    $model = $modelName::model()->findByPk($params[0]);
    $item = $model->item;
    // validate
    if ($model->company_id != $this->website->company_id) {
        throw new CHttpException(403);
    }
?>

<h1>
    <i class="fa <?php echo $type == 'product' ? 'fa-shopping-cart' : 'fa-truck'; ?>"></i>
    <?php echo $item->name; ?>
    <?php if (!empty($model->signature)) {
        echo '<br /><small>'.$model->signature.'</small>';
    } ?>
</h1>

<div class="row">
	<?php if ($item->thumbnail != null) {
			echo '<div class="span4">';
	    		echo Html::image($this->mapFile($item->thumbnail->generateUrl('large'), 'item'), $item->name,
	            array('class' => 'thumbnail'));
			echo '</div>';
	} ?>
	<div class="span8">
			<?php
			    //dane do TbDetailView
			    $tdvAttributes = array();
			    if($model->price)
	            $tdvAttributes[] = array(
	                'name'=>'price',
	                'value'=> $this->widget('bootstrap.widgets.TbLabel',
	                    array(
	                        'type' => $model->promotion ? 'warning' : 'success',
	                        'label' => $model->price.(isset($model->currency->name) ? ' '.$model->currency->name : '')
	                    ), true),
	                    'type' => 'raw'
	                );
	            if($model->promotion && $model->promotion_price) {
	                $tdvAttributes[] = array(
	                    'name'=>'promotion_price',
	                    'value'=>$this->widget('bootstrap.widgets.TbLabel', array(
	                        'label' => $model->promotion_price.(isset($model->currency->name) ? ' '.$model->currency->name : '')
	                    ), true),
	                    'type' => 'raw'
	                );
	                if($model->promotion_expire)
	                $tdvAttributes[] = array('name'=>'promotion_expire', 'value'=>$model->promotion_expire);
	            }

	            if ($type == 'product') {
	                if($model->delivery_free) {
	                    $tdvAttributes[] = array('name'=>'delivery_free',
	                        'value'=>Yii::t('common', 'yes'));
	                } else if($model->delivery_price) {
	                    $tdvAttributes[] = array('name'=>'delivery_price',
	                        'value'=>$model->delivery_price.' '.
	                            ($model->currency ? $model->currency->name : ''));
	                }
	                if($model->delivery_min) {
	                    $tdvAttributes[] = array('name'=>'delivery_min',
	                        'value'=>$model->delivery_min);
	                }
	                if($model->delivery_time) {
	                    $tdvAttributes[] = array('name'=>'delivery_time',
	                        'value'=>$model->delivery_time.' '.Yii::t('common', 'days'));
	                }
	            }
	    ?>
			<?php if (!empty($tdvAttributes)) {
				 $this->widget(
					'bootstrap.widgets.TbTabs',
					array(
						'type' => 'tabs', // 'tabs' or 'pills'
						'tabs' => array(
							array(
								'label' => Yii::t('product', 'Shop'),
								'active' => true,
								'content' => $this->widget(
								    'bootstrap.widgets.TbDetailView',
								    array(
								        'data' => $model,
								        'attributes' => $tdvAttributes,
										'htmlOptions' => array('class' => 'creators-table'),
								    ),
										true // return
								)
							)
						)
					)
				);
			} ?>
			
			<?php
				//var_dump($page->buttons);
				$buttons = array_flip($page->buttons);
				$allButtons = array(
					'currency_converter' => array(
						'label' => Yii::t('CreatorsModule.page', 'Currency converter'),
						'url' => 'http://www.money.pl/pieniadze/kalkulator/',
					),
					'delivery_services_comparison' => array(
						'label' => Yii::t('CreatorsModule.page', 'Delivery services comparison'),
						'url' => 'http://www.znajdzkuriera.pl/',
					),
					'exchange_rates' => array(
						'label' => Yii::t('CreatorsModule.page', 'Exchange rates'),
						'url' => 'http://www.nbp.pl/home.aspx?f=%2Fkursy%2Fkursya.html',
					),
				);

				echo '<div class="buttons">';
				if (!empty($tdvAttributes)) {
					foreach($allButtons as $key=>$value) {
						if (isset($buttons[$key]) || $this->previewMode) {
							echo '<span class="'.$key.'"
									'.(isset($buttons[$key]) ? '' : 'style="display:none"').' >';
								$this->widget(
									'Button',
									array(
										'buttonType' => 'link',
										'label' => $value['label'],
										//'type' => 'success',
										'url'=> $value['url'],
										'icon' => 'fa fa-bar-chart-o',
										'htmlOptions' => array(
											'style'=>	'margin:5px',
											'target'=>'_blank'
											//'title' => Yii::t('company', 'Currency converter'),
										),
									)
								);
							echo '</span>';
						}
					}
				}
				echo '</div>';
			?>
			
		</div>
</div>

<div>
		<?php
			$tabs = array();

			// description
			if (!empty($item->description)) {
				$tabs []= array (
					'label' => Yii::t ('product', 'Description'),
					'content' => $item->description,
					'active' => true
				);
			}

			// gallery
			if (!empty($item->files)) {
				$images = array();
				foreach($item->files as $file) {
						$images []= '<div class="img-polaroid bottom-10">'
										.Html::link(
														Html::image(
																$this->mapFile($file->generateUrl('medium'), 'item'),
																$item->name),
														$this->mapFile($file->generateUrl('large'), 'item'),
														array(
																'data-lightbox' => 'roadtrip',
																'title' => $item->name))
								.'</div>';
				}

				$tabs []= array(
					'label' => Yii::t ('product', 'Gallery'),
					'content' => '<div class="gallery">'.implode('', $images).'</div>'
				);
		 	}

			$attachments = Attachment::model()->dataProvider($item->id);
			if ($attachments->getTotalItemCount() > 0) {
				$attachmentsGrid = $this->widget(
					 'GridView',
					 array(
							'id' => 'attachments-grid',
							'type' => 'striped bordered',
							'dataProvider' => $attachments,
							'enableSorting' => false,
							'enablePagination' => false,
							'columns' => array(
								 array(
									 'name' => Yii::t('attachment', 'Name'),
									 'value' => '$data->anchor',
									 'htmlOptions' => array(
										 'style' => $item->cache_type=='c' ? 'width: 35%' : 'width: 50%'
									 )
								 ),
									 /*array(
											 'name' => Yii::t('attachment', 'Name'),
										 'value' => '$data->anchor'
									 ),*/
								 array(
									 'name' => Yii::t('attachment', 'Size'),
									 'value' => '$data->formatedFileSize()'
								 ),
								 'date',
									array(
											 'template' => '{download}',
											 'buttons' => array(
													 'download' => array(
														 'label' => Yii::t('attachment', 'Download'),
															 'icon' => 'fa fa-download',
															 'url' => 'Yii::app()->controller->mapFile($data->generateUrl())',
															 'options' => array(
																	 'target' => '_blank',
															 )
													 ),
											 ),
							 			 	'class' => 'bootstrap.widgets.TbButtonColumn'
									 ),
							 ),
					 ),
					 true	// return
			 );

			 $tabs []= array(
				 'label' => Yii::t ('attachment', 'Files'),
				 'content' => '<div class="attachments">'.$attachmentsGrid.'</div>'
			 );
			}


			if (!empty($tabs)) {
				$this->widget(
					'bootstrap.widgets.TbTabs',
					array(
						'type' => 'tabs', // 'tabs' or 'pills'
						'htmlOptions' => array('style' => 'margin-top: 20px'),
						'tabs' => $tabs
					)
				);
			}
		?>

		<?php $this->renderPartial('layouts/partials/comments', compact('item', 'page')); ?>
		
		<hr />
</div>

<br />
<a href="##" onClick="history.go(-1); return false;">
    <?php echo Yii::t('CreatorsModule.navigation', 'Go back'); ?>
</a>
