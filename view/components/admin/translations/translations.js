'use strict';

$(document).ready(function() {
    $('#branch_dropdown').dropdown({       
        onChange: function(value) { 
            var language = $('#choose_language').dropdown('get value');
            loadTranslate(value,language);         
        }
    });

    $('#choose_language').dropdown({
        onChange: function(value) {   
            var branch = $('#branch').dropdown('get value');
            loadTranslate(branch,value);                  
        }
    }); 

    function loadTranslate(branch, language) {
        arikaim.page.loadContent({
            id: 'translations_content',
            component: 'category::admin.translations.translate',
            params: { 
                language: language,
                branch: branch 
            }
        });     
    }
});