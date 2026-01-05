'use strict';

arikaim.component.onLoaded(function() {
  
    $('#branch_dropdown').on('change', function() {
        var val = $(this).val();

        $('#branch').val(val);
    });

    /*
    $('#category_dropdown').dropdown({
        allowCategorySelection: true,
        
        onChange: function(value, text, choice) { 
            var title = $(choice).attr('title');
            $(this).children('.text').html(title);
        }
    });
    */


    arikaim.ui.form.addRules("#category_form",{});   
});