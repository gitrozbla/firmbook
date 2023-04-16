<?php
/**
* Formularz zmiany paketu
*
* @category views
* @package packages
*/

if(!($selectedId = Yii::app()->request->getParam('package'))) $selectedId = 4;

$packages = Package::model()->with('periods')->findAll(array(
  'condition' => Yii::app()->params['websiteMode'] == 'creators' ? 'creators' : '!creators',
  'order' => 'order_index desc'
));


$packagesListOptions = array();
foreach($packages as $package) {
  if($package['id'] != Package::$_packageDefault) {
    $packageData = array();
    $periodsData = '';

    if ($canTest) {
      $periodsData .= '<option value="0"
        data-price="0.00 PLN (' . Yii::t('packages', 'darmowy') . ')">'
          . Yii::t('packages', 'Test ({period} days)', array('{period}'=> $package['test_period']))
        . '</option>';
    }
    foreach($package['periods'] as $period) {
      $periodsData .= '<option value="' . $period['period'] . '"
        data-price="' . $period['price'] . ' PLN">' . $period['period'] . '</option>';
    }

    $packageData['data-periods-html'] = $periodsData;
    //$packageData['id'] = $package['id'];
    if ($package['id'] == $selectedId) $packageData['selected'] = 'selected';
    $packagesListOptions[$package['id']]= $packageData;
  }
}
?>

<?php require '_packageDetails.php'; ?>

<div class="row">
  <div class="span6">

    <?php $form = $this->beginWidget('ActiveForm', array(
      'htmlOptions' => array('class' => 'well center'),
    )); ?>

      <?php
        echo '<h1>' . Yii::t('packages', 'Upgrade your account') . ' ' . Yii::app()->name . '</h1>';
        echo Yii::t('packages', 'Configure your order.');

        echo '<hr />';

        echo $form->dropDownListRow(
          $purchase,
          'package_id',
          Package::packagesToSelect(Yii::app()->params['websiteMode'] == 'creators'),
          array('options' => $packagesListOptions)
        );

        echo $form->dropDownListRow(
          $purchase,
          'period',
          array()
        );

        echo $form->textFieldRow($purchase, 'price', array(
          "disabled"=>"disabled"
        ));

        echo $form->checkBoxRow($purchase, 'force_activation');

        echo '<hr />';

        $this->widget('bootstrap.widgets.TbButton', array(
          'buttonType' => 'submit',
          'label' => Yii::t('packages', 'Order now'),
          'type' => 'success',
          'htmlOptions' => array(
            'class' => 'form-indent',
          )));

      ?>

      <div>
        <span class="required">*</span> - <?php echo Yii::t('packages', 'field required'); ?>
      </div>

    <?php $this->endWidget(); ?>

  </div>

  <div class="span6">
    <br />
    <h2><?php echo Yii::t('article.title', $articleRight1->title, array(), 'dbMessages'); ?></h2>
    <?php echo Yii::t('article.content', '{'.$articleRight1->alias.'}', array('{'.$articleRight1->alias.'}'=>$articleRight1->content), 'dbMessages'); ?>

    <h2><?php echo Yii::t('article.title', $articleRight2->title, array(), 'dbMessages'); ?></h2>
    <?php echo Yii::t('article.content', '{'.$articleRight2->alias.'}', array('{'.$articleRight2->alias.'}'=>$articleRight2->content), 'dbMessages'); ?>
  </div>

</div>
<?php
/*Yii::app()->clientScript->registerScriptFile('js/package-change.js');
Yii::app()->clientScript->registerScript('package-change-data', '
packageChange(' . json_encode($packagesData) . ');
');*/

  Yii::app()->clientScript->registerScript('package-change-data', "
    $('#PackagePurchase_package_id').on('change', function() {
      $('#PackagePurchase_period').html(
        $('option:selected', this).attr('data-periods-html')
      );
      setTimeout(function() {
        $('#PackagePurchase_period').trigger('change');
      }, 0);
    }).trigger('change');

    $('#PackagePurchase_period').on('change', function() {
      $('#PackagePurchase_price').val(
        $('option:selected', this).attr('data-price')
      );
    });
  ");
?>
