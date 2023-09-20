@if ($preparations->count())

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.preparations.before', ['product' => $product]) !!}

<accordian title="{{ __('admin::app.catalog.products.preparations') }}" :active="false">
    <div slot="body">

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.preparations.controls.before', ['product' => $product]) !!}

        <tree-view behavior="normal" value-field="id" name-field="preparations" input-type="checkbox" 
        items='@json($preparations)' 
        value='@json($product->preparations->pluck("id"))' 
        fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>

        {!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.preparations.controls.after', ['product' => $product]) !!}

    </div>
</accordian>

{!! view_render_event('bagisto.admin.catalog.product.edit_form_accordian.preparations.after', ['product' => $product]) !!}

@endif