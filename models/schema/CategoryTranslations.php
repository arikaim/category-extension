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

/**
 * Category translations table
 */
class CategoryTranslations extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'category_translations';

    /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {
        $table->tableTranslations('category_id','category',function($table) {
            $table->slug(false,true);
            $table->string('title')->nullable(false);
            $table->text('description')->nullable(true);
            $table->string('meta_title')->nullable(true);
            $table->text('meta_description')->nullable(true);
            $table->text('meta_keywords')->nullable(true);   
            // index
            $table->unique(['slug','language']);        
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
       
    }
}
