'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.button('.delete-image',function(element) {
        var uuid = $(element).attr('uuid');
        var fileName = $(element).attr('file-name');

        category.deleteImage(uuid,fileName,function(result) {
            return arikaim.page.loadContent({
                id: 'thumbnail',
                params: { 
                    file_name: null,
                    url: uuid
                },
                component: 'category::admin.edit.images.thumbnail'
            });
        });
    });
});