@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.typedocument.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.typedocument.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.typedocument.index') }}'"></i>

                        {{ __('admin::app.customers.typedocument.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.typedocument.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <div class="control-group" :class="[errors.has('prefijo') ? 'has-error' : '']">
                        <label for="name" class="required">
                            {{ __('admin::app.customers.typedocument.prefijo') }}
                        </label>
                        <input type="text" class="control" id="prefijo-input" name="prefijo" v-validate="'required'" value="{{ old('prefijo') }}" data-vv-as="&quot;{{ __('admin::app.customers.typedocument.prefijo') }}&quot;">
                        <span class="control-error" v-if="errors.has('prefijo')">@{{ errors.first('prefijo') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">
                            {{ __('admin::app.customers.typedocument.name') }}
                        </label>
                        <input type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.customers.typedocument.name') }}&quot;">
                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>
                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#prefijo-input').on('keyup', function (e) {
                // Escucha el evento de entrada en el campo de entrada con el id "prefijo-input"
                // Convierte el valor del campo de entrada en mayúsculas y establece el valor convertido nuevamente en el campo
                $(this).val($(this).val().toUpperCase());
            });
        });
    </script>
@endpush