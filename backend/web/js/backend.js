var GOODS = {
	addoGroup:function(div_id){
		console.log(window.GOODSOPTIONSGROUP);
		// window.GOODSOPTIONSGROUP = typeof(window.GOODSOPTIONSGROUP==undefined) ? 1 : window.GOODSOPTIONSGROUP;
		
		var html = '<div class="row options_group bg-warning" id="g_options_group'+window.GOODSOPTIONSGROUP+'">';
		html += '<div class="col-md-2 col-xs-2">';
		html += 'Group '+window.GOODSOPTIONSGROUP+':</div>';
		html += '<input type="hidden" name="GoodsOptionsGroup['+window.GOODSOPTIONSGROUP+'][g_options_group_id]" class="delete-data" data-type="group"/>';
		html += '<input name="GoodsOptionsGroup['+window.GOODSOPTIONSGROUP+'][name]" class="form-control width_16" placeholder="Group Name"/>';
		html += '<input name="GoodsOptionsGroup['+window.GOODSOPTIONSGROUP+'][options]" class="form-control width_16" placeholder="Group Name"/>';
		html += '<select name="GoodsOptionsGroup['+window.GOODSOPTIONSGROUP+'][options_type]" class="form-control width_16"><option value="radio">radio</option><option value="checkbox">checkbox</option><option value="select">select</option></select>';
		html += '<div class="col-md-2 col-xs-2"><input type="checkbox" name="GoodsOptionsGroup['+window.GOODSOPTIONSGROUP+'][required]" value="1" class="width_5"/>required</div>';
		html += '<a class="glyphicon glyphicon-plus" onclick="javascript:GOODS.addOptions(this,\''+window.GOODSOPTIONSGROUP+'\')">Add Options</a>&nbsp;';
		html += '<i class="glyphicon glyphicon-trash" onclick="javascript:GOODS.deleteRow(this)"></i>';
		html += '</div>';

		// row的数量添加
		window.GOODSOPTIONSGROUP ++;
		$("#"+div_id).append(html);
	},
	addOptions:function(obj,group_rowid){
		var html = '<div class="row goods_options bg-success">';
		html += '<div class="col-md-1 col-xs-1">';
		html += 'options :</div>';
		html += '<input type="hidden" readonly="readonly" name="GoodsOptionsGroup['+group_rowid+'][options_value][g_options_id][]" class="delete-data" data-type="options"/>';
		html += '<input name="GoodsOptionsGroup['+group_rowid+'][options_value][name][]" class="form-control width_12" placeholder="Option Name"/>';
		html += '<input name="GoodsOptionsGroup['+group_rowid+'][options_value][quanity][]" class="form-control width_12" placeholder="Quanity"/>';
		html += '<select name="GoodsOptionsGroup['+group_rowid+'][options_value][subtract][]" class="form-control width_5"><option value="0">0</option><option value="1">1</option></select>';
		html += '<input name="GoodsOptionsGroup['+group_rowid+'][options_value][price][]" class="form-control width_12" placeholder="price"/>';
		html += '<select name="GoodsOptionsGroup['+group_rowid+'][options_value][price_prefix][]" class="form-control width_5"><option value="+">+</option><option value="-">-</option></select>';
		html += '<input name="GoodsOptionsGroup['+group_rowid+'][options_value][weight][]" class="form-control width_12" placeholder="weight"/>';
		html += '<select name="GoodsOptionsGroup['+group_rowid+'][options_value][weight_prefix][]" class="form-control width_5"><option value="+">+</option><option value="-">-</option></select>';

		html += '<i class="glyphicon glyphicon-trash" onclick="javascript:GOODS.deleteRow(this)"></i>';
		html += '</div>';
		$(obj).parent().append(html);
	},
	deleteRow:function(obj){
		var id = $(obj).parent().children('.delete-data').val();
		var type = $(obj).parent().children('.delete-data').attr("data-type");
		var html = '';

		if(id!=undefined&&id!=''){
			html = '<input name="'+type+'delete[]" value="'+id+'" type="hidden"/>';
		}
		$("#options_group_delete_row").append(html);
		$(obj).parent().remove();
	}
}