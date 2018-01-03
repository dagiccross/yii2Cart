;
window.cart_submit_flat = 0;

$(document).ready(function(e) {

  // var myDate = new Date();
  // myDate.getYear();        //获取当前年份(2位)
  // myDate.getFullYear();    //获取完整的年份(4位,1970-????)
  // myDate.getMonth();       //获取当前月份(0-11,0代表1月)
  // myDate.getDate();        //获取当前日(1-31)
  // myDate.getDay();         //获取当前星期X(0-6,0代表星期天)
  // myDate.getTime();        //获取当前时间(从1970.1.1开始的毫秒数)
  // myDate.getHours();       //获取当前小时数(0-23)
  // console.log(myDate.getTime()+":"+myDate.getHours());

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


  // 加入購物車事件註冊
  $(".order-options-modal").delegate(".requiredAccessories .accessoryGroup","click",function(){
    $(this).parent().find("input[name='options\[\]']").prop("checked",false);
    $(this).find("input[name='options\[\]']").prop("checked",true);
    $(this).parent().hide();
    var g_options_id = $(this).find("input[name='options\[\]']").val();

    var next_i = $(this).parent().attr('data-i');
    var zs = $(this).parent().attr('data-zs');

    CART.addRadio(g_options_id,".addedItems");
    CART.nextOptions(next_i,zs);
  });

  // 当requrie没有选择
  $(".order-options-modal").delegate("#customisableProductSubmit","click",function(){
    $("#productDialogDetails").attr("data-message","");
    $(".data-message").remove();

    var i = $(this).attr("data-i");
    var zs = $(this).attr("data-zs");
    i = Number(i);
    zs = Number(zs);

    var data_required = $("#goods_options_"+i).attr("data-required");
    var data_type = $("#goods_options_"+i).attr("data-type");

    // 当在i进度的时候点击提交时
    var checkbox_zs = 0;
    var append_data = "";

    if(data_type=='checkbox'){
      var input_values = $("#goods_options_"+i+" .g-options-info");
      var input_i = 0;
      append_data = "Extra: ";
      input_values.each(function(){
        var input_val = Number($(this).val());
        checkbox_zs += input_val;
        if(input_val>0){
          if(input_i>0){
            append_data +=",";
          }
          append_data += $(this).attr("data-name");
          input_i++;
        }

      });
      if(input_i==0){
        append_data ="No Extra";
      }
    }else if(data_type=='radio'){
      append_data = "No Extra";
    }


    if(data_required=="1"&&checkbox_zs==0){
      if(!$("#productDialogDetails").attr("data-message")){
        $("#productDialogDetails").append("<p class='data-message'>(you will be prompted to choose one of the above)</p>");
        $("#productDialogDetails").attr("data-message",'true');
      }
    }else{
      $("#goods_options_"+i).hide();
      var next_i = (i+1);
      $(this).attr("data-i",next_i);
      if(next_i<zs){
        $("#goods_options_"+next_i).removeClass("hide");
        $("#goods_options_"+next_i).show();
      }
      CART.addGoodsSelecter(".addedItems",append_data);
    }


    // 当i==zs是就提交表单
    if(i==zs){
      $("#customisableProductForm").submit();
    }
  });
  // 选择附加产品时
  $(".order-options-modal").delegate(".additional_select .accessoryGroup","click",function(){
    var name = $(this).find(".name").html();
    $("input[name='cart\[additional\]']").val(name);
    CART.addGoodsSelecter(".addedItems",name);
    $(".additional_select").hide();
  });
  // 当附加产品点击confirm的时候
  $(".order-options-modal").delegate("#additional_confirm","click",function(){

    var free_goods = $("input[name='cart\[additional\]']").val();
    if(free_goods!="undefined"&&free_goods!=""){
      window.cart_submit_flat = 1;
      $(".cart-checkout-from").submit();
    }else{
      $("#productDialogDetails .alert").html("Please Select Options");
    }

  });


  $("#collection_one_step").submit(function(){
    return false;
  });
  // 當自提第一步提交時
  $("#collection_one_step .next-step").click(function(){

    var phone = $("input[name='shipment\[shipment_phone\]']").val();

    if(phone.length<11||phone.length>13){
      modal_alert("phone must be 11 character digits");
      return false;
    }

    if(phone!=''){
      $.ajax({
        type:"post",
        data:$("#collection_one_step").serialize(),
        url:"index.php?r=cart/checkout-ajax",
        success:function(data){
          if(isjson(data)){
            data = JSON.parse(data);
            window.location.href = data.url;
          }

        }
      });
    }else{
      modal_alert('Please input contact number');
    }

  });

  // 自提第二步提交時
  $("#collection_two_step .next-step").click(function(){
    $.ajax({
      type:"post",
      data:$("#collection_two_step").serialize(),
      url:"index.php?r=cart/checkout-ajax",
      success:function(data){
        if(isjson(data)){
          data = JSON.parse(data);
          window.location.href = data.url;
        }

      }
    });

  });

  // Postcode2 Validation
  // and check...
  // Signup Postcode2 validation



  $("#deliver_one_step .next-step").click(function(){

    // check phone number
    var phone = $("input[name='shipment\[shipment_phone\]']").val();

    if(phone.length<11||phone.length>13){
      modal_alert("Phone number must be 11 character digits");
      return false;
    }



    // check street address
    var add1 = $("input[name='shipment\[shipment_addr1\]']").val();

    var regexp = /^[\w\s.-]+\d+,\s*[\w\s.-]+$/;
    if(add1.length <= 2){
      modal_alert('Address are not valid');
      return false;
    }

    // check postcode
    if ((map_cal) == 0){

      var shipment2 = $("input[name='shipment\[shipment_postcode2\]']").val();

      var regPostcode = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;

      if(regPostcode.test(shipment2) == false){
        modal_alert("Postcode not valid");
        return false;
      }


      $.ajax({

        url: '/vendor/checkp.php',
        data: "f=checkPostalCSV&p=" + $("#txtPostCode").val(),
        type: "GET",
        success: function (response) {
          doPassDeliverStepOne();
        }
      });
    }
    if ((map_cal) == 1){

      // check city, avoid having numbers for confusion
      var citynumber = $("input[name='shipment\[shipment_city\]']").val();
      if(citynumber.length == 0){
        modal_alert('City/town cannot be empty');
        return false;
      }
      if(citynumber.length < 2){
        modal_alert('City/town is not valid');
        return false;
      }

      var shipment2 = $("input[name='shipment\[shipment_postcode2\]']").val();
      var testPostcode = /[0-9a-zA-Z]{0,1}[a-zA-Z]{2}/i;

      // // check city
      // var citych = $("input[name='shipment\[shipment_city\]']").val();
      // if(citych.length < 3){
      //   modal_alert('City/town are not valid');
      //   return false;
      // }

      if(testPostcode.test(shipment2) == false){
        modal_alert("Postcode is not valid");
        return false;
      }
      if(shipment2.length >= 4){
        modal_alert("Postcode is too long");
        return false;
      } else {
        doPassDeliverStepOne();
      }
    }


  });

  function doPassDeliverStepOne(){

    $.ajax({
      type:"post",
      data:$("#deliver_one_step").serialize(),
      url:"index.php?r=cart/checkout-ajax",
      success:function(data){
        if(isjson(data)){
          data = JSON.parse(data);
          if(data.status){
            window.location.href = data.url;
          }else{
            modal_alert(data.message);
          }

        }

      },
      error:function(msg){
        INFO.show_msg(msg);
      }
    });
  };



  // 當送餐提交第二步時
  $("#deliver_two_step .next-step").click(function(){
    $.ajax({
      type:"post",
      data:$("#deliver_two_step").serialize(),
      url:"index.php?r=cart/checkout-ajax",
      success:function(data){
        if(isjson(data)){
          data = JSON.parse(data);
          window.location.href = data.url;
        }

      }
    });
  });

  // 確認訂單頁選擇支付方式
  $(".paypal-part .paypal-way").each(function(){
    $(this).click(function(){
      $(".paypal-part .paypal-way").find("span").removeClass("glyphicon-menu-up");
      $(".paypal-part .paypal-way").find("span").addClass("glyphicon-menu-down");
      $(this).find("span").removeClass("glyphicon-menu-down");
      $(this).find("span").addClass("glyphicon-menu-up");
      var payment = $(this).attr("data-key");
      if(typeof(payment)!="undefined"&&payment!=""){

        CART.loadConfirm(payment);
      }

      console.log($(this).parent().find(".paypal-detail"));
      $(".paypal-part .paypal-detail").hide();
      $(this).parent().find(".paypal-detail").removeClass("hide");
      $(this).parent().find(".paypal-detail").slideDown();


    });

  });

  // 選擇送餐方式
  $(".home-right").delegate("#cancelMenuSwitch","click",function(){
    CART.loadcart();
  });

  $(".home-right").delegate("#confirmMenuSwitch","click",function(){
    var radiovalue = $("input[name='cart\[send\]']:checked").val();
    // console.log(radiovalue);
    // INFO.show_msg(radiovalue);
    // return false;
    $.ajax({
      type:'post',
      data:{send:radiovalue,type:"send"},
      url:"index.php?r=cart/ajax",
      success:function(data){
        CART.loadcart();
      }
    });
  });


  // 购物车提交时
  // $(".wrap").delegate(".checkout-button","click",CART.formSubmit());


  // cart info 單獨頁面時
  // 選擇送餐方式
  $("#cart-info").delegate("#cancelMenuSwitch","click",function(){
    CART.loadcart("reload");
  });

  $("#cart-info").delegate("#confirmMenuSwitch","click",function(){
    var radiovalue = $("input[name='cart\[send\]']:checked").val();
    // console.log(radiovalue);
    // INFO.show_msg(radiovalue);
    // return false;
    $.ajax({
      type:'post',
      data:{send:radiovalue,type:"send"},
      url:"index.php?r=cart/ajax",
      success:function(data){
        CART.loadcart("reload");
      }
    });
  });



  // end cart info 單獨頁面時
  // 弹出页面连接
  $(".pageback").click(function(){
    $(".page-feedback").modal();
  });
  // feedback 頁面彈出
  $(".feedback_kai").click(function(){
    kai();
  });

  // 彈出alertgy-dietary
  $(".alertgy-dietary").on("click",function(){
    allergy();
  });

  // 彈出開店時間頁面
  $(".open-time").click(function(){
    $(".page-open-time").modal();
  });

  // 彈出送餐信息頁面
  $(".delivery-information").click(function(){
    $(".page-delivery-information").modal();
  });

});

function kai(){
  d_width = $(document).width();
  d_width = parseInt(d_width)-575;

  window.open('index.php?r=site/feedback','newwindow','height=760,width=575,top=0,left='+d_width/2+',toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');
}
// allergy點擊
function allergy(){
  $(".allergy-modal").modal();
}


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
// 判斷是否為JSON
function isjson(obj){
  var isjson =false;
  try{
    json = JSON.parse(obj);
    isjson = true;
  }catch (e){
    isjson = false;
  }

  // var isjson = typeof(obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;
  return isjson;
}

// 購物車操作
var CART = {
  //添加產品到購物車
  add:function(goods_id){
    // 添加數量
    // 查看是不是購物車有沒有產品
    var empty_cart = $(".order-list").html();
    empty_cart = (typeof(empty_cart)=='undefined');
    // console.log(arguments.length);
    var flat_opitons_id = typeof(arguments[1])!='undefined';
    var options_id = arguments[1];
    // console.log(empty_cart);
    // console.log(arguments[1]+"1");
    // 當沒有產品時
    // 時間開始
    var time_open = (PAYMENT_INFO.Nowtime<PAYMENT_INFO.Opentime);
    //時間關閉
    var time_close = (PAYMENT_INFO.Nowtime>PAYMENT_INFO.Closetime);
    if((time_open||time_close)&&empty_cart){
      var radiovalue = $("input[name='cart\[send\]']:checked").val();
      // console.log("radiovalue:"+radiovalue);
      if(time_close){
        $(".pro-order-modal .open-time").hide();
        $(".pro-order-modal .close-time").show();
      }else{
        $(".pro-order-modal .close-time").hide();
        $(".pro-order-modal .open-time").show();
      }
      $(".pro-order-modal .confirm").unbind("click");

      $(".pro-order-modal .confirm").click(function(){

        if(flat_opitons_id){

          CART.addOptionsGoods(goods_id,options_id);
        }else{
          CART.addDo(goods_id);
        }

      });



      if(typeof(radiovalue)=="undefined"){

        console.log("radiovalue:"+radiovalue);

        $(".pro-delivery-order-modal .confirm").click(function(){
          var orderDelivery = $("input[name='orderDelivery']:checked").val();

          console.log("orderDelivery:"+orderDelivery);

          if(typeof(orderDelivery)=="undefined"){
            return false;
          }else{
            $.ajax({
              type:'post',
              data:{send:orderDelivery,type:"send"},
              url:"index.php?r=cart/ajax",
              success:function(data){
                $(".pro-order-modal").modal();
              }
            });
          }

        });

        $(".pro-delivery-order-modal").modal();
      }else{
        $(".pro-order-modal").modal();
      }

    }else{
      // 當有產品時
      // CART.addDo(goods_id);
      if(flat_opitons_id){
        CART.addOptionsGoods(goods_id,arguments[1]);
      }else{
        CART.addDo(goods_id);
      }
    }

  },
  // 後臺處理添加到購物車的操作
  addDo:function(goods_id,quanity,spec,size){

    $.ajax({
      url:"index.php?r=cart/add",
      type:"post",
      data:{goods_id:goods_id,quanity:(typeof(quantity) != 'undefined' ? quantity : 1),spec:(typeof(spec) != 'undefined' ? spec : 1),size:(typeof(size) != 'undefined' ? size : 1)},
      // dataType: 'json',
      success: function(data) {
        // console.log(data);
        if(isjson(data)){
          data = JSON.parse(data);
          INFO.alert_info(data.message);
        }else{
          $(".order-options-modal .content").html(data);
          $(".order-options-modal").modal();
          $("#customisableProductDialog").html(data);
        }
      },
      error:function(data){
        INFO.show_msg(data);
      }
    });
  },
  // 提示產品表單到後臺添加購物車
  addform:function(seleter){
    $.ajax({
      url:"index.php?r=cart/add&type=form",
      type:"post",
      data:$(seleter).serialize(),
      success: function(data) {
        if(isjson(data)){
          $(".order-options-modal .close").click();
          INFO.alert_info(data.message);

        }
      },
      error:function(msg){
        INFO.show_msg(msg);
      }
    });
    return false;
  },
  // 添加直接是擴展選擇的產品里OPTIONS
  addOptionsGoods:function(goods_id,options_id){
    $.ajax({
      url:"index.php?r=cart/add&type=options",
      type:"post",
      data:{goods_id:goods_id,quanity:(typeof(quantity) != 'undefined' ? quantity : 1),spec:(typeof(spec) != 'undefined' ? spec : 1),size:(typeof(size) != 'undefined' ? size : 1),options_id:options_id},
      // dataType: 'json',
      success: function(data) {
        console.log(data);
        if(isjson(data)){
          data = JSON.parse(data);
          INFO.alert_info(data.message);
        }
      },
      error:function(data){
        INFO.show_msg(data);
      }
    });
    return false;
  },
  // 購物車內產品加減
  edit:function(cart_key,action){

    $.ajax({
      type:"post",
      url:"index.php?r=cart/ajax",
      data:{key:cart_key,action:action},
      success:function(data){

        // console.log(data);
        data = JSON.parse(data);
        INFO.alert_info(data.message);
        if(data.status){
          // alert(PAYMENT_INFO.Agent!="Other"&&PAYMENT_INFO.Agent!="other"&&PAYMENT_INFO.ActionId=="info");
          if(PAYMENT_INFO.Agent!="Other"&&PAYMENT_INFO.Agent!="other"&&PAYMENT_INFO.ActionId=="info"){
            CART.loadcart(PAYMENT_INFO.Agent);
          }else{
            CART.loadcart();
          }
        }
      },
      error:function(msg){
        INFO.show_msg(data);
      }
    })
  },
  // 當更改送餐方式時
  changeSend:function(){
    $("#menuSwitcher").hide();
    $("#menuSwitcherAlert").show();
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
    // console.log(typeof(arguments[0])!="undefined");
    console.log(arguments[0]);
    if(typeof(arguments[0])!="undefined"){
      if(arguments[0]!="other"&&arguments[0]!="Other"){
        $(".container .wrap").load("index.php?r=cart/info #cart-info");
        window.location.reload();
      }else{
        window.location.reload();
      }

    }else{
      $(".home-right").load("index.php?r=cart/info #cart-info");
    }

  },
  loadConfirm:function(payment){
    if(typeof(arguments[0])=="undefined"){
      window.location.reload();
    }else{
      $(".confirm-cart-info").load("index.php?r=cart/info&payment="+payment+" #cart-confirm-info");
    }
  },
  addRadio:function(g_options_id,seleter){

    var name = $("input[name='goods_options\["+g_options_id+"\]']").attr("data-name");

    CART.goodsSubTotal("#customisableProductPrice");
    CART.addGoodsSelecter(seleter,name);
  },
  addGoodsSelecter:function(seleter,data){
    if(data==""){
      return ;
    }
    var shtml = "<div class='title'><p><i class='select-option glyphicon glyphicon-ok-circle'></i><span>"+data+"</span></p></div>";
    $(seleter).append(shtml);
  },
  nextOptions:function(i,zs){
    // console.log(i+"|"+zs);
    var next_i = parseInt(i)+1;
    $("#customisableProductSubmit").attr('data-i',next_i);

    if(i==(parseInt(zs)-1)){
      return false;
    }else{
      $("#goods_options_"+next_i).removeClass("hide");

      $("#goods_options_"+next_i).show();

      CART.checkSubmit();
    }
  },
  optionsAction:function(g_options_id,action){
    var quanity = $("input[name='goods_options\["+g_options_id+"\]']").val();

    var price = $("input[name='goods_options\["+g_options_id+"\]']").attr('data-price');
    var currency = $("input[name='goods_options\["+g_options_id+"\]']").attr('data-currency');
    if(action=="add"){
      if(quanity==0){
        $("input[name='goods_options\["+g_options_id+"\]']").parent().parent().show();
        $("#g_options_"+g_options_id).prop("checked",true);
      }
      quanity =parseInt(quanity)+1;

    }else{
      quanity =parseInt(quanity)-1;
      quanity = (quanity<=0) ? 0 : quanity;

      if(quanity==0){
        $("input[name='goods_options\["+g_options_id+"\]']").parent().parent().hide();
        $("#g_options_"+g_options_id).prop("checked",false);
      }
    }
    var zs = Number(price)*parseInt(quanity);
    zs = zs.toFixed(2);
    // console.log($("input[name='goods_options\["+g_options_id+"\]']").parent().parent().find(".price"));
    $("input[name='goods_options\["+g_options_id+"\]']").parent().parent().find(".price").html(currency+""+zs);
    $("input[name='goods_options\["+g_options_id+"\]']").val(quanity);
    CART.goodsSubTotal("#customisableProductPrice");
  },
  goodsSubTotal:function(seleter){
    var goods_price = $(seleter).attr("data-price"),
    currency = $(seleter).attr("data-currency"),
    subtotal = Number(goods_price);

    $("input[name='options\[\]']:checked").each(function(){
      var g_options_id = $(this).val();
      var price = $("input[name='goods_options\["+g_options_id+"\]']").attr("data-price");
      var quanity = $("input[name='goods_options\["+g_options_id+"\]']").val();
      price = isNaN(price) ? 0 : price;
      subtotal = Number(subtotal) + Number(price)*parseInt(quanity);

    });
    $(seleter).html("Total:"+currency+""+subtotal.toFixed(2));
  },
  checkSubmit:function(){
    // console.log(g_options_group_key);

    var flat = true;
    var j =0;
    for (var i = g_options_group_key.length - 1; i >= 0; i--) {
      var requiredoption = $("input[name='requiredoption\["+g_options_group_key[i]+"\]']:checked").val();
      if(typeof(requiredoption)!='undefined'){
        j++;
      }
      // console.log(j);
    };
    flat = j==(g_options_group_key.length) ? true : false;
    // console.log(j==(g_options_group_key.length));
    if(flat){
      // $("#customisableProductSubmit").attr("disabled",false);
      $("#customisableProductSubmit").attr("type","submit");
      $("#productDialogDetails .data-message").remove("p");
    }else{
      $("#customisableProductSubmit").attr("type","button");
    }
  },
  additional:function(coup_id){
    $.ajax({
      type:"post",
      data:{coup_id:coup_id,type:"additional"},
      url:"index.php?r=cart/ajax",
      success:function(data){
        // console.log(data);
        if(isjson(data)){
          data = JSON.parse(data);
          INFO.alert_info(data.message);
        }else{
          $(".order-options-modal .content").html(data);
          $(".order-options-modal").modal();
        }
        return false;
      },
      error:function(msg){
        console.log(msg.responseText);
      }
    });
  },
  formSubmit:function(){

    var send = $("input[name='cart\[send\]']:checked").val();
    var total = $("#cart-total").attr("data-total");
    try{
      var free_goods = $("input[name='cart\[free_goods\]']").val();
    }catch(e){
      // alert(e);
    }


    total = Number(total);

    if(typeof(send)=='undefined'){
      console.log(send);
      //alert(send);
      modal_alert("Please choose Collection or Delivery!");
      return false;
    }

    // if(total<Number(PAYMENT_INFO.Minpay)||(send=="deliver"&&total<Number(PAYMENT_INFO.Minimum))){
    //     return false;
    // }
    if(send=="deliver"&&total<Number(PAYMENT_INFO.Minimum)){
      var message = $(".home-right .need").html();
      message = PAYMENT_INFO.NeedInfo;
      modal_alert(message);
      return false;

    }
    // console.log(free_goods!=undefined);
    if(free_goods!=undefined&&!window.cart_submit_flat){
      CART.additional(free_goods);
      return false;
    }
    $(".cart-checkout-from").submit();
    // return false;
  }
}


// alert
function modal_alert(data){
  var flat = typeof(arguments[1])!='undefined';
  if(flat){
    $("#alert_all .cancel").html("Cancel");
    $("#alert_all .accept").show();
  }else{
    $("#alert_all .accept").hide();
    $("#alert_all .cancel").html("Ok");
  }
  // console.log('alert_all_content'+data);
  $("#alert_all_content").html(data);
  // console.log($("#alert_all_content").html());
  // document.getElementById("alert_all_a").click();
  // $("#alert_all_a").click();
  $('#alert_all').modal('toggle');
}

// alert info
var INFO = {
  alert_info:function(data){
    $(".alert-div .info").html(data);
    $(".alert-div").slideDown();
    window.setTimeout(function(){
      INFO.alert_hide();
    },3000);
    CART.loadcart();

  },
  alert_hide:function(){
    $(".alert-div").slideUp();
  },
  alert_msg:function(data){
    $(".alert-div .info").html(data);
    $(".alert-div").slideDown();
  },
  show_msg:function(data){
    if(typeof(data.responseText)!="undefined"){

      console.log(data.responseText);
    }else{
      console.log(data);
    }
  }
}

//
var MEMBER = {
  address:function(){
    $("#member_content").load("index.php?r=member/default/address #profile");
  }
}

// 订单管理
var ORDER = {
  status:false,
  flag:false,
  mail : function(){
    $.ajax({
      type:"post",
      url:"index.php?r=cart/order-mail",
      success:function(data){
        console.log(data);
      }
    });
  },
  payment:function(flag){
    if(flag){
      $(".payment-modal .modal-footer").find(".login-btn").hide();
      $(".payment-modal").modal();
    }else{
      $(".payment-modal .modal-footer").find(".login-btn").show();
      $(".payment-modal").modal();
    }

    // 延遲50S操作
    window.setTimeout(function(){
      ORDER.flag = true;
      ORDER.doUrl();
    },30000);

    // //循环执行，每隔10秒钟执行一次
    window.setInterval(function(){
      if(ORDER.flag){
        ORDER.doUrl();
      }
    }, 10000);

  },
  // 獲取訂單狀態
  getStatus:function(){
    $.ajax({
      type:"post",
      url:"index.php?r=order/ajax",
      success:function(data){
        ORDER.status = data;
      }
    });
  },
  // 訂單操作
  doUrl:function(){

    ORDER.getStatus();
    if(ORDER.status){
      var url = $(".payment-modal .login-btn").attr("href");
      console.log(url);
      window.location.href = url;
    }
  }
}
