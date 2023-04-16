<h1><?php echo Yii::t('CreatorsModule.companies', 'Welcome'); ?></h1>
<p>
    <?php echo Yii::t('CreatorsModule.companies', 'Here\'s Your companies list. Click configure option to start creating a website for company.'); ?>
</p>
<hr />
<br />
<h2><?php echo Yii::t('companies', 'Companies'); ?></h2>

<div class="row">
    <div class="span9">
        <?php $this->renderPartial('_list', compact('search')); ?>
    </div>

    <div class="span3">
        <?php $this->renderPartial('_listRight', compact('surveyForm')); ?>
    </div>
</div>