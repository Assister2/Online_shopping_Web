<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;
use App\Brand;
use App\User;
use Auth;
use App\ProductsImport;
use App\ProductsExport;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Excel;
use Illuminate\Support\Str;
use Validator;

class ProductBulkUploadController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.product_bulk_upload.index');
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.product.bulk_upload.index');
        }
    }

    public function export(){
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function pdf_download_category()
    {
        $categories = Category::all();

        return PDF::loadView('backend.downloads.category',[
            'categories' => $categories,
        ])->download('category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();

        return PDF::loadView('backend.downloads.brand',[
            'brands' => $brands,
        ])->download('brands.pdf');
    }

    public function pdf_download_seller()
    {
        $users = User::where('user_type','seller')->get();

        return PDF::loadView('backend.downloads.user',[
            'users' => $users,
        ])->download('user.pdf');

    }

    public function bulk_upload(Request $request)
    {
        if (Auth::user()->user_type == 'admin' && env('APP_ENV') =='live') {
            flash(translate('live_restriction'))->error();
            return redirect(url()->previous());
        }

        $rules = array(
            'bulk_file'         => 'required|mimes:xlsx,csv',
        );

        $attributes = array(
            'bulk_file' => 'Product File',
        );

        $validator = Validator::make($request->all(), $rules,[],$attributes);
        if ($validator->fails()) {
            flash(translate("upload_csv_only"))->error();
            return back();
        }

        if($request->hasFile('bulk_file')){
            $import = new ProductsImport;
            Excel::import($import, request()->file('bulk_file'));
            
            if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && 
                    \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
                $seller = Auth::user()->seller;
                $seller->remaining_uploads -= $import->getRowCount();
                $seller->save();
            }
//            dd('Row count: ' . $import->getRowCount());
        }
        
        
        return back();
    }

}
