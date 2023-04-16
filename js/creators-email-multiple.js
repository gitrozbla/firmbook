$('.send-mail-multiple-button').one('click', function(e) {
	e.preventDefault();
	var $this = $(this);

	// select-all handler must be 'live'! So we cannot cache some selectors.

	$this.html($this.attr('data-select-mode'));
	$('#creators-user-list-wrapper').removeClass('email-select-hidden');

	$(document).on('click', '.email-select-all', function(e) {
		//e.preventDefault();
		e.stopImmediatePropagation();

		$('#creators-user-list .email-select').prop('checked', $(this).prop('checked'));
	});

	$('.send-mail-multiple-button').on('click', function(e) {
		e.preventDefault();

		var $this = $(this);

		var inputs = $('#creators-user-list .email-select:checked');
		if (inputs.length == 0) {
			alert($this.attr('data-no-email-selected'));
			return;
		}
		var emailList = [];
		inputs.each(function() {
			emailList.push(this.name);
		});
		emailList = emailList.join(', ');

		window.location.href = $this.attr('data-send-link') + '?to=' + emailList;
	});
});
