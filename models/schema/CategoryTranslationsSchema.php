<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Models\Schema;

use Arikaim\Core\Db\Schema;
use Arikaim\Core\Db\Model;

/**
 * Category translations table
 */
class CategoryTranslationsSchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = "category_translations";

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {
        $table->tableTranslations('category_id','category',function($table) {
            $table->slug(true,true);
            $table->string('title')->nullable(false);
            $table->text('description')->nullable(true);
            $table->string('meta_title')->nullable(true);
            $table->text('meta_description')->nullable(true);
            $table->text('meta_keywords')->nullable(true);            
        });       
    }

    /**
     * Update table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function update($table) 
    {  
        if ($this->hasColumn('slug') == true) {          
            $table->string('slug')->nullable(true)->change();           
        }
        if ($this->hasColumn('meta_title') == false) {
            $table->string('meta_title')->nullable(true); 
        } 
        if ($this->hasColumn('meta_description') == false) {
            $table->text('meta_description')->nullable(true); 
        }
        if ($this->hasColumn('meta_keywords') == false) {
            $table->text('meta_keywords')->nullable(true); 
        }           
    }

    /**
     * Insert or update rows in table
     *
     * @param Seed $seed
     * @return void
     */
    public function seeds($seed)
    { 
        // create blog categories
        Model::Category('category',function($model) {
            $items = ['News','Travel','Lifestyle'];                
            return $model->createFromArray($items,null,'en','blog');           
        });
    }
}
