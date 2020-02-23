"use strict";

$(document).ready(function() {

    $('#choose_language').dropdown({
        onChange: function(value) {
            var uuid = $('#select_category').dropdown('get value');           
            arikaim.page.loadContent({
                id: 'form_content',
                component: 'category::admin.form',
                params: { 
                    uuid: uuid,
                    language: value 
                }
            },function(result) {
                initEditCategoryForm();
            });   
            $('#language').val(value);
        }
    }); 

    function initEditCategoryForm() {
        arikaim.ui.form.onSubmit("#category_form",function() {            
            return arikaim.put('/api/category/admin/update','#category_form');
        },function(result) {
            arikaim.ui.form.showMessage(result.message);
        });
    };

    $('#select_category').dropdown({
        allowCategorySelection: true,
      
        onChange: function(value, text, choice) { 
            var title = $(choice).attr('title');
            var language = $(choice).attr('language');            
            $(this).children('.text').html(title);
            console.log('show');

            arikaim.ui.show('#category_form_content');

            arikaim.page.loadContent({
                id: 'form_content',
                component: 'category::admin.form',
                params: { 
                    uuid: value,
                    language: language 
                }
            },function(result) {
                initEditCategoryForm();
            });  
        }
    });

    initEditCategoryForm();
});