<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Category;

use Arikaim\Core\Packages\Extension\Extension;

/**
 * Category extension
*/
class Category extends Extension
{
    /**
     * Install extension routes, events, jobs ..
     *
     * @return boolean
    */
    public function install()
    {
        // Api Routes
        $this->addApiRoute('GET','/api/category/{id}','Category','read');  
        $this->addApiRoute('GET','/api/category/list/[{parent_id}]','Category','readList');   
        // Control Panel
        $this->addApiRoute('POST','/api/category/admin/add','CategoryControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/category/admin/update','CategoryControlPanel','update','session');       
        $this->addApiRoute('DELETE','/api/category/admin/delete/{uuid}','CategoryControlPanel','delete','session');     
        $this->addApiRoute('PUT','/api/category/admin/status','CategoryControlPanel','setStatus','session'); 
            
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
        
        return true;
    }   
}
