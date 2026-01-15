<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request)
    {
        /**
         * Фильтры (через query-параметры):
         * q — поиск по подстроке в name
         * price_from, price_to
         * category_id
         * in_stock (true/false)
         * rating_from
         *
         * Сортировка:
         * параметр sort с допустимыми значениями: price_asc, price_desc, rating_desc, newest.
         *
         * Обязательна пагинация.
         **/

        $products = Product::query()
            ->with('category')
            ->filter($request->filters())
            ->paginate($request->input('per_page', 20));

        return ProductResource::collection($products)
//            ->additional(['success' => true])
            ->response()
            ->getData(true);

    }
}
