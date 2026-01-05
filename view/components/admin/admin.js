
'use strict';

function CategoryControlPanel() {
    
    this.update = function(formId,onSuccess,onError) {
        arikaim.put('/api/admin/category/update',formId,onSuccess,onError);
    };

    this.delete = function(uuid, onSuccess ,onError) {
        return arikaim.delete('/api/admin/category/delete/' + uuid,onSuccess,onError);          
    };

    this.deleteImage = function(uuid,fileName,onSuccess,onError) {
        return arikaim.put('/api/admin/category/delete/image',{ 
            uuid: uuid, 
            file_name: fileName 
        },onSuccess,onError);          
    };

    this.setStatus = function(uuid, status ,onSuccess, onError) {           
        return arikaim.put('/api/admin/category/status',{ 
            uuid: uuid, 
            status: status 
        },onSuccess,onError);      
    };   
}

var category = new CategoryControlPanel();
