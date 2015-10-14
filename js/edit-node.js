$(function(){
    $('#EditTabs').tabs();
    $('#node_edit-form').form({
        success: function(fields, data, form){
            if (data.is_error) {
                
            } else {
                $('body').data('modalWindow').close();
            }
        }
    });
});