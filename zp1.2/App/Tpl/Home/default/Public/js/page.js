$(document).ready(function() {	
	theHomePageAni();
	showHideDetail();
	showStageDialog();
});

function showStageDialog(){

	$('.stageLink').click(function(){ 
	    var frameSrc=$(this).attr("src");
	    var title=$(this).attr("aria-labelledby");
	    var zpid=$(this).attr("zpid");	
	    var author=$(this).attr("author");
	    var uid=$(this).attr("uid");
		$('#myModal').on('show', function (){
            $('#myModal iframe').attr("src",frameSrc);    
            $('#myModalLabel').text(title);
            var authorLink=$('#myModal .author');
            authorLink.text("作者："+author);
            authorLink.attr("href","http://zp.zuixiami.com/author/"+uid);            
            $('#myModal .btn-toView').click(function(){            	
            	window.location.href='http://zp.zuixiami.com/works/'+zpid;
            });      
        });     
        $('#myModal').modal({show:true})});
}

function showHideDetail(){
	var obj=$(".wgt-detail");
	var l=obj.length;
	if(l<0){return;}
	$(".btn-show-hide").click(function(){
		   if(obj.height()<=50)
		   {
		   	   obj.css({overflow: "hidden", height: "auto" });
		   	   $(this).text("︽");
	   	   }
	   	   else
	   	   {
	   	   	   obj.css({overflow: "hidden", height: "50px" });
	   	       $(this).text("︾");
	   	   }
	});
}

/***** code by zmy *****/

function theHomePageAni() {
	if(getCookie("rainbow-ani")=="")
	{
		//$(".wgt-rainbow").parent().addClass("rainbow-ani");
		//setCookie("rainbow-ani","yet");
	}
}

//获得coolie 的值

function getCookie(name) {
	var cookieArray = document.cookie.split("; "); //得到分割的cookie名值对  
	var cookie = new Object();
	for (var i = 0; i < cookieArray.length; i++) {
		var arr = cookieArray[i].split("="); //将名和值分开   
		if (arr[0] == name) return unescape(arr[1]); //如果是指定的cookie，则返回它的值 
	}
	return "";
}

function delCookie(name) {
	document.cookie = name + "=;expires=" + (new Date(0)).toGMTString();
}

function addCookie(objName, objValue, objHours) { //添加cookie
	var str = objName + "=" + escape(objValue);
	if (objHours > 0) { //为时不设定过期时间，浏览器关闭时cookie自动消失
		var date = new Date();
		var ms = objHours * 3600 * 1000;
		date.setTime(date.getTime() + ms);
		str += "; expires=" + date.toGMTString();
	}
	document.cookie = str;
}

function setCookie(name, value) {
	var Days = 30; //此 cookie 将被保存 30 天
	var exp = new Date(); //new Date("December 31, 9998");
	exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
	document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();

}