'use strict';

arikaim.component.onLoaded(function() {
    var fileUpload = new FileUpload('#category_images_form',{
        url: '/api/admin/category/upload/image',
        maxFiles: 1,
        allowMultiple: false,
        acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
        formFields: {            
            uuid: '#uuid'
        },
        onSuccess: function(result) {
            return arikaim.page.loadContent({
                id: 'thumbnail',
                params: { 
                    file_name: result.thumbnail,
                    url: result.url,
                    uuid: result.uuid
                },
                component: 'category::admin.edit.images.thumbnail'
            });
        }
    });
});