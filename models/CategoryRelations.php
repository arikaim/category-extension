<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) 2016-2018 Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license.html
 * 
*/
namespace Arikaim\Extensions\Category\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Extensions\Category\Models\Category;

use Arikaim\Core\Traits\Db\Uuid;
use Arikaim\Core\Traits\Db\Find;
use Arikaim\Core\Traits\Db\PolymorphicRelations;

class CategoryRelations extends Model  
{
    use 
        Uuid,
        PolymorphicRelations,
        Find;
       
    protected $table = "category_relations";

    protected $fillable = [
        'category_id',
        'relation_id',
        'relation_type'       
    ];
   
    public $timestamps = false;

    protected $relation_model_class = Category::class;

    protected $relation_attribute_name = 'category_id';
}
