;
(function($) {
	window._timeouts = [];
	
	window.timeout_name = [];

	$.fn.extend({
		bobo:function(){
			// 获取object
			var obj = $(this);
			var self = this;

			// 画布长宽
			var max_width = obj.width(),
			max_height = obj.height(),
			half_width = max_width/2,
			half_height = max_height/2;

			// 外圆个数
			var list = obj.find("ul>li").length;
			
			// 平均圆角
			var angle = 360/list;

			// 初始化小球
			obj.topDefault(max_width,max_height,half_width,half_height,angle);

			// console.log(angle);

			// 点击事件
			obj.find(".big-bo").click(function(){
				
				obj.children("hr").remove();
				obj.cleartimeout();

				for (var i = 0; i < list; i++) {
					// console.log(i);
					// 初始化半径和角度
					var li_x = BOBO.randomNum(99,130);
					var angle_ran = BOBO.randomNum(0,20);

					var item = obj.find("ul>li").eq(i);
					item.children("a").css({'height':li_x,'width':li_x});

					obj.setDefault(item,li_x);
					
					var angle1 = parseInt(angle*i)+angle_ran;
						
					var cos = Math.cos((2*Math.PI / 360) * angle1);
					var sin = Math.sin((2*Math.PI / 360) * angle1);

					// 计算出角度的XY
					var cx = half_width* cos;
					var cy = half_height* sin;

					cx = (max_width - li_x)/2 - cx;
					cy = (max_height - li_x)/2 - cy;

					if(cy<0){
						cy = 0- cy;
					}else if(cy>=(max_height-li_x)){
						cy =cy - li_x/2 - 10;
					}

					if(cx<0){
						cx = 0- cx;
					}else if(cx>=(max_width-li_x)){
						cx = cx - li_x/2 - 10;
					}

					var data = {'top':cy,'left':cx};
					var line_number = Number(i);
					var  center = {x:half_width,y:half_height};
					var  xiao_bo = {x:(cx+li_x/2),y:(cy+li_x/2)};

					window._timeouts.push(obj.timeout(item,data,i,xiao_bo,center));
					
				};				
			});

			// 滚动事件
			$(window).scroll(function(){
				var objTop = obj.offset().top;

				var t = document.documentElement.scrollTop || document.body.scrollTop;
				
				// 当到指定高度时
			    if(t>(objTop-max_height/2)&& t< (objTop+max_height)){
			    	console.log("start");
			    	obj.children("a").eq(0).addClass("selected");
			    	obj.children("a").eq(0).removeClass("show");
			    	
			    	var  center = {x:half_width,y:half_height};

			    	obj.dowCircle(list,center);

			    }else{
			    	obj.children("a").eq(0).addClass("show");
			    	obj.children("a").eq(0).removeClass("selected");
			    	obj.children("hr").remove();
			    	obj.topDefault(max_width,max_height,half_width,half_height,angle);
			    }
			});
			
			
		},
		setDefault:function(items,r){
			items.css({
				'top': 'calc(50% - '+(r/2)+'px)',
				'left': 'calc(50% - '+(r/2)+'px)'
			});

		},
		topDefault : function(max_width,max_height,half_width,half_height,angle){

			var obj = $(this);
			var i = 0;
			obj.find("ul>li").each(function(){
				var cy = $(this).children("a").height()/2,
				cx = $(this).children("a").width()/2;

				// 初始化半径和角度
				var li_x = BOBO.randomNum(99,130);
				var angle_ran = BOBO.randomNum(0,20);

				$(this).children("a").css({'height':li_x,'width':li_x});

				var angle1 = parseInt(angle*i)+angle_ran;

				var cos = Math.cos((2*Math.PI / 360) * angle1);
				var sin = Math.sin((2*Math.PI / 360) * angle1);

				// 计算出角度的XY
				var cx = half_width* cos;
				var cy = half_height* sin;

				cx = (max_width - li_x)/2 - cx;
				cy = (max_height - li_x)/2 - cy;

				if(cy<0){
					cy = 0- cy;
				}else if(cy>(max_height-li_x)){
					cy =cy - li_x/2;
				}

				if(cx<0){
					cx = 0- cx;
				}else if(cx>(max_width-li_x)){
					cx = cx - li_x/2 ;
				}

				
				$(this).css({'top':-2*li_x,'left':cx});
				$(this).attr("data-top",cy);
				$(this).attr("data-left",cx);
				$(this).attr("data-li",li_x);
				i++;
			});
		},
		dowCircle : function(list,center){
			var obj = $(this);

			for (var i = 0; i < list; i++) {
				var item = obj.find("ul>li").eq(i);
				
				var cy = item.attr("data-top");
				var cx = item.attr("data-left");
				var li_x = item.attr("data-li");

			    var  xiao_bo = {x:(cx+li_x/2),y:(cy+li_x/2)};

				if(typeof(cy)=='undefined'){

				}else{

					window._timeouts.push(obj.timeoutDown(item,cy,i,xiao_bo,center));

						
				}

			};

			
		},
		circleTo : function(max_width,max_height,half_width,half_height,angle){
			var obj = $(this);
			var i = 0;

			obj.find("ul>li").each(function(){
				// 初始化半径和角度
				var li_x = BOBO.randomNum(99,130);
				var angle_ran = BOBO.randomNum(0,20);

				setTimeout(function(){

					// console.log('setTimeout');

					$(this).children("a").css({'height':li_x,'width':li_x});

					var angle1 = parseInt(angle*i)+angle_ran;
						
					var cos = Math.cos((2*Math.PI / 360) * angle1);
					var sin = Math.sin((2*Math.PI / 360) * angle1);

					// 计算出角度的XY
					var cx = half_width* cos;
					var cy = half_height* sin;

					cx = (max_width - li_x)/2 - cx;
					cy = (max_height - li_x)/2 - cy;

					if(cy<0){
						cy = 0- cy;
					}else if(cy>=(max_height-li_x)){
						cy =cy - li_x/2 - 10;
					}

					if(cx<0){
						cx = 0- cx;
					}else if(cx>=(max_width-li_x)){
						cx = cx - li_x/2 - 10;
					}

				
					$(this).css({'top':cy,'left':cx});
						
						
					// 设置线条的角度
					var line_number = Number(i);
					var  center = {x:half_width,y:half_height};
					var  xiao_bo = {x:(cx+li_x/2),y:(cy+li_x/2)};

					var r = BOBO.pointLine(xiao_bo,center);
					var line_angle = BOBO.deg(xiao_bo,center);

					obj.find("hr").eq(i).css({
						'transform': 'rotate(' + line_angle + 'deg)',
						'-webkit-transform': 'rotate(' + line_angle + 'deg)',
						'-ms-transform': 'rotate(' + line_angle + 'deg)',
						'-moz-transform': 'rotate(' + line_angle + 'deg)',
						'transform-origin': '0',
						'-webkit-transform-origin': '0',
						'-ms-transform-origin': '0',
						'-moz-transform-origin': '0',
						// 'z-index': '-1',
						'width':r
					});

					i++;
				},1000);
				
			});
		},
		toPoint : function(items,data){
			// var myDate = new Date();
			// console.log(myDate.toLocaleTimeString());
			items.css(data);
		},
		drawLine : function(i,xiao_bo,center){
			var obj = $(this);
			obj.append('<hr/>');

			// 设置线条的角度
			var r = BOBO.pointLine(xiao_bo,center);
			var line_angle = BOBO.deg(xiao_bo,center);
			
			obj.find("hr").eq(i).css({
				'transform': 'rotate(' + line_angle + 'deg)',
				'-webkit-transform': '-webkit-rotate(' + line_angle + 'deg)',
				'-ms-transform': '-ms-rotate(' + line_angle + 'deg)',
				'-moz-transform': '-moz-rotate(' + line_angle + 'deg)',
				'-webkit-transform-origin': '0',
				'-ms-transform-origin': '0',
				'-moz-transform-origin': '0'
				// 'z-index': '-1',
				// 'width':r
			});
			obj.find("hr").eq(i).animate({'width':r});

		},
		timeout : function(item,data,i,xiao_bo,center){
			var obj = $(this);
			var items = item;
			var timeout = 800*(i+1);
			// console.log(timeout);
			
			window.timeout_name[i] = setTimeout(function(){
				// console.log(i);
				obj.toPoint(item,data);
			},timeout);

			window.timeout_name[i+'line'] = setTimeout(function(){
				obj.drawLine(i,xiao_bo,center);
			},timeout+100);
			
			return i;
		},
		timeoutDown : function(item,data,i,xiao_bo,center){
			var obj = $(this);
			var items = item;
			var timeout = 800*(i+2);
			// console.log(timeout);
			window.timeout_name[i+'down'] = setTimeout(function(){
				// console.log(i);
				var bbb = parseInt(data);
				items.css("top",bbb);

			},timeout);

			window.timeout_name[i+'downline'] = setTimeout(function(){
				obj.drawLine(i,xiao_bo,center);
			},timeout+100);

			// setTimeout(function(){
			// 	obj.drawLine(i,xiao_bo,center);
			// },timeout+100);
		},
		cleartimeout : function(){
			var timeout;
			var obj = this;
			console.log("window._timeouts:"+window.timeout_name);
	        while(timeout = window.timeout_name.shift()){
	        	console.log("timeout"+timeout);

	            clearTimeout(timeout);
	        }
		}
		
	});
	
	var BOBO = {
		randomNum : function(Min,Max){   
			var Range = Max - Min;   
			var Rand = Math.random();   
			return(Min + Math.round(Rand * Range));   
		},
		centerPoint : function(ele, offsetParent) {
			var point = {};
			point.x = ele.offset().left - offsetParent.offset().left + parseInt(ele.width() / 2);
			point.y = ele.offset().top - offsetParent.offset().top +  parseInt(ele.height() / 2);
			return point;
		},
		pointLine : function(outer, center) {
			return Math.round(Math.sqrt(Math.pow(Math.abs(outer.x - center.x), 2) + Math.pow(Math.abs(outer.y - center.y), 2)));
		},
		deg : function(outerInfo, centerInfo) {
			var deg = Math.round(Math.atan((Math.abs(outerInfo.y - centerInfo.y)) / (Math.abs(outerInfo.x - centerInfo.x))) * 180 / 3.14);
			// adjust deg
			if (outerInfo.x < centerInfo.x && outerInfo.y < centerInfo.y) {
				deg += 180;
			}
			if (outerInfo.x < centerInfo.x && outerInfo.y > centerInfo.y) {
				deg = 180 - deg;
			}
			if (outerInfo.x > centerInfo.x && outerInfo.y < centerInfo.y) {
				deg = 360 - deg;
			}
			return deg;
		},
		vendorPrefixes : function(items,prop,value){
	        ['-webkit-','-moz-','-o-','-ms-',''].forEach(function(prefix){
	            items.css(prefix+prop,value);
	        });
    	}
	};
})(jQuery);