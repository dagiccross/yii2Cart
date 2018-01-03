;
$(document).ready(function(e) {
	
    /*列表*/
    $('.list li').each(function() {
    	$(this).click(function(){
    		$(this).find(".chlid-list").slideToggle();
        if($(this).hasClass("active")){
        	$(this).removeClass("active");
        }else{
        	$(this).addClass("active");
        }
    	});
        
    });
    /*列表*/
   


});
// 设置cookie
function setCookie(name,value)
{
    var Days = 1;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();

    
//    var strsec = getsec(time);
//    var exp = new Date();
//    exp.setTime(exp.getTime() + strsec*1);
//    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
//读取cookies
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
 
    if(arr=document.cookie.match(reg))
 
        return (arr[2]);
    else
        return null;
}
