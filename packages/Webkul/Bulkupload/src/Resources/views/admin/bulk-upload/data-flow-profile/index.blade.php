@extends('admin::layouts.content')

@section('page_title')
	{{ __('bulkupload::app.admin.bulk-upload.data-flow-profile.index') }}
@stop

@push('css')
	<style>
		@media only screen and (max-width: 768px){
			.content-container .content .page-header .page-title .control-group .control{
				width: 100% !important;
				margin-top:-25px !important;
			}
		}
		.page-content {
			width: 100%;
			display: inline-block;
			padding: 8px 16px;

			.page-content-button {
				float: right;
			}

			.page-content-datagrid {
				display: block;
				clear: both;
			}
		}
	</style>
@endpush

@section('content')
	<div class="page-content">
   	<!-- Import New products -->
    <div class="import-new-products mt-45">
			<div class="heading mb-25">
				<h1>{{ __('bulkupload::app.admin.bulk-upload.data-flow-profile.add-profile') }}</h1>
			</div>

			<form method="POST" action="{{ route('bulkupload.bulk-upload.dataflow.add-profile') }}">
				@csrf
				<?php $familyId = app('request')->input('family') ?>

				<div class="control-group {{ $errors->first('name') ? 'has-error' :'' }}">
					<label for="profile_name" class="required">{{ __('bulkupload::app.admin.bulk-upload.data-flow-profile.name') }}</label>
					<input type="text" class="control" name="name" value=""/>
					<span class="control-error">{{ $errors->first('name') }}</span>
				</div>

				<div class="control-group {{ $errors->first('attribute_family') ? 'has-error' :'' }}">
					<label for="attribute_family" class="required">{{ __('admin::app.catalog.products.family') }}</label>

					<select class="control" id="attribute_family" name="attribute_family" {{ $familyId ? 'disabled' : '' }}>
						<option value="">
							{{ __('bulkupload::app.admin.bulk-upload.run-profile.please-select') }}
						</option>

						@foreach ($families as $family)
							<option value="{{ $family->id }}" {{ ($familyId == $family->id || old('attribute_family') == $family->id) ? 'selected' : '' }}>{{ $family->name }}</option>
						@endforeach
					</select>

					@if ($familyId)
						<input type="hidden" name="attribute_family" value="{{ $familyId }}"/>
					@endif

					<span class="control-error">{{ $errors->first('attribute_family') }}</span>
				</div>

				<div class="page-action" style="display:flex; justify-content: space-between;">
					<button type="submit" class="btn btn-lg btn-primary">
						{{ __('bulkupload::app.admin.bulk-upload.upload-files.save')  }}
					</button>
				</div>
			</form>
    </div>

    <accordian :title="'{{ __('bulkupload::app.admin.bulk-upload.data-flow-profile.grid') }}'" :active="true" class="mt-45">
			<div slot="body">
				<div class="page-content">
					{!! app('Webkul\Bulkupload\DataGrids\Admin\ProfileDataGrid')->render() !!}
				</div>
			</div>
    </accordian>
	</div>
@stop

