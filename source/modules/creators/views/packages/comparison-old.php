<h1><?php echo Yii::t('CreatorsModule.packages', 'Packages'); ?></h1>

<p>
    <?php echo Yii::t('CreatorsModule.packages', 'Below you can see packages available for order. '
    .'You can upgrade your package any time to gain more abilities!'); ?>
</p>

<table class="packages-table">
    <thead>
        <tr>
            <td></td>
            <?php foreach($packages as $package) : ?>
			<td style="<?php echo $package['badge_css']?>">
				<h2><?php echo $package['name']?></h2>
				<?php if($package['id'] != Yii::app()->params['packages']['defaultPackageCreators']) : ?>
				<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Buy now'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('packages/change/package/'.$package['id']),			                	
			                )
			            ); ?>
                <?php /*<button class="btn btn-primary" type="button" href=">Order</button>*/?>
                <?php endif; ?>				
			</td>
			<?php endforeach;?>
            <?php /*<td><h2>Free</h2></td>
            <td style="color:white; background:silver">
                <h2>Silver</h2>
                <button class="btn btn-primary" type="button">Order</button>
            </td>
            <td style="color:white; background:gold">
                <h2>Gold</h2>
                <button class="btn btn-primary" type="button">Order</button>
            </td>
            <td style="color:white; background:plum">
                <h2>Platinum</h2>
                <button class="btn btn-primary" type="button">Order</button>
            </td> */?>
        </tr>
    </thead>
    <tbody>
    	<?php foreach($services as $service) : ?>
    	<tr>
    		<td><?php echo Yii::t('package.service.title', $service['name'], array(), 'dbMessages');?></td>
    		<?php if($service['value_type'] == 1) : ?>
				<?php foreach($packages as $package) : ?>
					<td></td>
				<?php endforeach;?>
			<?php else : ?>
				<?php foreach($packages as $package) : ?>
					<?php if(isset($package['services'][$service['id']])) : ?>						
						<td><i class="fa fa-check"></i></td>						
					<?php else : ?>
						<td><i class="fa fa-times"></i></td>
					<?php endif; ?>
				<?php endforeach;?>
			<?php endif;?>			
    	</tr>
    	<?php endforeach;?>
    	<tr>
    		<td></td>
            <?php foreach($packages as $package) : ?>
			<td>				
				<?php if($package['id'] != Yii::app()->params['packages']['defaultPackageCreators']) : ?>
				<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Buy now'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('packages/change/package/'.$package['id']),			                	
			                )
			            ); ?>                
                <?php endif; ?>				
			</td>
			<?php endforeach;?>
    	</tr>
		<?php /*    
        <tr>
            <td>Generate website</td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td>Customer service</td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td>Responsive design (mobile version)</td>
            <td><i class="fa fa-times"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td>No Creators note in footer</td>
            <td><i class="fa fa-times"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td>Customer service priority</td>
            <td><i class="fa fa-times"></i></td>
            <td><i class="fa fa-times"></i></td>
            <td><i class="fa fa-check"></i></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td>Products, services per page</td>
            <td><i>up to 10 items</i></td>
            <td><i>up to 50 items</i></td>
            <td><i>up to 200 items</i></td>
            <td><i>unlimited</i></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><button class="btn btn-primary" type="button">Order</button></td>
            <td><button class="btn btn-primary" type="button">Order</button></td>
            <td><button class="btn btn-primary" type="button">Order</button></td>
        </tr>
        */ ?>
    </tbody>
    
</table>