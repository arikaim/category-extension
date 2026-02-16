/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
*/
'use strict';

class CategoryView  extends View {
    self = this;

    init() {
        self = this;

        this.loadMessages('category::admin');
        arikaim.ui.loadComponentButton('.create-category');

        arikaim.events.on('category.create',function(uuid) {          
            self.loadList();
        });

        arikaim.events.on('category.update',function(uuid) {          
            self.loadList();
        });
        
        HSAccordion.autoInit();

        /*
        $('#branch_dropdown').dropdown({
            onChange: function(branch, text, choice) {               
                self.loadList(branch);
            }
        });
        */        
    };

    loadItemsList(element, parentId, uuid, branch, onSuccess) { 
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


    loadList(branch) {
        this.loadItemsList('category_rows',null,null,branch,function(result) {                   
            self.initRows();  
            /*
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
            */

        });
    };

    initRows() {   
        HSAccordion.autoInit();
        arikaim.ui.loadComponentButton('.item-action');

        arikaim.ui.button('.item-toggle',function(btn) {
            console.log('load items');

            var hasChild = $(btn).attr('has-child');
            var uuid = $(btn).attr('uuid');

            console.log(hasChild);
            console.log(uuid);

            if (hasChild == true) {
                var parentId = $(btn).attr('parent-id');
                var branch = $('#category_rows').attr('branch');
               // var elementId = $(btn).attr('id');
             
                self.loadItemsList(uuid + '_child_content',parentId,uuid,branch,function(result) {                   
                    self.initRows();                    
                });
            }

        });
     
        arikaim.ui.button('.delete-item',function(element) {
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
                    arikaim.page.toastMessage(result.message);
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

      //  this.initAccordion('#category_tree');
    };

    loadItems(branch) {
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

    initAccordion(selector) {  
        selector = getDefaultValue(selector,'.accordion');            
        
        var el = document.querySelector(selector);

      //  var tr = new HSTreeView(el);

      //  console.log(tr);

         console.log(HSTreeView);
        console.log(selector);
           console.log(el);
        //const tree = new HSTreeView(document.querySelector(selector));

        var tree = HSTreeView.getInstance(selector,true);
        console.log(tree);

        /*
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
        */      
    };
}

var categoryView = new CategoryView();

arikaim.component.onLoaded(function() {
    categoryView.init();      


});