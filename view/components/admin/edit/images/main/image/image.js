'use strict';

arikaim.component.onLoaded(function() { 
    arikaim.ui.button('.delete-image-button',function(element) {
        var uuid = $(element).attr('uuid');
        products.update({
            image_id: null,
            uuid: uuid
        },function(result) {
            return arikaim.page.loadContent({
                id: 'main_image_content',           
                component: 'category::admin.edit.images.main.image',
                params: { 
                    uuid: uuid                   
                }
            });  
        });
    });
});