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
use Arikaim\Core\Controllers\ApiController;

/**
 * Category api controler
*/
class CategoryApi extends ApiController
{
    /**
     * Read category
     *
     * @Api(
     *      description="Category details",    
     *      parameters={
     *          @ApiParameter (name="id",type="string,int",required=true,description="Category id or Uuid"),
     *          @ApiParameter (name="language",type="string",description="Language"),        
     *      }
     * )
     * 
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function read($request, $response, $data)
    {
        $data->validate(true);

        $language = $data->get('language',null);
        $language = $language ?? $this->getDefaultLanguage();
        $id = $data->get('id');
        
        $category = Model::Category('category')->findById($id);
        if ($category == null) {
            $this->error('Not valid category id');
            return false;
        }

        $this
            ->setResult($category->toArray(),true);
    }

    /**
     * Read category list
     *
     * @Api(
     *      description="Categories list",    
     *      parameters={          
     *          @ApiParameter (name="language",type="string",required=true,description="Language code"),        
     *      }
     * )
     * 
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function readList($request, $response, $data)
    { 
        $data
            ->validate(true);

        $language = $data->get('language',null);
        $language = $language ?? $this->getDefaultLanguage();
    
        $category = Model::Category('category');
        $list = $category->get()->toArray();
        
        $this->setResult($list,true);
    }
}
