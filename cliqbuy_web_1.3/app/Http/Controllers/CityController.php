<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;
use App\Country;
use App\State;
use App\CityTranslation;
use Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $cities = City::orderBy('id', 'desc');
         if ($request->has('search')){
            $sort_search = $request->search;
            $cities = $cities->where('name', 'like', '%'.$sort_search.'%');
        }
        $cities = $cities->paginate(15);
        $countries = Country::where('status', 1)->get();
        return view('backend.setup_configurations.cities.index', compact('cities', 'countries', 'sort_search'));
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
            'name' => 'required|unique:cities,name',
            'cost'  => 'required|integer',          
        );
        $attributes = array(
            'name' => 'City Name',
            'cost'  => 'Cost',                                 
        );

        // $exists = City::where('state_id', $request->state_id)->get();
        // foreach($exists as $exist){
        //     if($exist->name==$request->name){
        //         $rules['name'] = 'unique:cities,name';
        //         break;
        //     }
        // }

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $city = new City;

        $city->name = $request->name;
        $city->cost = $request->cost;
        $city->country_id = $request->country_id;
        $city->state_id = $request->state_id;

        $city->save();

        flash(translate('city_inserted'))->success();

        return redirect()->route('cities.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit(Request $request, $id)
     {
         $lang  = $request->lang;
         $city  = City::findOrFail($id);
         $countries = Country::where('status', 1)->get();
         return view('backend.setup_configurations.cities.edit', compact('city', 'lang', 'countries'));
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
        $city = City::findOrFail($id);

        $rules = array(
            'name' => 'required|unique:cities,name,'.$city->id,
            'cost'  => 'required|integer',   
        );
        $attributes = array(
            'name'         => 'City Name',
            'cost'         => 'Cost',
        );

        // $exists = City::where('state_id', $request->state_id)->get();
        // foreach($exists as $exist){
        //     if($exist->name==$request->name){
        //         $rules['name'] = 'unique:cities,name,'.$city->id;
        //         break;
        //     }
        // }

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->lang == env("DEFAULT_LANGUAGE")){
            $city->name = $request->name;
        }

        $city->country_id = $request->country_id;
        $city->state_id = $request->state_id;
        $city->cost = $request->cost;

        $city->save();

        // $city_translation = CityTranslation::firstOrNew(['lang' => $request->lang, 'city_id' => $city->id]);
        // $city_translation->name = $request->name;
        // $city_translation->save();

        flash(translate('city_updated'))->success();
        return redirect()->route('cities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);

        foreach ($city->city_translations as $key => $city_translation) {
            $city_translation->delete();
        }

        City::destroy($id);

        flash(translate('city_deleted'))->success();
        return redirect()->route('cities.index');
    }
    
    public function get_city(Request $request) {        
        $states = City::where('state_id', $request->state_id)->get();
        $html = '';
        if($states->count()){
            foreach ($states as $row) {
                $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
            }
        } else {
            $html .= '<option value="">'.translate('nothing_selected').'</option>';
        }
        
        
        echo json_encode($html);
    }
}
