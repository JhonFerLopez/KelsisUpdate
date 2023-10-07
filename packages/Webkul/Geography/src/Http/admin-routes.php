<?php

Route::group(['middleware' => ['web']], function () {
  Route::prefix('admin')->group(function () {
      Route::group(['middleware' => ['admin']], function () {
        Route::get('geography', 'Webkul\Geography\Http\Controllers\Admin\DepartmentController@index')->defaults('_config', [
          'view' => 'geography::admin.geography.department.index'
        ])->name('admin.department.index');
      });
  });
});