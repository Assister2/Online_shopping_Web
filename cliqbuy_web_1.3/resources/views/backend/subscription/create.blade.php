@extends('backend.layouts.app')

@section('content')

<div class="row" ng-controller='Subscription' ng-init="custom_plan='No';is_free='No';currency_data={{json_encode($currency)}};duration_data={{json_encode(range(1,12))}}">
    <div class="col-lg-8 mr-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">Subscription Plan Information</h5>
            </div>
            <form  action="{{ route('subscription.add') }}" method="POST" id="subscriptionForm">
            	@csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">Plan Name</label>
                       
                                <div class="col-sm-9">
                                    <input type="text" placeholder="Plan Name" name="plan_name" class="form-control" autocomplete="off">
                                </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Description" >Description</label>
                        <div class="col-sm-9">
                            <textarea name="description"  autocomplete="off" rows="8" class="form-control" placeholder="Description"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Tagline">Plan Tagline</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Plan Tagline" name="tagline" class="form-control"autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">Is Custom Plan Type</label>
                        <div class="col-sm-3">
                            <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                <input ng-true-value="'Yes'" ng-false-value="'No'" ng-model="custom_plan" type="checkbox" name="custom_plan" >
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div ng-show="custom_plan=='No'">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Number of Products">Number of Products</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Number of Products" name="no_of_products" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row" >
                        <label class="col-sm-3 col-from-label">Is Free</label>
                        <div class="col-sm-3">
                            <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                        		<input value="1" type="checkbox" name="is_free" ng-true-value="'Yes'" ng-false-value="'No'" ng-model="is_free" ng-click="resetCustomPlan(is_free)">
                        		<span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row" ng-show="is_free=='No'">
                        <label class="col-sm-3 col-from-label" for="Price">Price</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Price" name="price" class="form-control"autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row" ng-show="is_free=='No'">
                        <label class="col-sm-3 col-from-label" for="name">Currency</label>
                        <div class="col-sm-9">
                            <select name="currency" class="form-control" >
                               <option value="">Select</option>
                                 <option ng-repeat="value in currency_data" value="@{{value}}" >@{{value}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">Duration (Months)</label>
                        <div class="col-sm-9">
                            <select name="duration" class="form-control " >
                                <option value="">Select</option>
                               <option value="@{{value}}" ng-repeat="value in duration_data">@{{value}}</option>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">Status</label>
                        <div class="col-sm-9">
                            <select name="status" class="form-control" >
                               <option value="">Select</option>
                               <option value="Active">Active</option>
                               <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div ng-init="translations = {{json_encode(old('translations') ?: array())}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}};">
                        <div class="panel-header">
                    <h5 class="box-title text-center">Translations</h5>
                  </div>
                   <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">
                   <div ng-repeat="translation in translations" class="my-3">
                    <div class="form-group row">
                         <input type="hidden" name="translations[@{{$index}}][id]" value="@{{translation.id}}">
                        <label class="col-sm-3 col-from-label" for="name">Language</label>
                        <div class="col-sm-8">
                            <select name="translations[@{{$index}}][locale]" class="form-control subscriptionForm"  id="input_language_@{{$index}}" ng-model="translation.locale" >
                                 <option value="" ng-if="translation.locale == ''">Select Language</option>
                                @foreach($languages as $key => $value)
                                  <option value="{{$key}}" ng-if="(('{{$key}}' | checkKeyValueUsedInStack : 'locale': translations) || '{{$key}}' == translation.locale) && '{{$key}}' != 'en'">{{$value}}</option>
                                @endforeach
                            </select>
                             <span class="text-danger ">@{{ errors['translations.'+$index+'.locale'][0] }}</span>
                        </div>
                        <div class="col-sm-1 p-0">
                          <button class="btn btn-danger" ng-click="translations.splice($index, 1); removed_translations.push(translation.id)">
                            <i class="las la-trash"></i>
                          </button>
                        </div>
                    </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">Plan Name</label>
                           
                                    <div class="col-sm-9">
                                        <input type="text" placeholder="Plan Name" name="translations[@{{$index}}][name]" class="form-control subscriptionForm" autocomplete="off">
                                    </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Description" >Description</label>
                            <div class="col-sm-9">
                                <textarea name="translations[@{{$index}}][description]"  autocomplete="off" rows="8" class="form-control subscriptionForm" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Tagline">Plan Tagline</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Plan Tagline" name="translations[@{{$index}}][tagline]" class="form-control subscriptionForm"autocomplete="off">
                            </div>
                        </div>
                       
                   </div>
                    <div class="panel-footer">
                    <div class="row" ng-show="translations.length <  {{count($languages) - 1}}">
                      <div class="col-sm-12 text-center my-3">
                        <button ng-if="translations.length<{{count($languages)}}" type="button" class="btn btn-info" ng-click="translations.push({locale:''});" >
                          <i class="las la-plus"></i> Add Translation
                        </button>
                      </div>
                    </div>
                  </div>
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="subscriptionSubmit btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
