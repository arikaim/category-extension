<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category;

use Arikaim\Core\Extension\Extension;

/**
 * Category extension
*/
class Category extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return void
    */
    public function install()
    {        
        // Control Panel
        $this->addApiRoute('POST','/api/admin/category/add','CategoryControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/admin/category/update','CategoryControlPanel','update','session'); 
        $this->addApiRoute('POST','/api/admin/category/upload/image','CategoryControlPanel','uploadImage','session'); 
        $this->addApiRoute('PUT','/api/admin/category/delete/image','CategoryControlPanel','deleteImage','session'); 
        $this->addApiRoute('PUT','/api/admin/category/update/meta','CategoryControlPanel','updateMetaTags','session');       
        $this->addApiRoute('PUT','/api/admin/category/update/description','CategoryControlPanel','updateDescription','session');       
        $this->addApiRoute('DELETE','/api/admin/category/delete/{uuid}','CategoryControlPanel','delete','session');     
        $this->addApiRoute('PUT','/api/admin/category/status','CategoryControlPanel','setStatus','session'); 
        $this->addApiRoute('POST','/api/admin/category/save/translation','CategoryControlPanel','saveTranslation','session'); 
        // translations
        $this->addApiRoute('PUT','/api/admin/category/translate/categories','CategoryControlPanel','translateCategories','session'); 
        // Api Routes
        $this->addApiRoute('GET','/api/category/{id}[/{language}]','CategoryApi','read');  
        $this->addApiRoute('GET','/api/category/list/{language}','CategoryApi','readList');     
        
        // Register events
        $this->registerEvent('category.create','Trigger after new category created');
        $this->registerEvent('category.update','Trigger after category is edited');
        $this->registerEvent('category.delete','Trigger after category is deleted');
        $this->registerEvent('category.status','Trigger after category status changed');
        // Create db tables
        $this->createDbTable('CategorySchema');
        $this->createDbTable('CategoryTranslationsSchema');
        $this->createDbTable('CategoryRelationsSchema');
        // console
        $this->registerConsoleCommand('CategoryDelete');
        $this->registerConsoleCommand('TranslateCategories');
    } 
    
    /**
     * UnInstall extension
     *
     * @return void
     */
    public function unInstall()
    {  
    }
}
