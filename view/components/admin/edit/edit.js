'use strict';

$(document).ready(function() {

    function loadEditTabs(uuid, language) {
        arikaim.page.loadContent({
            id: 'category_tabs_content',
            component: 'category::admin.edit.tabs',
            params: { 
                uuid: uuid,
                language: language 
            }
        });  
    }

    $('#choose_language').dropdown({
        onChange: function(value) {
            var uuid = $('#select_category').dropdown('get value');                    
            loadEditTabs(uuid,value);           
        }
    }); 

    $('#select_category').dropdown({
        allowCategorySelection: true,
        onChange: function(value, text, choice) { 
            var title = $(choice).attr('title');
            var language = $(choice).attr('language');            
            $(this).children('.text').html(title);
           
            if (isEmpty(value) == true) {
                arikaim.ui.hide('#category_tabs_content');
            } else {
                arikaim.ui.show('#category_tabs_content');
            }
            loadEditTabs(value,language);
        }
    });
});