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
   // 右邊購物車信息
   CART.loadcart();


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


// 購物車操作
var CART = {
        add:function(goods_id,quanity,spec,size){
            $.ajax({
                url:"index.php?r=cart/add",
                type:"post",
                data:{goods_id:goods_id,quanity:(typeof(quantity) != 'undefined' ? quantity : 1),spec:(typeof(spec) != 'undefined' ? spec : 1),size:(typeof(size) != 'undefined' ? size : 1)},
                dataType: 'json',
                beforeSend: function() {
                    
                },
                complete: function() {
                    
                },          
                success: function(json) {
                    
                    console.log(json);
                }
            });
        },
        addform:function(form_id){
            $.ajax({
                url:"index.php?r=cart/add&type=form",
                type:"post",
                data:$("#"+form_id).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    
                },
                complete: function() {
                    
                },          
                success: function(json) {
                    
                    console.log(json);
                }
            });
        },
        edit:function(key){

        },
        delete:function(key){

        },
        quanity:function(goods_id,action){
            // 添加數量
            console.log(goods_id),
            $.ajax({
                type:"post",
                url:"index.php?r=cart/ajax",
                data:{goods_id:goods_id,action:action,type:"quanity"},
                success:function(data){

                    console.log(data);
                    data = JSON.parse(data);
                    if(data.status){
                        CART.loadcart();
                    }
                    
                },
                error:function(msg){
                    console.log(msg);
                }
            })
        },

        feature:function(fatt_id,tag){
            $("input[name='Cart\["+tag+"\]']").val(fatt_id);

            var size = $("input[name='Cart\[size\]']").val();
            var spec = $("input[name='Cart\[spec\]']").val();
            var price,skuno;
            if(size!=''&&spec!=''){
                for (var i = SKU.length - 1; i >= 0; i--) {
                    // console.log(spec+":"+size);
                        if(SKU[i]['feature_arr'] ==spec+":"+size){
                            price=SKU[i]['price'];
                            skuno=SKU[i]['skuno'];
                        }
                        // console.log(SKU[i]);
                };
                
                $("input[name='Cart\[price\]']").val(price);
                $(".detail-note").html(skuno);
                $(".detail-price").html("$"+price);
            }
        },
        loadcart:function(){
            $(".home-right").load("index.php?r=cart/info #cart-info");
        }
}
