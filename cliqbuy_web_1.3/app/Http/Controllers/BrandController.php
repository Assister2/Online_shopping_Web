<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Brand;
use App\BrandTranslation;
use App\Product;
use Illuminate\Support\Str;
use Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $brands = Brand::orderBy('name', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');
        }
        $brands = $brands->paginate(15);
        return view('backend.product.brands.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name'         => 'required|unique:brands,name',           
        );
        $attributes = array(
            'name'         => 'Brand Name',                                  
        );

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $brand = new Brand;
        $brand->name = $request->name;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = str_replace(' ', '-', $request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $brand->logo = $request->logo;
        $brand->save();

        // $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);
        // $brand_translation->name = $request->name;
        // $brand_translation->save();

        flash(translate('brand_inserted'))->success();
        return redirect()->route('brands.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $brand  = Brand::findOrFail($id);
        return view('backend.product.brands.edit', compact('brand','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $rules = array(
            'name'         => 'required|unique:brands,name,'.$brand->id,           
        );
        $attributes = array(
            'name'         => 'Brand Name',                                  
        );

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $brand->name = $request->name;
        }
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $brand->slug = strtolower($request->slug);
        }
        else {
            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }
        $brand->logo = $request->logo;
        $brand->save();

        // $brand_translation = BrandTranslation::firstOrNew(['lang' => $request->lang, 'brand_id' => $brand->id]);
        // $brand_translation->name = $request->name;
        // $brand_translation->save();

        flash(translate('brand_updated'))->success();
        return redirect()->route('brands.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        $product_count = Product::where('brand_id', $brand->id)->count();

        if($product_count) {
            flash(translate('some_brand_msg'))->warning();
            return redirect()->route('brands.index');
        }

        Product::where('brand_id', $brand->id)->delete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->delete();
        }
        Brand::destroy($id);

        flash(translate('brand_deleted'))->success();
        return redirect()->route('brands.index');

    }
}
