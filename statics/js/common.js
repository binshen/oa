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
});