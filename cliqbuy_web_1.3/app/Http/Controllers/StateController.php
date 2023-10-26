<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\State;
use Validator;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $states = State::orderBy('id', 'desc');
         if ($request->has('search')){
            $sort_search = $request->search;
            $states = $states->where('name', 'like', '%'.$sort_search.'%');
        }
        $states = $states->paginate(15);
        $countries = Country::where('status', 1)->get();
        return view('backend.setup_configurations.states.index', compact('states', 'countries', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required',
            // 'cost'  => 'required|integer',          
            'short_name'  => 'required',          
        );
        $attributes = array(
            'name' => 'State Name',
            'cost'  => 'Cost',                                 
            'short_name'  => 'Short Name',                                 
        );
       $exists = State::where('country_id', $request->country_id)->get();
       foreach($exists as $exist){
            if($exist->name==$request->name){
                $rules['name'] = 'unique:states,name';
                break;
            }
        }

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $state = new State;

        $state->name = $request->name;
        $state->cost = @$request->cost??0;
        $state->country_id = $request->country_id;
        $state->short_name = $request->short_name;

        $state->save();

        flash(translate('state_inserted'))->success();

        return redirect()->route('states.index');
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
        $lang  = $request->lang;
        $state  = State::findOrFail($id);
        $countries = Country::where('status', 1)->get();
        return view('backend.setup_configurations.states.edit', compact('state', 'lang', 'countries'));
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
        $state = State::findOrFail($id);

        $rules = array(
            'name' => 'required',
            // 'cost'  => 'required|integer',   
            'short_name'  => 'required',   
        );
        $attributes = array(
            'name'         => 'State Name',
            'cost'         => 'Cost',
            'short_name'   => 'Short Name',
        );
        
        $exists = State::where('country_id', $request->country_id)->get();
        foreach($exists as $exist){
            if($exist->name==$request->name){
                $rules['name'] = 'unique:states,name,'.$state->id;
                break;
            }
        }

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->lang == env("DEFAULT_LANGUAGE")){
            $state->name = $request->name;
        }

        $state->country_id = $request->country_id;
        $state->cost = @$request->cost??0;
        $state->short_name = @$request->short_name;

        $state->save();

        // $state_translation = StateTranslation::firstOrNew(['lang' => $request->lang, 'state_id' => $state->id]);
        // $state_translation->name = $request->name;
        // $state_translation->save();

        flash(translate('state_updated'))->success();
        return redirect()->route('states.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::findOrFail($id);

        foreach ($state->state_translations as $key => $state_translation) {
            $state_translation->delete();
        }

        State::destroy($id);

        flash(translate('state_deleted'))->success();
        return redirect()->route('states.index');
    }

     public function get_state(Request $request) {        
        $states = State::where('country_id', $request->country_id)->get();
        $html = '';
        if($states->count()){
            foreach ($states as $row) {
//                $val = $row->id . ' | ' . $row->name;
                $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('nothing_selected') . '</option>';
        }
        
        
        echo json_encode($html);
    }
}
