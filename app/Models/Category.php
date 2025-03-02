<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function yellow_pages()
    {
        return $this->hasMany(YellowPage::class, 'category_id', 'id');
    }
}
