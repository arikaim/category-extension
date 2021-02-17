'use strict';

arikaim.component.onLoaded(function() {
    $('#choose_language').dropdown({
        onChange: function(value) {
            $('#language').val(value);
        }
    }); 

    arikaim.ui.form.onSubmit("#category_form",function() {  
        var language = $('#choose_language').dropdown('get value');       
        $('#language').val(language);

        return arikaim.post('/api/category/admin/add','#category_form');
    },function(result) {
        arikaim.ui.form.clear('#category_form');
        arikaim.ui.form.showMessage(result.message);
        // load edit category       
        category.loadEditCategory(result.uuid,result.language);
    });
});
