'use strict';

arikaim.component.onLoaded(function() {
    $('#branch_dropdown').dropdown({
        onChange: function(branch, text, choice) { 
            if (text == 'All') {
                text = '';
            }
            $('#branch').val(text);
        }
    });

    $('#category_dropdown').dropdown({
        allowCategorySelection: true,
        
        onChange: function(value, text, choice) { 
            var title = $(choice).attr('title');
            $(this).children('.text').html(title);
        }
    });
    
    arikaim.ui.form.addRules("#category_form",{
        inline: false,
        fields: {
            title: {
            identifier: "title",      
                rules: [{
                    type: "minLength[2]"       
                }]
            }
        }
    });   
});