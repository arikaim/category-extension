'use strict';

function applyTranslation(result) {   
    if (isEmpty(result.fields) == false) {
        // apply translation
        $('#title').val(result.fields.title);   
        Object.entries(result.fields).forEach(function([key, value]) {           
            $('#' + key).val(value);          
        });
    }
}

arikaim.component.onLoaded(function() {
    $('#choose_translation_language').dropdown({
        onChange: function(value) {
            var uuid = $('#category_translations_content').attr('category-uuid');
            $('.translate-button').attr('language',value);

            arikaim.page.loadContent({
                id: 'category_translations_content',
                component: 'category::admin.edit.translations.form',
                params: { 
                    uuid: uuid,
                    language: value 
                }
            });
        }
    });
});