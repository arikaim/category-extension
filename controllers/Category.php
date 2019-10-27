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
use Arikaim\Core\View\Template;

/**
 * Category api controler
*/
class Category extends ApiController
{
    /**
     * Read category
     *
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function readController($request, $response, $data)
    {
        $this->onDataValid(function($data) {
            $language = $data->get('language',Template::getLanguage());
            $id = $data->get('id');
            $category = Model::Category('category')->findById($id);
            $translation = $category->translation($language);
            $data = array_merge($translation->toArray(),$category->toArray());
        });

        $data->validate();
    }

    /**
     * Read category list
     *
     * @param object $request
     * @param object $response
     * @param Validator $data
     * @return object
    */
    public function readListController($request, $response, $data)
    { 
        $this->onDataValid(function($data) {
            $parentId = $data->get('parent_id',null);
            $category = Model::Category('category')->getList($parentId);
            $this->setResult($category,true);
        });
               
        $data->validate();
    }
}
