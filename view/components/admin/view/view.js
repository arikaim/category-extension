/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
*/
'use strict';

function CategoryView() {
    var self = this;

    this.init = function() {
        this.loadMessages('category::admin');
        arikaim.ui.loadComponentButton('.create-category');

        arikaim.events.on('category.create',function(uuid) {
        });

        paginator.init('category_rows',{
            name: 'category::admin.view.items',
            params: {
                namespace: 'category'
            }
        });      

        $('#branch_dropdown').dropdown({
            onChange: function(branch, text, choice) {               
                self.loadList(branch);
            }
        });
    };

    this.loadItemsList = function(element, parentId, uuid, branch, onSuccess) { 
        return arikaim.page.loadContent({
            id : element,
            component : 'category::admin.view.items',
            params: { 
                parent_id: parentId,           
                uuid: uuid,
                branch: branch 
            }
        },onSuccess);
    };


    this.loadList = function(branch) {
        self.loadItemsList('category_rows',null,null,branch,function(result) {                   
            self.initRows();  
            paginator.clear('category',function() {
                paginator.init('category_rows',{
                    name: 'category::admin.view.items',
                    params: {
                        namespace: 'category',
                        branch: branch
                    }
                });     
                paginator.reload();     
            });   
        });
    };

    this.initRows = function() {       
        arikaim.ui.loadComponentButton('.category-action');
        
        $('.actions-dropdown').dropdown();
        
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });
            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                category.delete(uuid,function(result) {
                    $('#' + uuid).remove();
                    $('.class-' + uuid).remove();                   
                },function(errors) {                   
                    arikaim.page.toastMessage({
                        class: 'error',
                        message: errors[0]
                    });
                });
            });
        });
      
        arikaim.ui.button('.disable-button',function(element) {
            var uuid = $(element).attr('uuid');
            var branch = $(element).attr('branch');

            category.setStatus(uuid,0,function(result) {
                $('#item_' + uuid).html('');
                self.loadItems(branch);               
            });
        });   
        
        arikaim.ui.button('.enable-button',function(element) {
            var uuid = $(element).attr('uuid');
            var branch = $(element).attr('branch');

            category.setStatus(uuid,1,function(result) {
                $('#item_' + uuid).html('');
                self.loadItems(branch);               
            });
        }); 
        
        arikaim.ui.button('.relations-button',function(element) {
            var uuid = $(element).attr('uuid');
          
            category.loadCategoryRelations(uuid);     
        });

        this.initAccordion();
    };

    this.loadItems = function(branch) {
        arikaim.page.loadContent({
            id : 'category_rows',
            component : 'category::admin.view.items',
            params: { 
                parent_id: null,       
                branch: branch 
            }
        },function(result) {
            self.initRows();
        });
    };

    this.initAccordion = function(selector) {  
        selector = getDefaultValue(selector,'.ui.accordion');             
        $(selector).accordion({
            selector: {
                trigger: '.title .dropdown'
            },
            onOpening: function() {
                $(this).html('');
            },
            onOpen: function() {
                var hasChild = $(this).attr('has-child');
                if (hasChild == true) {
                    var parentId = $(this).attr('parent-id');
                    var branch = $('#category_rows').attr('branch');
                    var elementId = $(this).attr('id');
                    var uuid = $(this).attr('uuid');
                    self.loadItemsList(elementId,parentId,uuid,branch,function(result) {                   
                        self.initRows();                    
                    });
                }
            }
        });        
    };
}

var categoryView = new createObject(CategoryView,ControlPanelView);

arikaim.component.onLoaded(function() {
    categoryView.init();   
    categoryView.initRows();
});