<div class="page-<?php echo $page->id; ?>">
    <?php if ($this->noCustomContent == false) : ?>
        <h2 class="page-title"><?php echo Html::encode($page->title); ?></h2>

        <p class="page-content">
            <?php echo Html::encode($page->content); ?>
        </p>
    <?php endif; ?>
    <div class="page-type-content">
        <?php if ($page->type != 'custom') {
            if ($this->previewMode) {
                $url = $this->createGlobalRouteUrl('companies/show', array(
						'name' => $this->website->company->item->alias
				));
                echo '<p><i class="faded">[';
                    echo Yii::t('CreatorsModule.editor',
                            'The further text is generated automatically. '
                            . 'You can edit company data ');
                    echo Html::link(Yii::t('CreatorsModule.editor', 'HERE'), $url, array('target'=>'_blank'));
                echo '.]</i></p>';
            }
            
            include('pages/'.$page->type.'.php');
        } ?>
    </div>
</div>