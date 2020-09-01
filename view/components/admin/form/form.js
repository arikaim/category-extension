"use strict";

$(document).ready(function() {   
    $('#branch_dropdown').dropdown({
        onChange: function(branch, text, choice) { 
            $('#branch').val(text);
        }
    });

    category.initCategoryDropDown();
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