<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Category\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ApiController;
use Arikaim\Core\Arikaim;

/**
 * Category control panel controler
*/
class CategoryControlPanel extends ApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('category::admin.messages');
    }

    /**
     * Add new category
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function addController($request, $response, $data) 
    {       
        $this->requireControlPanelPermission();
        
        $this->onDataValid(function($data) {
            $category = Model::Category('category');
            $data['parent_id'] = $data->get('parent_id',null);                                             
            $model = $category->create($data->toArray());

            if (is_object($model) == true) {                      
                $result = $model->saveTranslation($data->slice(['title','description']),$data['language']);                              
            } else {
                $result = false;
            }
            $this->setResponse($result,function() use($model,$data) {                                                       
                Arikaim::event()->trigger('category.create',$data->toArray());            
                $this
                    ->message('add')
                    ->field('id',$model->id)
                    ->field('uuid',$model->uuid);           
            },'errors.add');
        });
        $data
            ->addRule('exist:model:Category|field=id',"parent_id")
            ->addRule('text:min=2','title')
            ->addRule('text:min=2|max=2','language')
            ->validate();       
    }

    /**
     * Update category
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function updateController($request, $response, $data) 
    {    
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $model = Model::Category('category')->findByid($uuid); 
            // save parent id
            $data['parent_id'] = $data->get('parent_id',null);                  
            $result = $model->update($data->toArray());   
            $result = $model->saveTranslation($data->slice(['title','description']),$data['language']); 
         
            $this->setResponse($result,function() use($model) {
                Arikaim::event()->trigger('category.update',['uuid' => $model->uuid]);   
                $this
                    ->message('update')
                    ->field('uuid',$model->uuid);   
            },'errors.update');
        });
        $data      
            ->addRule('text:min=2','title')
            ->addRule('text:min=2|max=2','language')
            ->validate();    
    }

    /**
     * Delete category
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function deleteController($request, $response, $data)
    { 
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $result = Model::Category('category')->remove($uuid);

            $this->setResponse($result,function() use($uuid) {
                Arikaim::event()->trigger('category.delete',['uuid' => $uuid]); 
                $this
                    ->message('delete')
                    ->field('uuid',$uuid);  
            },'errors.delete');
        }); 
        $data->validate();
    }
    
    /**
     * Enable/Disable category
     *
     * @param object $request
     * @param object $response
     * @param object $data
     * @return object
    */
    public function setStatusController($request, $response, $data)
    {
        $this->requireControlPanelPermission();

        $this->onDataValid(function($data) {
            $status = $data->get('status',1);                
            $uuid = $data->get('uuid');
            $model = Model::Category('category')->findById($uuid);
            $result = $model->setStatus($status); 
            $model->setChildStatus($uuid,$status);

            $this->setResponse($result,function() use($uuid,$status,$data) {             
                Arikaim::event()->trigger('category.status',$data->toArray());  
                $this
                    ->message('status')
                    ->field('uuid',$uuid)
                    ->field('status',$status);
            },'errors.status');
        });
        $data
            ->addRule('status','checkList:items=0,1,toggle')
            ->validate(); 
    }
}
