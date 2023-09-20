@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.typedocument.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.typedocument.title') }}</h1>
            </div>

            <div class="page-action">
                @if (bouncer()->hasPermission('customers.typedocument.create'))
                    <a href="{{ route('admin.typedocument.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.typedocument.add-title') }}
                    </a>
                @endif
            </div>
        </div>
        <div class="page-content">
            <datagrid-plus src="{{ route('admin.typedocument.index') }}"></datagrid-plus>
        </div>
    </div>
@stop
