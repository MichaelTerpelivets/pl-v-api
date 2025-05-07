<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Represents the Product model.
 *
 * This model interacts with the 'products' table and supports attributes such as
 * 'id', 'name', 'slug', 'price', 'category', and 'attributes'.
 *
 * Includes attribute casting for the 'attributes' property to an array.
 *
 * Automatically generates a 'slug' when the 'name' attribute is set, if the 'slug'
 * is not provided.
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 */
class Product extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'price',
        'category',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    /**
     * Set the "name" attribute for the model.
     * If the "slug" attribute is empty, it generates a slug from the given "name" value.
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }
}
