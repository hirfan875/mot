<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendyolCategories extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'id', 'title', 'parent_id' ];
    
    /**
     * get category sub categories
     *
     * @return \Illuminate\Support\Collection
     */
    public function subcategories()
    {
        return $this->hasMany(TrendyolCategories::class, 'parent_id')->with('subcategories')->where('status', 1);
    }
    
    /**
     * get parent category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(TrendyolCategories::class, 'parent_id');
    }
    

    public function children()
    {
        return $this->hasMany(TrendyolCategories::class, 'parent_id');
    }
    
    // recursive, loads all descendants
    public function childrenRecursive()
    {
       return $this->children()->with('childrenRecursive');
    }
    
    /**
     * get trendyol categories categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categoriesAssign()
    {
        return $this->BelongsToMany(Category::class, 'trendyol_categories_assign');
    }
    
    public function product()
    {
        return $this->hasOne(Product::class);
    }
    
}
