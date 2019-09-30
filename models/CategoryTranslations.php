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
use Arikaim\Core\Traits\Db\Slug;

class CategoryTranslations extends Model  
{
    use 
        Uuid,
        Slug,
        Find;
       
    protected $table = "category_translations";

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'language'
    ];
   
    public $timestamps = false;

    public function category()
    {
        return $this->hasOne(Category::class,'id'); 
    }
}
