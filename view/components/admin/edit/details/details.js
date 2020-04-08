'use strict';

$(document).ready(function() {
    arikaim.ui.form.onSubmit("#category_form",function() {            
        return arikaim.put('/api/category/admin/update','#category_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});