<?php

Route::group([
    //   'prefix'     => 'placetopay',
       'middleware' => ['web', 'theme', 'locale', 'currency']
   ], function () {
	   
       Route::get('placetopay-redirect',"Wontonee\Placetopay\Http\Controllers\PlacetopayController@redirect")->name('placetopay.process');
       Route::post('placetopay-success',"Wontonee\Placetopay\Http\Controllers\PlacetopayController@success")->name('placetopay.success');
       Route::post('placetopay-failure',"Wontonee\Placetopay\Http\Controllers\PlacetopayController@failure")->name('placetopay.failure');
     
});