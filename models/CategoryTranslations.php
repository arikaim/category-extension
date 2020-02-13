<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Extensions\Category\Models\Category;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Slug;

class CategoryTranslations extends Model  
{
    use 
        Uuid,
        Slug,
        Find;
       
    /**
     * Db table name
     *
     * @var string
     */
    protected $table = "category_translations";

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'language'
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Category relation
     *
     * @return mixed
     */
    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id'); 
    }
}
