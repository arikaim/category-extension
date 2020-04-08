'use strict';

$(document).ready(function() {   
    arikaim.ui.form.onSubmit("#category_description_form",function() {            
        return arikaim.put('/api/category/admin/update/description','#category_description_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});