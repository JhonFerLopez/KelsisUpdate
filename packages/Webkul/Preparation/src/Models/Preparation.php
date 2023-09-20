<?php

namespace Webkul\Preparation\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\Collection as NestedCollection;
use Kalnoy\Nestedset\NodeTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Preparation\Contracts\Preparation as PreparationContract;
use Webkul\Preparation\Database\Factories\PreparationFactory;
use Webkul\Preparation\Repositories\PreparationRepository;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Models\ProductProxy;

class Preparation extends TranslatableModel implements PreparationContract
{
    use HasFactory, NodeTrait;

    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'description',
        'slug',
        'url_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'status',
        'display_mode',
        'parent_id',
        'additional',
    ];

    /**
     * Eager loading.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['image_url', 'banner_url', 'preparation_icon_url'];

    /**
     * The products that belong to the preparation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'product_preparations');
    }

    /**
     * The filterable attributes that belong to the preparation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filterableAttributes(): BelongsToMany
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'preparation_filterable_attributes')
            ->with([
                'options' => function ($query) {
                    $query->orderBy('sort_order');
                },
                'translations',
                'options.translations',
            ]);
    }

    /**
     * Finds and returns the preparation within a nested preparation tree.
     *
     * Will search in root preparation by default.
     *
     * Is used to minimize the numbers of sql queries for it only uses the already cached tree.
     *
     * @param  \Webkul\Velocity\Contracts\Preparation[]  $preparationTree
     * @return \Webkul\Velocity\Contracts\Preparation
     */
    public function findInTree($preparationTree = null): Preparation
    {
        if (! $preparationTree) {
            $preparationTree = app(PreparationRepository::class)->getVisiblePreparationTree($this->getRootPreparation()->id);
        }

        $preparation = $preparationTree->first();

        if (! $preparation) {
            throw new NotFoundHttpException('preparation not found in tree');
        }

        if ($preparation->id === $this->id) {
            return $preparation;
        }

        return $this->findInTree($preparation->children);
    }

    /**
     * Getting the root preparation of a preparation.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function getRootPreparation()
    {
        return self::query()
            ->where([
                [
                    'parent_id',
                    '=',
                    null,
                ], [
                    '_lft',
                    '<=',
                    $this->_lft,
                ], [
                    '_rgt',
                    '>=',
                    $this->_rgt,
                ],
            ])
            ->first();
    }

    /**
     * Returns all preparations within the preparation's path.
     *
     * @return \Webkul\Velocity\Contracts\Preparation[]
     */
    public function getPathPreparations(): array
    {
        $preparation = $this->findInTree();

        $preparations = [$preparation];

        while (isset($preparation->parent)) {
            $preparation = $preparation->parent;

            $preparations[] = $preparation;
        }

        return array_reverse($preparations);
    }

    /**
     * Get full slug.
     *
     * @return void
     */
    public function getFullSlug($localeCode)
    {
        /**
         * Getting all ancestors for url preparation.
         */
        $ancestors = $this->ancestors()->get();

        $preparations = (new NestedCollection())
            ->merge($ancestors)
            ->push($this);

        $preparations->shift();

        /**
         * In case of new locale which is not yet updated we need to filter out that one.
         *
         * To Do (@devansh): Need to monitor this more and improvisation also needed.
         */
        return $preparations->map(fn ($preparation) => $preparation->translate($localeCode))
            ->filter(fn ($preparation) => $preparation)
            ->pluck('slug')->join('/');
    }

    /**
     * This is updating the full url path for all locale.
     *
     * @return void
     */
    public function updateFullSlug()
    {
        /**
         * Self and descendants preparations.
         */
        $selfAndDescendants = $this->getDescendants()->prepend($this);

        /**
         * This loop will check all the descandant and update all the slug because parent slug got changed.
         *
         * To Do (@devansh): Need to monitor this more.
         */
        foreach ($selfAndDescendants as $preparation) {
            foreach (core()->getAllLocales() as $locale) {
                $preparationFullUrl = $preparation->getFullSlug($locale->code);

                $transalatedPreparation = $preparation->translate($locale->code);

                if ($transalatedPreparation) {
                    $transalatedPreparation->url_path = $preparationFullUrl;
                }
            }

            $preparation->save();
        }
    }

    /**
     * Get image url for the preparation image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get banner url attribute.
     *
     * @return string
     */
    public function getBannerUrlAttribute()
    {
        if (! $this->preparation_banner) {
            return;
        }

        return Storage::url($this->preparation_banner);
    }

    /**
     * Get preparation icon url for the preparation icon image.
     *
     * @return string
     */
    public function getPreparationIconUrlAttribute()
    {
        if (! $this->preparation_icon_path) {
            return;
        }

        return Storage::url($this->preparation_icon_path);
    }

    /**
     * Use fallback for preparation.
     *
     * @return bool
     */
    protected function useFallback(): bool
    {
        return true;
    }

    /**
     * Get fallback locale for preparation.
     *
     * @param  string|null  $locale
     * @return string|null
     */
    protected function getFallbackLocale(?string $locale = null): ?string
    {
        if ($fallback = core()->getDefaultChannelLocaleCode()) {
            return $fallback;
        }

        return parent::getFallbackLocale();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return PreparationFactory::new();
    }
}
