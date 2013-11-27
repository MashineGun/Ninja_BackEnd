toastr.options = {
    "debug": false,
    "positionClass": "toast-top-right",
    "onclick": null,
    "fadeIn": 300,
    "fadeOut": 500,
    "timeOut": 2000,
    "extendedTimeOut": 1000
}

function initNinja() {
    //Init Ninja Buttons
    $('.ninja-button').off('click');
    $(document).on('click', '.ninja-button', function(event) {
        event.preventDefault();
        var device = $(this).attr('data-device');
        var shortName = $(this).attr('data-short-name');
	var action = $(this).attr('data-action');
        var goal = '';
        var start = 0;
        var end = 0;
        if (action == 'GET') {
            var targetId = $(this).attr('data-target-id');
        }
        else if (action == 'PUT') {

        }
        if (shortName.indexOf('#') > -1) {
            var inputType = $(this).attr('data-input-type');
            if (inputType == 'select') {
                var shortName = $(inputType + shortName + ' option:selected').val();
            } else if (inputType == 'text') {
                var shortName = $(shortName).val();
            } else if (inputType == 'dropdown') {
                if ((typeof $('ul' + shortName + ' .label').attr('data-goal') !== 'undefined') && ($('ul' + shortName + ' .label:eq(0)').attr('data-goal').toString() != '')) {
                  var goal = $('ul' + shortName + ' .label').attr('data-goal').toString();
                }
		var shortName = $('ul' + shortName + ' .label').attr('value');
            }
        }
        var data = {
            'short_name': shortName,
            'device': device,
            'action': action,
            'goal': goal,
            'start': start,
            'end': end
        };
        var timeout_milli = 30000;
        toastr.info("Sending...", device);
        $.ajax({
            url: '/action.php',
            type: 'POST',
            data: data,
            timeout: timeout_milli,
            success: function(data) {
                toastr.success("Command sucessfully executed !", device)
                //called when successful
                if ($('#' + targetId).length > 0) {
                    $('#' + targetId).empty().html(data);
                }
            },
            error: function(e) {
                //called when there is an error
                toastr.error("Server didn't execute the command...", device)
            }
        });
    });

    //Init submit buttons
    $('.submitForm-button').off('click');
    $(document).on('click', '.submitForm-button', function(event) {
        event.preventDefault();
        var formId = $(this).attr('data-form-id');
        $('form#' + formId).submit();
    });


    //Init show hide
    $('.showHide').off('click');
    $(document).on('click', '.showHide', function(event) {
        event.preventDefault();
        var objectId = $(this).attr('data-show-hide');
        if ($('#' + objectId).css('display') == 'none') {
            $('#' + objectId).show();
        } else {
            $('#' + objectId).hide();
        }
    });
}

function getStatus() {
    var data = {
        'short_name': '',
        'device': 'All',
        'action': 'GET'
    };
    var timeout_milli = 10000;
    $.ajax({
        url: '/action.php',
        type: 'POST',
        data: data,
        timeout: timeout_milli,
        success: function(data) {
            var devices = $.parseJSON(data);
            //console.log(devices);
            $.each($(devices), function(key, device_data) {
                var device_string = device_data['device'].replace(/ /g, '-');
                if ($('#' + device_string + '-status').length > 0) {
			if ((typeof $('#' + device_string + '-status').attr('data-to-display') !== 'undefined') && ($('#' + device_string + '-status').attr('data-to-display') != '')) {
				var toDisplay = $('#' + device_string + '-status').attr('data-to-display');
                	}
			else {
				var toDisplay = 'short_name';
			}
			var status = capitaliseFirstLetter(device_data[toDisplay]);
			if (device_data['goal'] != '') {
				status += ' > ' + device_data['goal'];
    			}
			$('#' + device_string + '-status').html(status);
			$('#' + device_string + '-status').attr('value', status.toLowerCase());
			if (parseFloat($('#' + device_string + '-status').attr('data-time-out')) > 0) {
				if (parseFloat($('#' + device_string + '-status').attr('data-time-out')) < parseFloat(device_data['updated_since'])) {
					$('#' + device_string + '-status').css('color', 'red');
				}
				else {
					$('#' + device_string + '-status').css('color', 'green');
				}
			}
		}
	    });
		setTimeout(function() {
    			getStatus();
		}, 20000);
        },
        error: function(x, t, m) {
		if (t === 'timeout') {
         	       setTimeout(function() {
                	        getStatus();
                	}, 20000);
		}
		else {
            		//console.log(t);
		}
        }
    });

}

function capitaliseFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
