<?php

namespace Webkul\Geography\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\Collection as NestedCollection;
use Kalnoy\Nestedset\NodeTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Geography\Contracts\Department as DepartmentContract;
use Webkul\Geography\Database\Factories\DepartmentFactory;
use Webkul\Geography\Repositories\GeographyRepository;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Models\ProductProxy;

class Department extends TranslatableModel implements DepartmentContract
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
    protected $appends = ['image_url', 'banner_url', 'geography_icon_url'];

    /**
     * The products that belong to the geography.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'product_geographys');
    }

    /**
     * The filterable attributes that belong to the geography.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function filterableAttributes(): BelongsToMany
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'geography_filterable_attributes')
            ->with([
                'options' => function ($query) {
                    $query->orderBy('sort_order');
                },
                'translations',
                'options.translations',
            ]);
    }

    /**
     * Finds and returns the geography within a nested geography tree.
     *
     * Will search in root geography by default.
     *
     * Is used to minimize the numbers of sql queries for it only uses the already cached tree.
     *
     * @param  \Webkul\Velocity\Contracts\Department[]  $departmentTree
     * @return \Webkul\Velocity\Contracts\Department
     */
    public function findInTree($geographyTree = null): Department
    {
        if (! $geographyTree) {
            $geographyTree = app(GeographyRepository::class)->getVisibleGeographyTree($this->getRootGeography()->id);
        }

        $geography = $geographyTree->first();

        if (! $geography) {
            throw new NotFoundHttpException('geography not found in tree');
        }

        if ($geography->id === $this->id) {
            return $geography;
        }

        return $this->findInTree($geography->children);
    }

    /**
     * Getting the root geography of a geography.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function getRootGeography()
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
     * Returns all geographys within the geography's path.
     *
     * @return \Webkul\Velocity\Contracts\Geography[]
     */
    public function getPathGeographys(): array
    {
        $geography = $this->findInTree();

        $geographys = [$geography];

        while (isset($geography->parent)) {
            $geography = $geography->parent;

            $geographys[] = $geography;
        }

        return array_reverse($geographys);
    }

    /**
     * Get full slug.
     *
     * @return void
     */
    public function getFullSlug($localeCode)
    {
        /**
         * Getting all ancestors for url geography.
         */
        $ancestors = $this->ancestors()->get();

        $geographys = (new NestedCollection())
            ->merge($ancestors)
            ->push($this);

        $geographys->shift();

        /**
         * In case of new locale which is not yet updated we need to filter out that one.
         *
         * To Do (@devansh): Need to monitor this more and improvisation also needed.
         */
        return $geographys->map(fn ($geography) => $geography->translate($localeCode))
            ->filter(fn ($geography) => $geography)
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
         * Self and descendants geographys.
         */
        $selfAndDescendants = $this->getDescendants()->prepend($this);

        /**
         * This loop will check all the descandant and update all the slug because parent slug got changed.
         *
         * To Do (@devansh): Need to monitor this more.
         */
        foreach ($selfAndDescendants as $geography) {
            foreach (core()->getAllLocales() as $locale) {
                $geographyFullUrl = $geography->getFullSlug($locale->code);

                $transalatedGeography = $geography->translate($locale->code);

                if ($transalatedGeography) {
                    $transalatedGeography->url_path = $geographyFullUrl;
                }
            }

            $geography->save();
        }
    }

    /**
     * Get image url for the geography image.
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
        if (! $this->geography_banner) {
            return;
        }

        return Storage::url($this->geography_banner);
    }

    /**
     * Get geography icon url for the geography icon image.
     *
     * @return string
     */
    public function getGeographyIconUrlAttribute()
    {
        if (! $this->geography_icon_path) {
            return;
        }

        return Storage::url($this->geography_icon_path);
    }

    /**
     * Use fallback for geography.
     *
     * @return bool
     */
    protected function useFallback(): bool
    {
        return true;
    }

    /**
     * Get fallback locale for geography.
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
        return DepartmentFactory::new();
    }
}
