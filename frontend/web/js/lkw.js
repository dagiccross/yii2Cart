$(function() {

	$("#news-btn").click(function() {
    $(".email-wrap").slideToggle("slow");
  });
      
  var home_width=$(".home").width();
  var homewrap_width=$(".home-wrap").width();
  var home_left=(home_width-homewrap_width)/2;
  $(".home-wrap").css("left",home_left);

  //获取窗口高度
  var winHeight = $(window).height();   


  $("#homeCarousel").css("height",winHeight);
  $(".ws_images img").css("height",winHeight);
  
  // 控制导航居中  
  $("li > .down-nav").each(function(){
    var nav_width=$(this).width();
    $(this).css({"left":"calc(50% - "+(nav_width/2)+"px)"});
  });
  
  //获取窗口高度
  var winHeight = $(window).height();     

  $("#homeCarousel").css("height",winHeight);
  $(".ws_images img").css("height",winHeight);
  // end 

  // 下拉菜单
  $(".home-nav > li").each(function(){
    $(this).click(function(){
      // console.log($(this));
      $(".home-nav > li").removeClass("kai");
      $(this).addClass("kai");
    });
  });
  $(".down-nav > li").each(function(){
    $(this).click(function(){
      $(".down-nav > li").removeClass("active");
      $(this).addClass("active");
    });
    
  });
  //bobo 动画

	var buttons = document.querySelectorAll(".radmenu a");

  for (var i=0, l=buttons.length; i<l; i++) {
    var button = buttons[i];
    // button.onclick = setSelected;
  }

  function setSelected(e) {
    if (this.classList.contains("selected")) {
      this.classList.remove("selected");
      if (!this.parentNode.classList.contains("radmenu")) {
        this.parentNode.parentNode.parentNode.querySelector("a").classList.add("selected")
      } else {
        this.classList.add("show");
      }
    } else {
      this.classList.add("selected");
      if (!this.parentNode.classList.contains("radmenu")) {
        this.parentNode.parentNode.parentNode.querySelector("a").classList.remove("selected")
      } else {
        this.classList.remove("show");
      }
    }
    return false;
  }
	
  $(".arrow-right").each(function(){
    $(this).click(function(){
      $(this).parent(".link-li").addClass("bounceOutLeft animated");
      setTimeout(function(){
        $('.link-li').removeClass("bounceOutLeft animated");            
      },1000);
    });
  });     	

  // 控制显示
  $(".more-btn").click(function(){
    var show_id = $(this).attr('data-div');
    if(typeof(show_id)!='undefined'){
      $("."+show_id).slideToggle();
    }
  });
  // end bobo
});

$(document).ready(function() {
  $(".animsition").animsition({
    // inClass: 'fade-in-down',
    // outClass: 'fade-out-down',
    inDuration: 1500,
    outDuration: 800,
    linkElement: '.animsition-link',
    // e.g. linkElement: 'a:not([target="_blank"]):not([href^=#])'
    loading: false,
    loadingParentElement: 'body', //animsition wrapper element
    loadingClass: 'animsition-loading',
    loadingInner: '', // e.g '<img src="loading.svg" />'
    timeout: true,
    timeoutCountdown: 5000,
    onLoadEvent: true,
    browser: [ 'animation-duration', '-webkit-animation-duration'],
    // "browser" option allows you to disable the "animsition" in case the css property in the array is not supported by your browser.
    // The default setting is to disable the "animsition" in a browser that does not support "animation-duration".
    overlay : false,
    overlayClass : 'animsition-overlay-slide',
    overlayParentElement : 'body',
    transition: function(url){ window.location.href = url; }
  });
});