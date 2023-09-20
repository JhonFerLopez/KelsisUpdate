<?php

namespace Webkul\Preparation\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Webkul\Preparation\Models\PreparationTranslationProxy;
use Webkul\Core\Eloquent\Repository;

class PreparationRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Preparation\Contracts\Preparation';
    }

    /**
     * Create preparation.
     *
     * @param  array  $data
     * @return \Webkul\Preparation\Contracts\Preparation
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

        $preparation = $this->model->create($data);

        $this->uploadImages($data, $preparation);
        $this->uploadImages($data, $preparation, 'preparation_banner');
         
        if (isset($data['attributes'])) {
            $preparation->filterableAttributes()->sync($data['attributes']);
        }

        return $preparation;
    }

    /**
     * Update preparation.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Preparation\Contracts\Preparation
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $preparation = $this->find($id);

        $data = $this->setSameAttributeValueToAllLocale($data, 'slug');

        $preparation->update($data);

        $this->uploadImages($data, $preparation);
        $this->uploadImages($data, $preparation, 'preparation_banner');

        if (isset($data['attributes'])) {
            $preparation->filterableAttributes()->sync($data['attributes']);
        }

        return $preparation;
    }

    /**
     * Specify preparation tree.
     *
     * @param  int  $id
     * @return \Webkul\Preparation\Contracts\Preparation
     */
    public function getPreparationTree($id = null)
    {
        return $id
            ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->get()->toTree()
            : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Specify preparation tree.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function getPreparationTreeWithoutDescendant($id = null)
    {
        return $id
            ? $this->model::orderBy('position', 'ASC')->where('id', '!=', $id)->whereNotDescendantOf($id)->get()->toTree()
            : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    /**
     * Get root preparations.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRootPreparations()
    {
        return $this->getModel()->where('parent_id', null)->get();
    }

    /**
     * Get child preparations.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getChildPreparations($parentId)
    {
        return $this->getModel()->where('parent_id', $parentId)->get();
    }

    /**
     * get visible preparation tree.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function getVisiblePreparationTree($id = null)
    {
        static $preparations = [];

        if (array_key_exists($id, $preparations)) {
            return $preparations[$id];
        }

        return $preparations[$id] = $id
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
        $exists = PreparationTranslationProxy::modelClass()::where('preparation_id', '<>', $id)
            ->where('slug', $slug)
            ->limit(1)
            ->select(DB::raw(1))
            ->exists();

        return ! $exists;
    }

    /**
     * Retrieve preparation from slug.
     *
     * @param string $slug
     * @return \Webkul\Preparation\Contracts\Preparation
     */
    public function findBySlug($slug)
    {
        $preparation = $this->model->whereTranslation('slug', $slug)->first();

        if ($preparation) {
            return $preparation;
        }
    }

    /**
     * Retrieve preparation from slug.
     *
     * @param string $slug
     * @return \Webkul\Preparation\Contracts\Preparation
     */
    public function findBySlugOrFail($slug)
    {
        $preparation = $this->model->whereTranslation('slug', $slug)->first();

        if ($preparation) {
            return $preparation;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $slug
        );
    }

    /**
     * Find by path.
     *
     * @param  string  $urlPath
     * @return \Webkul\Preparation\Contracts\Preparation
     */
    public function findByPath(string $urlPath)
    {
        return $this->model->whereTranslation('url_path', $urlPath)->first();
    }

    /**
     * Upload preparation's images.
     *
     * @param  array  $data
     * @param  \Webkul\Preparation\Contracts\Preparation  $preparation
     * @param  string $type
     * @return void
     */
    public function uploadImages($data, $preparation, $type = 'image')
    {
        
        if (isset($data[$type])) {
            $request = request();
           
            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'preparation/' . $preparation->id;

                if ($request->hasFile($file)) {
                    if ($preparation->{$type}) {
                        Storage::delete($preparation->{$type});
                    }

                    $preparation->{$type} = $request->file($file)->store($dir);

                    $preparation->save();
                }
            }
        } else {
            if ($preparation->{$type}) {
                Storage::delete($preparation->{$type});
            }

            $preparation->{$type} = null;
            
            $preparation->save();
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
        $preparations = $this->model->all();

        $trimmed = [];

        foreach ($preparations as $key => $preparation) {
            if (
                $preparation->name != null
                || $preparation->name != ''
            ) {
                $trimmed[$key] = [
                    'id'   => $preparation->id,
                    'name' => $preparation->name,
                    'slug' => $preparation->slug,
                ];
            }
        }

        return $trimmed;
    }

    /**
     * Set same value to all locales in preparation.
     *
     * To Do: Move column from the `preparation_translations` to `preparation` table. And remove
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
