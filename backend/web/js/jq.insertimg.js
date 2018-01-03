var IMGINSERT = {
	name : "imageInsert",
	url : "index.php?r=upfile/json",
	writeDiv : function(){
		var doc_width = $(document).width();
		var shtml = "<div id='inserimg_dialog'style='display: none;width: 650px;height: 440px;position: fixed;z-index: 811213;left: 358px;top: 10%;'>";
		shtml +="<div class='ke-dialog-content' style='background-color: #FFF;width: 100%;height: 100%;color: #333;border: 1px solid #A0A0A0;'>";
		shtml +="<div id='inserimg_header' style='border-width: 0px 0px 1px;border-style: none none solid;border-color: -moz-use-text-color -moz-use-text-color #CFCFCF;-moz-border-top-colors: none;-moz-border-right-colors: none;-moz-border-bottom-colors: none;-moz-border-left-colors: none;border-image: none;margin: 0px;padding: 0px 10px;background: #F0F0EE ;height: 24px;font: 12px/24px tahoma,verdana,helvetica;text-align: left;color: #222;cursor: move;'>Upload Manager";
		shtml +="<span onclick='IMGINSERT.close()' title='Close' style='display: block;width: 41px;height: 16px;position: absolute;right: 6px;top: 0px;cursor: pointer;'>Close</span></div>";
		shtml +="<div style='height: 447px;' class='ke-dialog-body'>";
		shtml +="<div style='padding:10px 20px;'>";
		shtml +="<div id='ke-plugin-filemanager-header'>";
		shtml +="<div class='ke-clearfix'>";
		shtml +="<input value='Index' type='button' onclick='javascript:IMGINSERT.gotoPage(1)'/>";
		shtml +="<input value='Prev' type='button' onclick='javascript:IMGINSERT.gotoPage(\"pre\");'/>";
		shtml +="<input value='Next' type='button' onclick='javascript:IMGINSERT.gotoPage(\"next\")'/>";
		shtml +="<input value='Last' type='button' onclick='javascript:IMGINSERT.gotoPage(\"last\")'/>";
		shtml +="</div></div>";
		shtml +="<div id='ke-plugin-filemanager-body' style='overflow: scroll;background-color: #FFF;border-color: #848484 #E0E0E0 #E0E0E0 #848484;border-style: solid;border-width: 1px;width: auto;height: 370px;padding: 5px;'></div></div>";
		shtml +="</div>";
		shtml +="</div>";
		shtml +="</div>";
		document.write(shtml);
	},
	close : function(){
		document.getElementById("inserimg_dialog").style.display="none";
	},
	show : function(){
		$("#inserimg_dialog").slideDown();
	},
	loadimg : function(url,page,data_id){
		$.ajax({
			type:"GET",
			data:{page:page,data_id:data_id},
			url:url,
			success:function(data){
				//console.log(data);
				document.getElementById("ke-plugin-filemanager-body").innerHTML = data;
				
			}
		});
	},
	reloadimg : function(page,id){
		var self = this;
		console.log(self.url);
		self.loadimg(self.url,page,id);
	},
	gotoPage : function(type){
		var self = this;

		var page = $("#loadimg_info").attr("data-page");
		var total = $("#loadimg_info").attr("data-total");
		var data_id = $(".thumbnail").attr("data-id");

		if(type=="next"){
				self.reloadimg(parseInt(page)+1,data_id);
		}else if(type=="pre"){
				
				page = parseInt(page)-1;
				page = page>0 ? page : 1;
				self.reloadimg(page,data_id);
		}else if(type=="last"){
				self.reloadimg(total,data_id);
		}else{
				self.reloadimg(1,data_id);
		}
	},
	setUrl : function(url){
		this.url = url;
	},
	init : function(){
		var self = this;
		self.writeDiv();
		self.reloadimg(1,0);
		self.setAction();
	},
	addAction : function(selecter,action,method){
		document.getElementById(selecter).action = method;
	},
	setAction : function(){
		$("#inserimg_dialog").delegate(".thumbnail","dblclick",function(){
			var id = $(this).attr("data-id");
			var src = $(this).attr("data-src");
			$(id).val(src);
			$("#inserimg_dialog").hide();
		});
	}
}

function ImgOptions(options){
	this.init.apply(this,arguments);

}

ImgOptions.prototype = IMGINSERT;

imgInsert = new ImgOptions();

var posX;
var posY;
fdiv = document.getElementById("inserimg_dialog");           
document.getElementById("inserimg_header").onmousedown=function(e){
    if(!e) e = window.event;  //IE
    posX = e.clientX - parseInt(fdiv.style.left);
    posY = e.clientY - parseInt(fdiv.style.top);
    document.onmousemove = mousemove;           
}

document.onmouseup = function(){
    document.onmousemove = null;
}
function mousemove(ev){
    if(ev==null) ev = window.event;//IE
    fdiv.style.left = (ev.clientX - posX) + "px";
    fdiv.style.top = (ev.clientY - posY) + "px";
}

function del_image_attr(id){
	$("#image_attr_div_"+id).remove();
}

function del_div_id(id){
		$(id).remove();
}
function select_image_attr(id){
    	// $("#inserimg_dialog").slideDown();
    	imgInsert.show();
    	$(".thumbnail").attr("data-id",id);
}

