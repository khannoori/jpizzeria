/*

*/

function saveItemPrice(obj, itemId, itemSize, oldPrice) {
	// Get the new price as float
	var newPrice = obj.value;
	
	// if the price has not been changed do nothing
	if (newPrice.replace(",",".") == oldPrice) {
		return;
	}
    
	// System message container
	var msg = document.id('system-message-container');
	
	new Request.JSON({
		url : 'index.php',
		onSuccess: function(response) {
			if (response != undefined && response.error != '') {
				msg.set('html', response.error);
				msg.set('class', "ajax-message-error");
			} else if (response != undefined && response.result != 'OK') {
				msg.set('html', 'Something strange happend - Please contact the developer (Ismail Faizi)'); 
				msg.set('class', "ajax-message");
			}
		},
		onError: function(err) {
			if (err != undefined && err != '') {
				msg.set('html', err);
				msg.set('class', "ajax-message-error");
			}
		}
	}).get({
		'option': 'com_pizza',
		'task': 'item.update',
		'price': newPrice,
		'size' : itemSize,
		'itemId': itemId
	});
}

function saveToppingPrice(obj, priceId, typeId, sizeId, oldPrice) {
	// Get the new price as float
	var newPrice = obj.value;
	
	// if the price has not been changed do nothing
	if (newPrice.replace(",",".") == oldPrice) {
		return;
	}
    
	// System message container
	var msg = document.id('system-message-container');
	
	new Request.JSON({
		url : 'index.php',
		onSuccess: function(response) {
			if (response != undefined && response.error != '') {
				msg.set('html', response.error);
				msg.set('class', "ajax-message-error");
			} else if (response != undefined && response.result != 'OK') {
				msg.set('html', 'Something strange happend - Please contact the developer (Ismail Faizi)'); 
				msg.set('class', "ajax-message");
			}
		},
		onError: function(err) {
			if (err != undefined && err != '') {
				msg.set('html', err);
				msg.set('class', "ajax-message-error");
			}
		}
	}).get({
		'option':	 'com_pizza',
		'task':		 'prices.save',
		'priceId':	 priceId,
		'price':	 newPrice,
		'typeId':	 typeId,
		'sizeId':	 sizeId
	});	
}

(function() {
	var Sizes = this.Sizes = {
		initialize: function()
		{
			// Initialization goes here
		},
		
		remove: function(sizeId)
		{
			// System message container
			var msg = document.id('system-message-container');
			
			new Request.JSON({
				url : 'index.php',
				onSuccess: function(response) {
					if (response != undefined && response.error != '') {
						msg.set('html', response.error);
						msg.set('class', "ajax-message-error");
					} else if (response != undefined && response.result == 'OK') {
						var row = document.id('row-'+sizeId);
						row.dispose();
					}
				},
				onError: function(err) {
					if (err != undefined && err != '') {
						msg.set('html', err);
						msg.set('class', "ajax-message-error");
					}
				}
			}).get({
				'option': 'com_pizza',
				'task': 'size.remove',
				'id': sizeId
			});
		},
		
		edit: function(sizeId)
		{
			// Get the edit button as an element
			var btn = document.id('edit-btn-'+sizeId);
			// Get the element containing the size name
			var val = document.id('size-'+sizeId);
			// Get system message container
			var msg = document.id('system-message-container');
			// Create the AJAX-form
			var input = new Element('input', {
				value: val.get('html').trim(),
				size: '30'
			});
			// Add the "submit" event
			input.addEvent('keypress', function(evt) {
				if (evt.key == 'enter') 
				{
					var newSize = input.get('value');
					
					new Request.JSON({
						url : 'index.php',
						onSuccess: function(response) {
							if (response != undefined && response.error != '') {
								msg.set('html', response.error);
								msg.set('class', "ajax-message-error");
							} else if (response != undefined && response.result == 'OK') {
								val.set('html', newSize);
							}
							btn.replaces(input);
						},
						onError: function(err) {
							if (err != undefined && err != '') {
								msg.set('html', err);
								msg.set('class', "ajax-message-error");
							}
							btn.replaces(input);
						}
					}).get({
						'option': 'com_pizza',
						'task': 'size.edit',
						'id': sizeId,
						'size': newSize
					});					
				}
			});
			
			// Replace the button with the AJAX-form
			input.replaces(btn);
		}
	}
})(document.id);

window.addEvent('domready', function(){
	Sizes.initialize();
});