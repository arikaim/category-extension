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

        $('#branch_dropdown').on('change', function() {
            var selected = $(this).val();            
            self.loadList(selected);          
        });    
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
        });
    };

    initRows() {   
        HSAccordion.autoInit();
        arikaim.ui.loadComponentButton('.item-action');

        arikaim.ui.button('.item-toggle',function(btn) {
            var hasChild = $(btn).attr('has-child');
            var uuid = $(btn).attr('uuid');

            if (hasChild == true) {
                var parentId = $(btn).attr('parent-id');
                var branch = $('#category_rows').attr('branch');
             
                self.loadItemsList(uuid + '_child_content',parentId,uuid,branch,function(result) {                   
                    self.initRows();                    
                });
            }

        });
     
        arikaim.ui.button('.delete-item',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });
            
            arikaim.ui.getComponent('confirm_delete').open(
            function() {
                category.delete(uuid,function(result) {
                    $('#' + uuid).remove(); 
                    arikaim.ui.getComponent('toast').show(result.message);                   
                },function(errors) {          
                    arikaim.ui.getComponent('toast').show(errors[0]);                              
                });
            },message);
        });
      
        arikaim.ui.button('.status-button',function(element) {
            var uuid = $(element).attr('uuid');
            var status = $(element).attr('status');
            var branch = $(element).attr('branch');

            category.setStatus(uuid,status,function(result) {
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