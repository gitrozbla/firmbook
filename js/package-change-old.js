function packageChange(packages) {
  //console.log(packages);

  var $period = $('#PackagePurchase_period'),
      $price = $('#PackagePurchase_price'),
      $package = $('#PackagePurchase_package_id');

  function loadPeriods() {
    var package = packages[$package.val()];
    var periods = package['periods'];
    $period.html('');
    $.each(periods, function(id, period) {
      $period.append('<option value=\"' + id + '\">' + id + '</option>');
    });
  }

  loadPeriods();
  $price.val(packages[$package.val()]['periods'][$period.val()]['price']);

 $('#PackagePurchase_package_id').change(function () {
   loadPeriods();
   
   $price.val(packages[$package.val()]['periods'][$period.val()]['price']);
     var optionSelected = $(this).find("option:selected");
   var valueSelected  = optionSelected.val();
     var textSelected   = optionSelected.text();
     if(packages[valueSelected]['test_period']) {
      $('#test_button').text(packages[valueSelected]['test_label']);
      $('#test_layer').css({'display':'inline'});
     } else
      $('#test_layer').css({'display':'none'});
 });
 $period.change(function () {
  $price.val(packages[$package.val()]['periods'][$period.val()]['price']);
 });
}
