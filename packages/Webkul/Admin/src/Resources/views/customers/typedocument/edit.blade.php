@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.typedocument.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.typedocument.update', $typedocument->id) }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.typedocument.index') }}'"></i>

                        {{ __('admin::app.customers.typedocument.edit-title') }}
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

                    <input name="_method" type="hidden" value="PUT">

                    <div class="control-group" :class="[errors.has('prefijo') ? 'has-error' : '']">
                        <label for="prefijo" class="required">
                            {{ __('admin::app.customers.typedocument.prefijo') }}
                        </label>
                        <input type="text" v-validate="'required'" class="control" id="prefijo" name="prefijo" data-vv-as="&quot;{{ __('admin::app.customers.typedocument.prefijo') }}&quot;" value="{{ old('prefijo') ?: $typedocument->prefijo }}" disabled="disabled"/>
                        <input type="hidden" name="prefijo" value="{{ $typedocument->prefijo }}"/>
                        <span class="control-error" v-if="errors.has('prefijo')">@{{ errors.first('prefijo') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">
                            {{ __('admin::app.customers.typedocument.name') }}
                        </label>
                        <input type="text" class="control" name="name" v-validate="'required'" value="{{ old('name') ?: $typedocument->name }}" data-vv-as="&quot;{{ __('admin::app.customers.typedocument.name') }}&quot;">
                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop