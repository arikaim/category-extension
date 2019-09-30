<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Category\Models\Schema;

use Arikaim\Core\Db\Schema;

/**
 * Category db table
 */
class CategorySchema extends Schema  
{    
    /**
     * Table name
     *
     * @var string
     */
    protected $table_name = "category";

   /**
     * Create table
     *
     * @param \Arikaim\Core\Db\TableBlueprint $table
     * @return void
     */
    public function create($table) 
    {            
        // columns    
        $table->id();
        $table->bigInteger('parent_id')->unsigned()->nullable(true);  
        $table->prototype('uuid');       
        $table->status();
        $table->position();
        $table->userId();
        // foreign keys
        $table->foreign('parent_id')->references('id')->on('category')->onDelete('cascade');  
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
