"use strict";

$(document).ready(function() {
    $('#select_category').dropdown({
        allowCategorySelection: true,
      
        onChange: function(value, text, choice) { 
            var title = $(choice).attr('title');            
            $(this).children('.text').html(title);
            
            arikaim.page.loadContent({
                id: 'translations_content',
                component: 'category::admin.translations.view.items',
                params: { 
                    uuid: value
                }
            },function(result) {               
            });  
        }
    });
});