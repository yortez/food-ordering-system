<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    public function productRecipes()
    {
        return $this->hasMany(ProductRecipe::class);
    }
}
