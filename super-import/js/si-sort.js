function getCbkList(siteName) {
    var cbk = document.getElementsByName('ckb-oz');
    var impList = [];
    for(var i = 0; i < cbk.length; i++) {
        if(cbk[i].checked && cbk[i].getAttribute('site-data') == siteName) {
            impList.push(cbk[i].getAttribute('id-data'));
        }
    }
    return impList;
}

function sendRq(sym, siteName) {
    var resList = getCbkList(siteName);

    var btns = document.getElementsByName('btn-import');
    for(var i = 0; i < btns.length; i++) {
        btns[i].disabled = true;
    }

    jQuery.ajax({
        url : SI_OBJ.ajax_url,
        dataType : 'json', 
        type : 'POST',
        data : {
            action : 'imprq',
            flag : sym,
            list : resList,
            site : siteName
        },
        success : function(response) {
            console.log(response);
            for(var i = 0; i < btns.length; i++) {
                btns[i].disabled = false;
            }
        }
    });
}

function runScrapy() {
    jQuery.ajax({
        url : SI_OBJ.ajax_url,
        dataType : 'json', 
        type : 'POST',
        data : {
            action : 'scrapyrq',
        },
        success : function(response) {
            console.log(response);
        }
    });
    setTimeout(function () {
        alert('Request sent. Please refresh this page later.');
    }, 2000);
}
