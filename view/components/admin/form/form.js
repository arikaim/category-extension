'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.addRules("#category_form",{});   
    
    $('#branch_select').on('change', function() {
        $('#branch').val($(this).val());
    });   
});