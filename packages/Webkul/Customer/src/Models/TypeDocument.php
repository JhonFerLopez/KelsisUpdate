<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Customer\Database\Factories\TypeDocumentFactory;
use Webkul\Customer\Contracts\TypeDocument as TypeDocumentContract;

class TypeDocument extends Model implements TypeDocumentContract
{
    use HasFactory;

    protected $table = 'type_document';

    protected $fillable = [
        'prefijo',
        'name',
    ];

    /**
     * Create a new factory instance for the model
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return TypeDocumentFactory::new();
    }
}
