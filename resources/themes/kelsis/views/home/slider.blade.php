@if (
    $velocityMetaData
    && $velocityMetaData->slider
)
    <div class="slider-container">
        <slider-component
            direction="{{ core()->getCurrentLocale()->direction }}"
            default-banner="{{ asset('/themes/kelsis/assets/images/banner-600x390.jpg') }}"
            :banners="{{ json_encode($sliderData) }}">

            {{-- this is default content if js is not loaded --}}
            @if(! empty($sliderData))
                <img class="col-12 no-padding banner-icon" src="{{ Storage::url($sliderData[0]['path']) }}" alt=""/>
            @else
                <img class="col-12 no-padding banner-icon" src="{{ asset('/themes/kelsis/assets/images/banner-600x390.jpg') }}" alt=""/>
            @endif

        </slider-component>
    </div>
@endif
