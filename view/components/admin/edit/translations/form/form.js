'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#category_translaton_form",function() {  
        var language = $('#choose_translation_language').dropdown('get value');
        $('#language').val(language);
        
        return category.saveTranslation('#category_translaton_form');
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});