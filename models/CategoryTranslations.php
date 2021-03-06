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
use Arikaim\Core\Db\Traits\MetaTags;

class CategoryTranslations extends Model  
{
    use 
        Uuid,
        Slug,
        MetaTags,
        Find;
       
    /**
     * Db table name
     *
     * @var string
     */
    protected $table = 'category_translations';

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
        'meta_title',
        'meta_description',
        'meta_keywords',
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

    /**
     * Find Category
     *
     * @param string $slug
     * @param string $language
     * @return Model|null
     */
    public function findCategory(string $slug, string $language)
    {
        return $this->where('slug','=',$slug)->where('language','=',$language)->first();
    }
}
