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

use Arikaim\Core\Db\Model as DbModel;
use Arikaim\Extensions\Category\Models\CategoryTranslations;
use Arikaim\Core\Utils\File;
use Arikaim\Core\Utils\Path;
use Arikaim\Core\Utils\Utils;
use Arikaim\Core\View\Html\Page;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\ToggleValue;
use Arikaim\Core\Db\Traits\Position;
use Arikaim\Core\Db\Traits\Tree;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\UserRelation;
use Arikaim\Core\Db\Traits\Translations;

/**
 * Category class
 */
class Category extends Model  
{
    use Uuid,
        ToggleValue,        
        Position,
        Find,
        Status,
        UserRelation,
        Translations,
        Tree;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'category';

    /**
     * Translation column ref
     *
     * @var string
     */
    protected $translationReference = 'category_id';

    /**
     * Translatin model class
     *
     * @var string
     */
    protected $translationModelClass = CategoryTranslations::class;

    /**
     * Translated attributes
     *
     * @var array
     */
    protected $translatedAttributes = [                  
    ];

    /**
     * Append attributes to serialization
     *
     * @var array
     */
    protected $appends = [        
        'title',
        'description',
        'slug'
    ];

    /**
     * With relations
     *
     * @var array
     */
    protected $with = [
        'translations',
        'user'
    ];
    
    /**
     * Visible columns
     *
     * @var array
     */
    protected $visible = [
        'id',
        'position',       
        'status',
        'parent_id',
        'branch',
        'user',
        'uuid',               
        'thumbnail'
    ];

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'position',       
        'status',
        'parent_id',
        'branch',
        'user_id',
        'thumbnail'
    ];
   
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * title attribute
     *
     * @return string|null
     */
    public function getTitleAttribute()
    {
        $model = $this->translation();

        return ($model !== false) ? $model->title : null;
    }

    /**
     * description attribute
     *
     * @return string|null
     */
    public function getDescriptionAttribute()
    {
        $model = $this->translation();

        return ($model !== false) ? $model->description : null;
    }

    /**
     * slug attribute
     *
     * @return string|null
     */
    public function getSlugAttribute()
    {
        $model = $this->translation();

        return ($model !== false) ? $model->slug : null;
    }

    /**
     * Parent category relation
     *
     * @return Relation|null
     */
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }
    
    /**
     * Set child category status
     *
     * @param integer|string $id
     * @param integer $status
     * @return bool
     */
    public function setChildStatus($id, $status): bool
    {
        $model = $this->findById($id);
        if ($model == false) {
            return false;
        }
        $model = $model->where('parent_id','=',$model->id)->get();
        if (\is_object($model) == false) {
            return false;
        }

        foreach ($model as $item) {   
            $item->setStatus($status);        
            $this->setChildStatus($item->id,$status);
        }   

        return true;
    }

    /**
     * Delete category 
     *
     * @param integer|string $id
     * @param boolean $removeChild
     * @return bool
     */
    public function remove($id, bool $removeChild = true): bool
    {
        if ($removeChild == true) {
            $this->removeChild($id);
        }
        $model = $this->findById($id);
        if (\is_object($model) == false) {
            return false;
        }
        $relations = DbModel::CategoryRelations('category');
        $relations->deleteRelations($model->id);
        $model->removeTranslations();

        return (bool)$model->delete();      
    }

    /**
     * Remove child category
     *
     * @param integer|string $id
     * @return boolean
     */
    public function removeChild($id): bool
    {
        $model = $this->findById($id);
        if ($model == false) {
            return false;
        }
        $model = $model->where('parent_id','=',$model->id)->get();
        if (\is_object($model) == false) {
            return false;
        }
        foreach ($model as $item) {
            $item->remove($item->id);          
        }
      
        return true;
    }

    /**
     * Get full cateogry title
     *
     * @param integer|string $id
     * @param string|null $language
     * @param array $items
     * @return array|null
     */
    public function getTitle($id = null, ?string $language = null, $items = []): ?array
    {       
        $model = (empty($id) == true) ? $this : $this->findById($id);
        $language = $language ?? 'en';
        if (\is_object($model) == false) {
            return null;
        }

        $result = $items;
        if (empty($model->parent_id) == false) {
           $result = $model->getTitle($model->parent_id,$language,$result);        
        }     
        $title = $model->getTranslationTitle($language);
        $title = (empty($title) == true) ? $model->getTranslationTitle('en') : $title;
        $result[] = $title;

        return $result;
    }

    /**
     * Get cateogry slug
     *
     * @param string|null $language
     * @return string|null
     */
    public function getSlug(?string $language = null): ?string
    {
        $language = $language ?? 'en';
        $translation = $this->translation($language);
        $translation = (\is_object($translation) == false) ? $this->translation('en') : $translation;
        
        return $translation->slug;      
    }

    /**
     *  Get categories list
     *    
     * 
     * @param integer|null $parentId
     * @param string|null $branch
     * @return Model|null
     */
    public function getList(?int $parentId = null, ?string $branch = null)
    {   
        $model = (empty($branch) == false) ? $this->where('branch','=',$branch) : $this;       
        $model = $model->where('parent_id','=',$parentId)->get();

        return (\is_object($model) == true) ? $model : null;           
    }

    /**
     * Get translation title
     *
     * @param string|null $language
     * @param string|null $default
     * @return string|null
     */
    public function getTranslationTitle(?string $language = null, $default = null): ?string
    {
        $language = $language ?? 'en';
        $model = $this->translation($language);     

        return ($model == false) ? $default : $model->title ?? null;  
    }

    /**
     * Return true if category exist
     *
     * @param string $title
     * @param integer|null $parentId
     * @param string|null $branch
     * @return boolean
     */
    public function hasCategory(?string $title, ?int $parentId = null, ?string $branch = null): bool
    { 
        return \is_object($this->findCategory($title,$parentId,$branch));
    }

    /**
     * Get category id
     *
     * @param string $slug
     * @param string $language
     * @return integer|null
     */
    public function findCategoryId(string $slug, string $language = 'en'): ?int 
    {
        $model = $this->translation($language);     
        if (\is_object($model) == false) {
            return null;
        }

        $model = $model->where('slug','=',$slug)->first();
        return (\is_object($model) == false) ? null : $model->id;
    }

    /**
     * Find category
     *
     * @param string $title
     * @param integer|null $parentId
     * @param string|null $branch
     * @return Model|false
     */
    public function findCategory(?string $title, ?int $parentId = null, ?string $branch = null)
    {
        $model = (empty($branch) == false) ? $this->where('branch','=',$branch) : $this;     
        $model = (empty($parentId) == true) ? $model->whereNull('parent_id') : $model->where('parent_id','=',$parentId);
        $model = $model->get();

        foreach ($model as $item) {        
            $translation = $item->translations()->getQuery()->where('title','=',$title)->first();   
            if (\is_object($translation) == true) {
                return $item;
            }  
        }
        
        return false;
    }

    /**
     * Create categories from array
     *
     * @param array $items
     * @param integer|null $parentId
     * @param string|null $language
     * @param string|null $branch
     * @return array
     */
    public function createFromArray(array $items, ?int $parentId = null, ?string $language = null, ?string $branch = null): array
    {
        $language = $language ?? 'en';
        $result = [];
        foreach ($items as $key => $value) {         
            $title = \trim($value);
            if (empty($title) == true) continue;

            $slug = Utils::slug($title);
            $model = $this->findTranslation('slug',$slug,$language);
            if (\is_object($model) == false) {  
                                       
                $model = $this->create([
                    'parent_id' => $parentId,
                    'branch'    => $branch
                ]);
                $model->saveTranslation(['title' => $title], $language, null); 
                $result[] = $model->id;   
            } else {
                $result[] = $model->category_id;   
            }
        }      

        return $result;
    }

    /**
     * Build category relations query
     *
     * @param Model $filterModel
     * @param string $categorySlug
     * @return Model
     */
    public function relationsQuery($filterModel, $categorySlug)
    {
        if (empty($categorySlug) == false) {           
            $categoryTranslations = DbModel::create('CategoryTranslations','category',function($model) use($categorySlug) {                
                return $model->findBySlug($categorySlug);                  
            });
            $filterModel = $filterModel->whereHas('categories',function($query) use($categoryTranslations) {               
                $query->where('category_id','=',$categoryTranslations->category_id);
            });
                    
            return $filterModel;
        }

        return $filterModel;
    }

    /**
     * Get category images path
     *
     * @param boolean $relative
     * @return string
     */
    public function getImagesPath(bool $relative = false): string
    {
        $path = 'category' . DIRECTORY_SEPARATOR;

        return ($relative == true) ? 'public' . DIRECTORY_SEPARATOR . $path : Path::STORAGE_PUBLIC_PATH . $path;
    }

    /**
     * Create category images path
     *
     * @return boolean
     */
    public function createImagesPath()
    {
        $path = $this->getImagesPath();
        
        return (File::exists($path) == false) ? File::makeDir($path) : true;       
    }

    /**
     * Get hosted game url
     *
     * @param string|null $imageFileName
     * @param boolean $full
     * @return string
     */
    public function getImageUrl(?string $imageFileName = null, bool $full = false): string
    {
        $image = (empty($imageFileName) == true) ? $this->thumbnail : $imageFileName;

        return Page::getUrl('public/category/' . $image,$full);
    }

    /**
     * Return true if image is used
     *
     * @param string $fileName
     * @return boolean
     */
    public function isImagUsed(string $fileName): bool
    {
        $model = $this->where('thumbnail','=',$fileName)->first();

        return \is_object($model);
    } 
}
