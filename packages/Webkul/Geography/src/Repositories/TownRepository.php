<?php

namespace Webkul\Geography\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Geography\Models\TownProxy;
use Webkul\Core\Eloquent\Repository;

class TownRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Geography\Contracts\Geography';
    }

    /**
     * Create geography.
     *
     * @param  array  $data
     * @return \Webkul\Geography\Contracts\Geography
     */
    public function create(array $data)
    {
        if (
            isset($data['locale'])
            && $data['locale'] == 'all'
        ) {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];

                        $data[$locale->code]['locale_id'] = $locale->id;
                    }
                }
            }
        }

        $geography = $this->model->create($data);

        $this->uploadImages($data, $geography);
        $this->uploadImages($data, $geography, 'geography_banner');
         
        if (isset($data['attributes'])) {
            $geography->filterableAttributes()->sync($data['attributes']);
        }

        return $geography;
    }

    /**
     * Update geography.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Geography\Contracts\Geography
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $geography = $this->find($id);

        $data = $this->setSameAttributeValueToAllLocale($data, 'slug');

        $geography->update($data);

        $this->uploadImages($data, $geography);
        $this->uploadImages($data, $geography, 'geography_banner');

        if (isset($data['attributes'])) {
            $geography->filterableAttributes()->sync($data['attributes']);
        }

        return $geography;
    }

    /**
     * Specify geography tree.
     *
     * @param  int  $id
     * @return \Webkul\Geography\Contracts\Geography
     */
    public function getGeographyTree($id = null)
    {
        return $id
            ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
            : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Specify geography tree.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function getGeographyTreeWithoutDescendant($id = null)
    {
        return $id
            ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->whereNotDescendantOf($id)->get()->toTree()
            : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Get root geographys.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRootGeographys()
    {
        return $this->getModel()->where('parent_id', null)->get();
    }

    /**
     * Get child geographys.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getChildGeographys($parentId)
    {
        return $this->getModel()->where('parent_id', $parentId)->get();
    }

    /**
     * get visible geography tree.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function getVisibleGeographyTree($id = null)
    {
        static $geographys = [];

        if (array_key_exists($id, $geographys)) {
            return $geographys[$id];
        }

        return $geographys[$id] = $id
            ? $this->model::orderBy('position', 'ASC')->where('status', 1)->descendantsAndSelf($id)->toTree($id)
            : $this->model::orderBy('position', 'ASC')->where('status', 1)->get()->toTree();
    }

    /**
     * Checks slug is unique or not based on locale.
     *
     * @param  int  $id
     * @param  string  $slug
     * @return bool
     */
    public function isSlugUnique($id, $slug)
    {
        $exists = TownProxy::modelClass()::where('geography_id', '<>', $id)
            ->where('slug', $slug)
            ->limit(1)
            ->select(DB::raw(1))
            ->exists();

        return ! $exists;
    }

    /**
     * Retrieve geography from slug.
     *
     * @param string $slug
     * @return \Webkul\Geography\Contracts\Geography
     */
    public function findBySlug($slug)
    {
        $geography = $this->model->whereTranslation('slug', $slug)->first();

        if ($geography) {
            return $geography;
        }
    }

    /**
     * Retrieve geography from slug.
     *
     * @param string $slug
     * @return \Webkul\Geography\Contracts\Geography
     */
    public function findBySlugOrFail($slug)
    {
        $geography = $this->model->whereTranslation('slug', $slug)->first();

        if ($geography) {
            return $geography;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $slug
        );
    }

    /**
     * Find by path.
     *
     * @param  string  $urlPath
     * @return \Webkul\Geography\Contracts\Geography
     */
    public function findByPath(string $urlPath)
    {
        return $this->model->whereTranslation('url_path', $urlPath)->first();
    }

    /**
     * Upload geography's images.
     *
     * @param  array  $data
     * @param  \Webkul\Geography\Contracts\Geography  $geography
     * @param  string $type
     * @return void
     */
    public function uploadImages($data, $geography, $type = 'image')
    {
        
        if (isset($data[$type])) {
            $request = request();
           
            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'geography/' . $geography->id;

                if ($request->hasFile($file)) {
                    if ($geography->{$type}) {
                        Storage::delete($geography->{$type});
                    }

                    $geography->{$type} = $request->file($file)->store($dir);

                    $geography->save();
                }
            }
        } else {
            if ($geography->{$type}) {
                Storage::delete($geography->{$type});
            }

            $geography->{$type} = null;
            
            $geography->save();
        }
    }
    

    /**
     * Get partials.
     *
     * @param  array|null  $columns
     * @return array
     */
    public function getPartial($columns = null)
    {
        $geographys = $this->model->all();

        $trimmed = [];

        foreach ($geographys as $key => $geography) {
            if (
                $geography->name != null
                || $geography->name != ''
            ) {
                $trimmed[$key] = [
                    'id'   => $geography->id,
                    'name' => $geography->name,
                    'slug' => $geography->slug,
                ];
            }
        }

        return $trimmed;
    }

    /**
     * Set same value to all locales in geography.
     *
     * To Do: Move column from the `geography_translations` to `geography` table. And remove
     * this created method.
     *
     * @param  array  $data
     * @param  string $attributeNames
     * @return array
     */
    private function setSameAttributeValueToAllLocale(array $data, ...$attributeNames)
    {
        $requestedLocale = core()->getRequestedLocaleCode();

        $model = app()->make($this->model());

        foreach ($attributeNames as $attributeName) {
            foreach (core()->getAllLocales() as $locale) {
                if ($requestedLocale == $locale->code) { 
                    foreach ($model->translatedAttributes as $attribute) {
                        if ($attribute === $attributeName) {
                            $data[$locale->code][$attribute] = isset($data[$requestedLocale][$attribute])
                                ? $data[$requestedLocale][$attribute]
                                : $data[$data['locale']][$attribute];
                        }
                    }
                }
            }
        }

        return $data;
    }
}
