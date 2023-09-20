<?php

namespace Webkul\Preparation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Preparation\Contracts\PreparationTranslation as PreparationTranslationContract;
use Webkul\Preparation\Database\Factories\PreparationTranslationFactory;

/**
 * Class PreparationTranslation
 *
 * @package Webkul\Preparation\Models
 *
 * @property-read string $url_path maintained by database triggers
 */
class PreparationTranslation extends Model implements PreparationTranslationContract
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
        return PreparationTranslationFactory::new();
    }
}