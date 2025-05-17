'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.onSubmit("#category_form",function() {  
        return arikaim.post('/api/admin/category/add','#category_form');
    },function(result) {
        arikaim.ui.form.clear('#category_form');
        arikaim.ui.form.showMessage(result.message);

        arikaim.events.emit('category.create',result.uuid);

        arikaim.page.loadContent({
            id: 'category_details',
            component: 'category::admin.edit',
            params: { 
                uuid: result.uuid
            }
        });  
    });
});
