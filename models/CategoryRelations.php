<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Extensions\Category\Models\Category;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\PolymorphicRelations;

/**
 * CategoryRelations class
 */
class CategoryRelations extends Model  
{
    use 
        Uuid,
        PolymorphicRelations,
        Find;
       
    /**
     * Table name
     *
     * @var string
     */
    protected $table = "category_relations";

    protected $fillable = [
        'category_id',
        'relation_id',
        'relation_type'       
    ];
   
    public $timestamps = false;

    /**
     * Relation model class
     *
     * @var string
     */
    protected $relationModelClass = Category::class;

    /**
     * Reation column name
     *
     * @var string
     */
    protected $relationColumnName = 'category_id';
}
