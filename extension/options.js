$(document).ready(function() {

	var array = [];
	var arrayOperation = {
		add: function (array, item) {
			array.push(item);
			return array;
		}
	}

	var getLocal = localStorage["PIXNET_HACK_ADS_TYPE"];
	var getLocalArray = [];

	if (getLocal) {
		getLocalArray = getLocal.split(",");
	}

	$('input[type=checkbox]').attr('checked', false);
	for (i = 0; i < getLocalArray.length; i++) {
  		$("#check_" + getLocalArray[i]).attr('checked', true);
	}

	$('input[type=checkbox]').change(function() {
		array = [];
		$('input[type=checkbox]').each(function () {
		    if (this.checked) {
		    	arrayOperation.add(array, $(this).val());
		    }
		});
		var result = array.join(",");

		localStorage["PIXNET_HACK_ADS_TYPE"] = result;

		// PIXNET_HACK_ADS_TYPE
		chrome.storage.sync.set({'PIXNET_HACK_ADS_TYPE': result}, function() {
	    	// Notify that we saved.
	    	console.log('PIXNET_HACK_ADS_TYPE changed = ' + result);
	    });
	});

});