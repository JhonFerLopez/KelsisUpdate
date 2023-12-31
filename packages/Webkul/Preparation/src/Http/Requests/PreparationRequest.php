<?php

namespace Webkul\Preparation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webkul\Admin\Validations\ProductPreparationUniqueSlug;

class PreparationRequest extends FormRequest
{
    /**
     * Determine if the Configuraion is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $locale = core()->getRequestedLocaleCode();

        if ($id = request('id')) {
            return [
                $locale . '.slug' => ['required', new ProductPreparationUniqueSlug('preparation_translations', $id)],
                $locale . '.name' => 'required',
                'image.*'         => 'mimes:bmp,jpeg,jpg,png,webp',
            ];
        }

        return [
            'slug'        => ['required', new ProductPreparationUniqueSlug],
            'name'        => 'required',
            'image.*'     => 'mimes:bmp,jpeg,jpg,png,webp',
            'description' => 'required_if:display_mode,==,description_only,products_and_description',
        ];
    }
}
