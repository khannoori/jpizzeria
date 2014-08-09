/*

*/

function handleRating(value) {
	// Get the form and the id of the item
	var frm = this.options.form;
    var id = frm.getFirst('input[type=hidden]').get('value');
    
	new Request.JSON({
		url : 'index.php',
		onSuccess: function(response) {
			alert(response.result);
		},
		onError: function(err) {
			alert(err);
		}
	
	}).get({
		'option': 'com_pizza',
		'task': 'item.rate',
		'rating': value,
		'itemId': id
	});
	
	// disabled form
	frm.getChildren('input[type=radio]').set('disabled', 'disabled');
}