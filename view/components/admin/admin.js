/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function CategoryControlPanel() {
    var self = this;

    this.delete = function(uuid, onSuccess ,onError) {
        return arikaim.delete('/api/category/admin/delete/' + uuid,onSuccess,onError);          
    };

    this.deleteImage = function(uuid, fileName, onSuccess,onError) {
        var data = { 
            uuid: uuid, 
            file_name: fileName 
        };

        return arikaim.put('/api/category/admin/delete/image',data,onSuccess,onError);          
    };

    this.setStatus = function(uuid, status ,onSuccess, onError) {           
        var data = { 
            uuid: uuid, 
            status: status 
        };

        return arikaim.put('/api/category/admin/status',data,onSuccess,onError);      
    };

    this.loadList = function(element, parentId, uuid, language, branch, onSuccess) { 
        return arikaim.page.loadContent({
            id : element,
            component : 'category::admin.view.items',
            params: { 
                parent_id: parentId,
                language: language,
                uuid: uuid,
                branch: branch 
            }
        },onSuccess);
    };

    this.loadAddCategory = function(parentId, language, branch) {
        arikaim.ui.setActiveTab('#add_category','.category-tab-item');

        arikaim.page.loadContent({
            id: 'category_content',
            component: 'category::admin.add',
            params: { 
                parent_id: parentId,
                branch: branch,
                language: language 
            }
        });          
    };

    this.loadCategoryRelations = function(uuid, language) {
        arikaim.ui.setActiveTab('#relations','.category-tab-item');
             
        arikaim.page.loadContent({
            id: 'category_content',
            component: 'category::admin.relations',
            params: { 
                uuid: uuid,
                language: language 
            }
        });  
    };

    this.loadCategoryTranslations = function(uuid) {
        arikaim.ui.setActiveTab('#translations','.category-tab-item');
             
        arikaim.page.loadContent({
            id: 'category_content',
            component: 'category::admin.translations.view',
            params: { 
                uuid: uuid              
            }
        });  
    };


    this.loadEditCategory = function(uuid, language) {
        arikaim.ui.setActiveTab('#edit_category','.category-tab-item')      
        arikaim.page.loadContent({
            id: 'category_content',
            component: 'category::admin.edit',
            params: { 
                uuid: uuid,
                language: language 
            }
        });  
    };

    this.initCategoryDropDown = function() {
        $('#category_dropdown').dropdown({
            allowCategorySelection: true,
            
            onChange: function(value, text, choice) { 
                var title = $(choice).attr('title');
                $(this).children('.text').html(title);
            }
        });
    };

    this.translateCategories = function(language, branch, onSuccess, onError) {      
        var data = {
            language: language,
            branch: branch
        };

        return arikaim.put('/api/category/admin/translate/categories',data,onSuccess,onError);      
    };

    this.init = function() {    
        arikaim.ui.tab();
    };
}

var category = new CategoryControlPanel();

arikaim.component.onLoaded(function() {
    category.init();
});