<?php

namespace App\Http\Controllers;

use Webkul\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Rules\ProductUpdateValidationRule;

class ProductController extends Controller
{

  public function index()
  {  
    $response['status'] = Response::HTTP_OK;
    $response['data'] = 'MEnsaje';
    return response()->json($response, $response['status']);
  }

  public function update(Request $request)
  {  
    $data = $request->json()->all();

    $validator = Validator::make($data, [
        '*.sku' => 'required|string',
        '*.price' => 'required|numeric',
        '*.stock' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    foreach ($data as $productData) {
      $product = Product::where('sku', $productData['sku'])->first();
      $response['status'] = Response::HTTP_OK;
      $response['data'] = $product;
      /*if ($product) {
        $product->update([
          'price' => $productData['price'],
          'stock' => $productData['stock'],
        ]);
      }*/
    }

    //return response()->json(['message' => 'Productos actualizados correctamente']);
    return response()->json($response, $response['status']);
  }
}