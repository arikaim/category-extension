
'use strict';

function CategoryControlPanel() {
    
    this.update = function(formId,onSuccess,onError) {
        arikaim.put('/api/admin/category/update',formId,onSuccess,onError);
    };

    this.delete = function(uuid, onSuccess ,onError) {
        return arikaim.delete('/api/admin/category/delete/' + uuid,onSuccess,onError);          
    };

    this.deleteImage = function(uuid,fileName,onSuccess,onError) {
        var data = { 
            uuid: uuid, 
            file_name: fileName 
        };

        return arikaim.put('/api/admin/category/delete/image',data,onSuccess,onError);          
    };

    this.setStatus = function(uuid, status ,onSuccess, onError) {           
        var data = { 
            uuid: uuid, 
            status: status 
        };

        return arikaim.put('/api/admin/category/status',data,onSuccess,onError);      
    };   
}

var category = new CategoryControlPanel();

arikaim.component.onLoaded(function() {
    arikaim.ui.tab();
});