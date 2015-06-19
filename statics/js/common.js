$(function () {
    var total_check_all = $('input.J_check_all');
    
    //遍历所有全选框
    $.each(total_check_all, function () {
        var check_all = $(this);
        var check_items = $('input.J_check');
        //点击全选框
        check_all.change(function (e) {
            if ($(this).prop('checked')) {
                //全选状态
                check_items.prop('checked', true);
            } else {
                //非全选状态
                check_items.prop('checked', false);
            }
        });

        //点击非全选时判断是否全部勾选
        check_items.change(function () {
            if ($(this).prop('checked')) {
                if (check_items.filter(':checked').length === check_items.length) {
                    //已选择和未选择的复选框数相等
                    check_all.prop('checked', true);
                }
            } else {
                check_all.prop('checked', false);
            }
        });
    });

    if ($('button.J_ajax_del').length) {
    	$('.J_ajax_del').on('click', function(e) {
    		e.preventDefault();
    		$('#my-confirm').modal({
    			relatedTarget: this, 
    			onConfirm: function(options) {
    	            var url = $(this.relatedTarget).attr('data-url');
    	            $.getJSON(url).done(function (data) {
    	            	if (data.referer) {
                            location.href = data.referer;
                        } else {
                            reloadPage(window);
                        }
                    });
    	        },
    	        // closeOnConfirm: false,
    	        onCancel: function() {
    	        }
    	    });
    	});
    }
    
    //ifrmae
	$content=$("#contentq");
	$mainFrame=$("#mainFrame");
	$menuDiv = $("#menuDiv");
	
	var headerheight=50;
	$content.height($(window).height()-headerheight-$('footer').height());
	$menuDiv.height($(window).height()-headerheight-$('footer').height());
	if($(window).width()-$('.admin-sidebar').width()-1 > 380){
		$mainFrame.width($(window).width()-$('.admin-sidebar').width()-2);
	}else{
		$mainFrame.width($(window).width());
	}
	
	$(window).resize(function(){
		$content.height($(window).height()-headerheight-$('footer').height());
		$menuDiv.height($(window).height()-headerheight-$('footer').height());
		if($(window).width()-$('.admin-sidebar').width()-1 > 380){
			$mainFrame.width($(window).width()-$('.admin-sidebar').width()-2);
		}else{
			$mainFrame.width($(window).width());
		}
	});
	

//	var iframe = $mainFrame.get(0);
//	if (!/*@cc_on!@*/0) { //if not IE 
//		iframe.onload = function(){ 
//			//$("#load").hide();
//		}; 
//	} else { 
//		iframe.onreadystatechange = function(){ 
//			if (iframe.readyState == "complete"){ 
//				//$("#load").hide();
//			} 
//		}; 
//	}
	
	$(".am-pagination").find('a').click(function() {
		if(!$(this).parent().hasClass('am-active')) {
			parent.document.getElementById("load").style.display = 'block';
		}
    })

});

//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location = win.location;
    location.href = location.pathname + location.search;
}

//页面跳转
function redirect(url) {
    location.href = url;
}

//点击菜单打开ifrmae页面
function open_content(url){
	$("#mainFrame").attr("src",url);
	$("#load").show();
	$("#load").show();
	$("#mainFrame").load(function(){
		$("#load").hide();
    });
}
