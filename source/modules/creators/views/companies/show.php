<div class="row">
    <div class="span9">
        <h1><?php echo $company->item->name; ?></h1>
        <?php if ($company->item->active == false) :?>
            <p class="quiet">(<?php echo Yii::t('CreatorsModule.companies', 'This company is disabled on Firmbook'); ?>)</p>
        <?php endif; ?>

        <?php /*<div class="alert alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo Yii::t('CreatorsModule.companies', 'To edit company details'); ?>
            <?php echo Html::link(
                  Yii::t('CreatorsModule.companies', 'please login to Firmbook account'),
                  $this->createFirmbookUrl('companies/update', array('id'=>$company->item->id)),
                  array('target' => '_blank')
            ); ?>.
        </div>*/ ?>
		<?php $this->widget(
				'bootstrap.widgets.TbButton',
				array(
						'label' => Yii::t('CreatorsModule.companies', 'Edit company data'),
						'url' => $this->createGlobalRouteUrl('companies/update', array('id'=>$company->item->id)),
// 						'url' => $this->createGlobalRouteUrl('companies/show', array(
// 								'name' => $company->item->alias
// 						)),
						'type' => 'info'
				)
		); ?>

        <?php $this->renderPartial('_fileList', array('company' => $company->item_id)); ?>
            
        <div>
            <span class="label label-info">!</span> 
            <?php echo Html::link(
                Yii::t('CreatorsModule.companies', 'How to host a website?'), 
                $this->createUrl('pages/show', array(
                    'name' => Yii::t('CreatorsModule.article', 'help')
                )).'#'.Yii::t('CreatorsModule.anchor', 'how-to-host-a-website'), 
                array('target' => '_blank')
            ); ?>
            <?php echo Yii::t('CreatorsModule.companies', ''); ?>
        </div>

        <div class="text-right">
            <?php $this->widget(
                'bootstrap.widgets.TbButtonGroup',
                array(
                    'buttons' => array(
                        array(
                            'label' => Yii::t('CreatorsModule.generator', 'Editor and live preview'), 
                            'url' => $this->createUrl('generator/editor', array(
                                'id' => $company->item_id
                            )),
                            'type' => 'info',
                            'htmlOptions' => array('target' => '_blank')
                        ),
                        array(
                            'label' => Yii::t('CreatorsModule.generator', 'Generate website'), 
                            'url' => $this->createUrl('generator/build', array(
                                'id' => $company->item_id
                            )),
                            'type' => 'primary'
                        ),
                    ),
                )
            ); ?>
        </div>

        <p>
            <?php echo $this->renderBackButton(); ?>
        </p>
    </div>
    
    <div class="span3">
        <?php $this->renderPartial('_companyRight', compact('company')); ?>
    </div>
</div>