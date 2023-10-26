<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Utility\CategoryUtility;
use App\Product;

class CategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                $id = $data->id;

                // product count by categoty
                $category_ids = CategoryUtility::children_ids($id);
                $category_ids[] = $id;
                $products = Product::whereIn('category_id', $category_ids)->where('published', 1);

                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'banner' => api_asset($data->banner),
                    'icon' => api_asset($data->icon),
                    'order_level' => $data->order_level,
                    'number_of_children' => CategoryUtility::get_immediate_children_count($data->id),
                    'products_count' => filter_products($products)->count(),
                    'links' => [
                        'products' => route('api.products.category', $data->id),
                        'sub_categories' => route('subCategories.index', $data->id)
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
