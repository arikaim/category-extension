/**
 *  Arikaim
 *  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Category
 *  Component: category:admin.categories.form
 */

arikaim.page.onReady(function() {   
    
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