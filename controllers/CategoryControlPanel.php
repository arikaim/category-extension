<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Utils\Factory;
use Arikaim\Core\Controllers\ControlPanelApiController;
use Arikaim\Core\Controllers\Traits\FileUpload;
use Arikaim\Extensions\Category\Classes\Category;

/**
 * Category control panel controler
*/
class CategoryControlPanel extends ControlPanelApiController
{
    use FileUpload;

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
     * Translate categories
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function translateCategoriesController($request, $response, $data)
    {
        $transalte = Factory::createController($this->getContainer(),'TranslationsControlPanel','translations');

        if (\is_object($transalte) == false) {
            $this->error('errors.translations');
            return;
        }
        $this->onDataValid(function($data) use($transalte) {
            $category = Model::Category('category');
            $branch = $data->get('branch',null);
            $language = $data->get('language');

            $category = (empty($branch) == false) ? $category->where('branch','=',$branch) :  $category;
            $categories = $category->orderBy('id')->get();
            $translated = 0;
            foreach($categories as $item) {
                $result = Category::translate($item,$language);
                $translated += ($result === false) ? 0 : 1;
            }    
           
            $this->setResponse(true,function() use($translated) {    
                $this
                    ->message('translations')
                    ->field('translations',$translated);
            },'errors.translations');
        });
        $data->validate();
    }

    /**
     * Add new category
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) {
            $category = Model::Category('category');   
            $title = $data->get('title',null);       
            $data['parent_id'] = (empty($data['parent_id']) == true) ? null : $data['parent_id']; 

            if ($category->hasCategory($title,$data['parent_id']) == true) {
                $this->error('errors.exist');
                return false;
            } 
          
            $model = $category->create($data->toArray());

            if (\is_object($model) == true) {                      
                $result = $model->saveTranslation($data->slice(['title','description']),$data['language']);                              
            } else {
                $result = false;
            }
            $this->setResponse((bool)$result,function() use($model,$data) {                                                       
                $this->get('event')->dispatch('category.create',$data->toArray());            
                $this
                    ->message('add')
                    ->field('id',$model->id)
                    ->field('language',$data['language'])
                    ->field('uuid',$model->uuid);           
            },'errors.add');
        });
        $data
            ->addRule('exist:model:Category|field=id','parent_id')
            ->addRule('text:min=2','title')
            ->addRule('text:min=2|max=2','language')
            ->validate();       
    }

    /**
     * Delete category image
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteImageController($request, $response, $data) 
    {
        $this->onDataValid(function($data) {   
            $model = Model::Category('category')->findByid($data['uuid']);  
            $result = $model->update([
                'thumbnail' => null
            ]);
            
            if ($model->isImagUsed($data['file_name']) == false) {
                // delete image file
                $fileName = $model->getImagesPath(true) . $data['file_name'];
                if ($this->get('storage')->has($fileName) == true) {
                    $this->get('storage')->delete($fileName);
                }
            };

            $this->setResponse($result,function() use($model) {              
                $this
                    ->message('delete-image')                                 
                    ->field('uuid',$model->uuid);   
            },'errors.delete-image');
        });  
        $data->validate();      
    }

    /**
     * Upload category image
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function uploadImageController($request, $response, $data) 
    {
        $this->onDataValid(function($data) use($request) {         
            $model = Model::Category('category')->findByid($data['uuid']);                
           
            if ($model->createImagesPath() === false) {
                $this->error('errors.path');
                return;
            }
            $destinationPath = $model->getImagesPath(true);
            $files = $this->uploadFiles($request,$destinationPath);

            // process uploaded image
            $result = false;
            foreach ($files as $item) {               
                if (empty($item['error']) == false) {
                    continue;
                }
                // set thumbnail image
                $result = $model->update([
                    'thumbnail' => $item['name']
                ]); 
            }

            $this->setResponse($result,function() use($model) {              
                $this
                    ->message('upload')
                    ->field('thumbnail',$model->thumbnail)
                    ->field('url',$model->getImageUrl())
                    ->field('uuid',$model->uuid);   
            },'errors.upload');
        });
        $data->validate(); 
    }

    /**
     * Save category dscription
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateDescriptionController($request, $response, $data) 
    {
        $this->onDataValid(function($data) {         
            $model = Model::Category('category')->findByid($data['uuid']);   
        
            $result = $model->saveTranslation($data->slice(['description']),$data['language']); 
                    
            $this->setResponse((bool)$result,function() use($model) {
                $this->get('event')->dispatch('category.update',['uuid' => $model->uuid]);   
                $this
                    ->message('update')
                    ->field('uuid',$model->uuid);   
            },'errors.update');
        });
        $data->validate(); 
    }

    /**
     * Update category meta tags
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateMetaTagsController($request, $response, $data) 
    {
        $this->onDataValid(function($data) {         
            $model = Model::Category('category')->findByid($data['uuid']);      
            $info = $data->slice(['meta_title','meta_description','meta_keywords']);
            $result = $model->saveTranslation($info,$data['language']); 
                    
            $this->setResponse((bool)$result,function() use($model) {
                $this->get('event')->dispatch('category.update',['uuid' => $model->uuid]);   
                $this
                    ->message('update')
                    ->field('uuid',$model->uuid);   
            },'errors.update');
        });
        $data                
            ->addRule('text:min=2|max=2','language')
            ->validate(); 
    }

    /**
     * Update category
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {    
        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $model = Model::Category('category')->findByid($uuid); 
            // save parent id           
            $parentId = (empty($data['parent_id']) == true) ? null : $data['parent_id'];    
        
            $translations = Model::CategoryTranslations('category');
            $categoryTranslation = $model->translation($data['language']);
            // check if slug exist
            $translation = $translations->findBySlug($data['title']);
            if (\is_object($translation) == true && \is_object($categoryTranslation) == true) {                              
                if ($translation->uuid != $categoryTranslation->uuid) {
                    $this->error('errors.translations.slug');
                    return false;
                }                                   
            }
            
            $result = $model->update([
                'parent_id' => $parentId,
                'branch'    => $data['branch']
            ]);     
            
            $result = $model->saveTranslation($data->slice(['title','description']),$data['language']); 
         
            $this->setResponse((bool)$result,function() use($model) {
                $this->get('event')->dispatch('category.update',['uuid' => $model->uuid]);   
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteController($request, $response, $data)
    { 
        $this->onDataValid(function($data) {
            $uuid = $data->get('uuid');
            $result = Model::Category('category')->remove($uuid);

            $this->setResponse($result,function() use($uuid) {
                $this->get('event')->dispatch('category.delete',['uuid' => $uuid]); 
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function setStatusController($request, $response, $data)
    {
        $this->onDataValid(function($data) {
            $status = $data->get('status',1);                
            $uuid = $data->get('uuid');
            $model = Model::Category('category')->findById($uuid);
            $result = $model->setStatus($status); 
            $model->setChildStatus($uuid,$status);

            $this->setResponse($result,function() use($uuid,$status,$data) {             
                $this->get('event')->dispatch('category.status',$data->toArray());  
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
