function show_js_sub(){
	document.write('<div id="" style="width:100%;height:100%; margin:auto;">');
	document.write('<iframe src="'+ gwkurl +'&feedback='+feedback+'" width="100%" height="760px" scrolling="yes" frameborder="0"></iframe>');
	document.write('</div>');
	console.log('<iframe src="'+ gwkurl +'?chan='+chan+'&search='+search+'&show='+show+'" width="100%" height="760px" scrolling="yes" frameborder="0"></iframe>');
}

function show_Gwkjs_sub(){
	//console.log( gwkurl+'?chan='+chan+'&search='+search+'&show='+show+'&searchRange'+searchRange+'&keyword='+keyword+'&feedback='+feedback);
	document.write('<div id="" class="haitao-box" style="width:100%;">');
	document.write('<iframe src="'+ gwkurl +'?chan='+chan+'&search='+search+'&show='+show+'&searchRange='+searchRange+'&keyword='+keyword+'&feedback='+feedback+'" width="100%" height="100%" scrolling="no" frameborder="0" ></iframe>');
	document.write('</div>');

}

function wechat_button_click(link, href,ev){
        if(link=='QRCODE'){
                if(!window.jQuery) return;
                var elm = ev.srcElement || ev.target;
                var qrDiv = jQuery(elm).parent().find('.open_social_qrcode');
                if(!qrDiv.find('canvas').length){
                        qrDiv.qrcode({width:180,height:180,correctLevel:0,background:'#fff',foreground:'#111',text:href});
                }
                qrDiv.toggle(250);
        }
}
