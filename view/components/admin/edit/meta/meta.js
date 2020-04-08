"use strict";

$(document).ready(function() {   
    arikaim.ui.form.onSubmit("#meta_tags_form",function() {            
        return arikaim.put('/api/category/admin/update/meta','#meta_tags_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});