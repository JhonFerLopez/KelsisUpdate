<?php

namespace Webkul\Geography\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Geography\Contracts\Town as TownContract;
use Webkul\Geography\Database\Factories\TownFactory;

/**
 * Class GeographyTranslation
 *
 * @package Webkul\Geography\Models
 *
 * @property-read string $url_path maintained by database triggers
 */
class Town extends Model implements TownContract
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return TownFactory::new();
    }
}