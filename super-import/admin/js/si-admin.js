function siImportAjax(spanname, path, shopid, secnum) {
	var fileInfobox = document.getElementById(spanname);
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		url: ajaxurl,
		timeout: 0,
		data: {
			'action': 'trigger_import',
			'fileurl': path,
			'shopid': shopid,
			'secnum': secnum
		},
		success: function(data) {
			console.log(data);
			if(data.res) {
				if(data.secnum == -1) {
					fileInfobox.textContent = 'Completed';
					fileInfobox.style.border = "solid 2px lime";
				} else {
					fileInfobox.textContent = 'Importing: ' + (data.secnum/data.totalsec*100).toFixed(0) + '%';
					siImportAjax(spanname, path, shopid, data.secnum);
				}
			} else {
				fileInfobox.textContent = 'Failed';
				fileInfobox.style.border = "solid 2px red";
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			fileInfobox.textContent = 'Failed';
			fileInfobox.style.border = "solid 2px red";
			console.log(xhr.status);
			console.log(xhr.responseText);
			console.log(thrownError);
		}
	});

}

function siExcuteButtonClick() {
    var fileUrlForParseArray = new Array();

    var checkboxes = document.getElementsByClassName('file-chooser');
    for(var i = 0; i < checkboxes.length; i++) {
        if(checkboxes[i].checked) {
            var path = checkboxes[i].getAttribute('value');
            var spanname = path.replace(/^.*[\\\/]/, '').replace(/\./g, '-');
            var shopid = spanname.replace(/\-.*/, '');

            (function (){
	            var fileInfobox = document.getElementById(spanname);
				fileInfobox.textContent = 'Importing...';
	    		fileInfobox.style.border = "solid 2px blue";

                siImportAjax(spanname, path, shopid, 0);
            })();
        }
    }

}

function siSelectAll(){
    var checkboxes = document.getElementsByClassName('file-chooser');
    for(var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = true;
    }
}

function siCancelAll(){
    var checkboxes = document.getElementsByClassName('file-chooser');
    for(var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
    }
}

function siCleanDB() {
    if(confirm('Warning! This will delete all the data in pl_producthistory.'+"\n"+'Sure to continue?')){
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'trigger_deldb',
            },
			timeout: 0,
            success: function(data) {
                window.alert('Related database has been cleaned.');
                location.reload();
                console.log(data);
            },
            beforeSend: function() {
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });

    } else {

    };
}

function siCheckCronInput(boxName) {
    var val = document.getElementById(boxName).value;

    if(!jQuery.isNumeric(val) && val != '*') {
        document.getElementById(boxName).value = '';
        alert('Can only accept number or symbol * in input box.');
        return -1;
    }

    return val;
}

function siExcuteCron() {
     if(confirm('Are you sure to excute the cron job? This may slow down the server. And need to wait for a long time.')) {
        var infoBox = document.getElementById('cron-info');
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'trigger_execcron',
            },
			timeout: 0,
            success: function(data) {
                if(data.res == true) {
                    infoBox.textContent = 'Excuting finished.';
                } else {
                    alert('The cron script is running on the server. You don\'t need to run again.');
                }
            },
            beforeSend: function() {
                    infoBox.textContent = 'Excuting the cron script. Please wait until finished...';
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert('Cron excute failed. The cron script may be running. Please retry again later.');
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }
}

function siDelCron() {
    if(confirm('Are you sure to cancel the cron job?')) {
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
                'action': 'trigger_delcron',
            },
			timeout: 0,
            success: function(data) {
                if(data.res == true) {
                    location.reload();
                } else {
                    alert('Cron set failed. Please retry again.');
                }
            },
            beforeSend: function() {
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert('Cron set failed. Please retry again.');
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }
}

function siDefaultCron() {
    if(confirm('Are you sure to set the cron job to default value?')) {
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
			timeout: 0,
            data: {
                'action': 'trigger_addcron',
                'cron-min': '0',
                'cron-hr': '3',
                'cron-day': '*',
                'cron-mon': '*',
                'cron-week': '*',
            },
            success: function(data) {
                if(data.res == true) {
                    location.reload();
                } else {
                    alert('Cron set failed. Please retry again.');
                }
            },
            beforeSend: function() {
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert('Cron set failed. Please retry again.');
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }
}

function siAddCron() {
    var cronMin = siCheckCronInput('cron-min');
    if(cronMin < 0) {
        return;
    }

    var cronHr = siCheckCronInput('cron-hour');
    if(cronHr < 0) {
        return;
    }

    var cronDay = siCheckCronInput('cron-day');
    if(cronDay < 0) {
        return;
    }

    var cronMon = siCheckCronInput('cron-mon');
    if(cronMon < 0) {
        return;
    }

    var cronWeek = siCheckCronInput('cron-week');
    if(cronWeek < 0) {
        return;
    }

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
		timeout: 0,
        data: {
            'action': 'trigger_addcron',
            'cron-min': cronMin,
            'cron-hr': cronHr,
            'cron-day': cronDay,
            'cron-mon': cronMon,
            'cron-week': cronWeek,
        },
        success: function(data) {
            if(data.res == true) {
                location.reload();
            } else {
                alert('Cron set failed. Please retry again.');
            }
        },
        beforeSend: function() {
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert('Cron set failed. Please retry again.');
            console.log(xhr.status);
            console.log(xhr.responseText);
            console.log(thrownError);
        }
    });
}
