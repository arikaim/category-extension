<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Category\Models\Traits;

use Arikaim\Extensions\Category\Models\Category;

/**
 * Image relation trait
 *      
*/
trait CategoryRelation
{    
    /**
     * Get category column relation name
     *
     * @return string
     */
    public function getCategoryColumn(): string
    {
        return $this->categoryColumn ?? 'category_id';
    }

    /**
     * Remove category relation id
     *
     * @return boolean
     */
    public function unsetCategory(): bool
    {
        return $this->setImage(null);
    }

    /**
     * Set category relation id
     *
     * @param integer|null $categoryId
     * @return boolean
     */
    public function setCategory(?int $categoryId): bool
    {
        return ($this->update([$this->getCategoryColumn() => $categoryId]) !== false);
    }

    /**
     * Get category relation
     *
     * @return Relation|null
     */
    public function category()
    {
        return $this->belongsTo(Category::class,$this->getCategoryColumn());
    }

    /**
     * Return true if category relation exist
     *
     * @return boolean
     */
    public function hasCategory(): bool
    {
        $categoryId = $this->attributes[$this->getCategoryColumn()] ?? null;

        return (empty($categoryId) == true) ? false : ($this->category !== null);
    }
}
