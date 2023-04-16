var createShareButtons = function() {
	var $buttons = $('share-button');

	$buttons.html('');	// empty buttons
	$buttons.each(function() {
		var $this = $(this);
		var text = $this.attr('data-button-text');

		var networksArray = $this.attr('data-networks').split(',');
		var networksList = {};
		var allNetworks = ['facebook', 'googlePlus', 'twitter', 'pinterest', 'linkedin', 'email', 'reddit', 'whatsapp'];
		for(var i=0; i<allNetworks.length; i++) {
			networksList[allNetworks[i]] = {enabled: networksArray.indexOf(allNetworks[i]) < 0 ? false : true};
		}

		new ShareButton('#'+this.id, {
			ui: {
				buttonText: text != '' ? text : $this.attr('data-default-text'),
				flyout: 'middle left',
				networkOrder: ['facebook', 'googlePlus', 'twitter', 'pinterest', 'linkedin', 'email', 'reddit', 'whatsapp']
			},
			networks: networksList
		})
	});
}
createShareButtons();
