(function( $ ) {

  function shiftEmptyInputs($inputs, $addButtonWrapper) {
    // gather values
    var values = [],
      value,
      i;
    for(i=0; i<$inputs.length; i++) {
      value = $inputs[i].value;
      if (value != '') values.push(value);
    }
    // shift
    var foundEmpty = false;
    for(i=0; i<$inputs.length; i++) {
      var $input = $($inputs[i]);
      if (i < values.length) {
        $input.val(values[i])
          .closest('.control-group')
          .show().removeClass('hidden');
      } else {
        $input.val('')
          .closest('.control-group')
          .hide().addClass('hidden');
        foundEmpty = true;
      }
    }
    // show/hide add button
    if (foundEmpty) $addButtonWrapper.show();
    else $addButtonWrapper.hide();
    // first input is always visible
    $inputs.first().closest('.control-group')
      .show().removeClass('hidden');
  }

  $.fn.inputGroup = function(options) {

    this.each(function() {
      var $this = $(this);
      var $inputs = $(this).find('input');

      $this.addClass('input-group');

      // add button
      $this.append('<div class="control-group add-button-wrapper" style="display:none">'
          + '<div class="controls">'
            + '<a href="#" class="add-button text-success">'
              + (options['add-button-label']
                ? options['add-button-label']
                : '<i class="fa fa-plus"></i>')
            + '</a>'
          + '</div>'
        + '</div>');
      var $addButtonWrapper = $this.find('.add-button-wrapper');
      var $addButton = $this.find('.add-button');

      // remove buttons
      $inputs.after('&nbsp;<a href="#" class="remove-button text-error">'
          + '<i class="fa fa-minus"></i>'
        + '</a>');
      $removeButtons = $this.find('.remove-button');

      shiftEmptyInputs($inputs, $addButtonWrapper);


      $addButton.on('click', function(e) {
        e.preventDefault();

        $this.find('.control-group.hidden').first()
          .slideDown().removeClass('hidden');

        if ($this.find('.control-group.hidden').length == 0) {
          $(this).closest('.control-group').hide();
        }
      });

      $removeButtons.on('click', function(e) {
        e.preventDefault();

        $(this).parent().find('input').val('');

        shiftEmptyInputs($inputs, $addButtonWrapper);
      });
    });

    return this;

  };

}( jQuery ));
