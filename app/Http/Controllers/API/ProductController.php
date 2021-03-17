<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use App\Http\Controllers\API\BaseController;

class ProductController extends BaseController
{
    /**
     * Validation rules.
     *
     * @var array
     */
    private $rules = [
        'name'     => 'required|string|min:4|max:32',
        'category' => 'required|string|min:4|max:32',
        'price'    => 'required|integer|numeric|min:1',
        'stock'    => 'boolean',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Getting all products.
         */
        $products = Product::all();

        /**
         * Determines response send parameters.
         */
        $result  = ProductResource::collection($products);
        $message = 'Products retrieved successfully';

        return $this->sendResponse($result, $message);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Validation of transmitted parameters.
         */
        $validator = Validator::make($request->all(), $this->rules);

        /**
         * Sending error response if the validation fails.
         */
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        /**
         * Create product.
         */
        $product = Product::create($request->all());

        /**
         * Determines response send parameters.
         */
        $result  = new ProductResource($product);
        $message = 'Product created succesfully';

        return $this->sendResponse($result, $message, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
         * Find product.
         */
        $product = Product::find($id);

        /**
         * Sending error response when the product not found.
         */
        if (! $product) {
            return $this->sendError('Product not found');
        }

        /**
         * Determines response send parameters.
         */
        $result  = new ProductResource($product);
        $message = 'Product retrieved successfuly';

        return $this->sendResponse($result, $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        /**
         * Validation of transmitted parameters.
         */
        $validator = Validator::make($request->all(), $this->rules);

        /**
         * Sending error response if the validation fails.
         */
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors());
        }

        /**
         * Update product
         */
        $product->update($request->all());

        /**
         * Determines response send parameters.
         */
        $result  = new ProductResource($product);
        $message = 'Product updated successfuly';

        return $this->sendResponse($result, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        /**
         * Delete product
         */
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfuly');
    }
}
