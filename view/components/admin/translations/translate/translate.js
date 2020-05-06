'use strict';

$(document).ready(function() {
    arikaim.ui.button('.translate-button',function(element) {
        var language = $('#choose_language').dropdown('get value');
        var branch = $('#branch').dropdown('get value');
        
        return category.translateCategories(language,branch,function(result) {            
            arikaim.page.toastMessage(result.message);

            arikaim.page.loadContent({
                id: 'translations_content',
                component: 'category::admin.translations.translate',
                params: { 
                    language: language,
                    branch: branch 
                }
            });             
        },function(error) {
            arikaim.page.toastMessage({
                message: error,
                class: 'error'
            });          
        });
    });
});