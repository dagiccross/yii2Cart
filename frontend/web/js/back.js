$( document ).ready(function() {
	// Set canvas drawing canvas
  var d_width = $(document.body).width(),d_height,
  		b_width = 1920,b_height=847;
  		d_height = (d_width/b_width)*b_height,
  		bai_height = (d_width/b_width)*76,
  		rate = d_width/b_width,
  		hander_width = 488*rate,
  		hander_height = 505*rate,
  		boat_width = 635*rate,
  		boat_height = 502*rate;
  		
  		//ÉèÖÃÍ·±³¾°
  		$("header").css({height:d_height});
  		$(".bai").css({height:bai_height});
  		$(".xinxin").css({height:d_height});
  		$(".hander").css({height:hander_height});
  		$(".boat").css({height:boat_height});
  		
  		window.onresize = function(){
  			d_width = $(document.body).width();
  			d_height = (d_width/b_width)*b_height;
	  		bai_height = (d_width/b_width)*76;
	  		rate = d_width/b_width;
	  		hander_width = 488*rate;
	  		hander_height = 505*rate;
	  		boat_width = 635*rate;
	  		boat_height = 502*rate;
  			$("header").css({height:d_height});
	  		$(".bai").css({height:bai_height});
	  		$(".xinxin").css({height:d_height});
	  		$(".hander").css({height:hander_height});
	  		$(".boat").css({height:boat_height});
  		}

  		var body_width=$(".body").width();
  		var head_width=$(".head-top").width();
  		var head_left=(body_width-head_width)/2;
  		$(".head-top").css("left",head_left);
  		console.log(head_left);




       $("#go-top").click(function() {
          jQuery("html, body").animate({
              scrollTop: 0
          }, 600);
      });
      $(window).scroll(function() {
          if ($(this).scrollTop() > 300) {
              $('#go-top').fadeIn("fast");
          } else {
              $('#go-top').stop().fadeOut("fast");
          }
      });



});	