@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{translate('Google reCAPTCHA Setting')}}</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('google_recaptcha.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="control-label">{{translate('Google reCAPTCHA')}}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="google_recaptcha" type="checkbox" @if (get_setting('google_recaptcha') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="CAPTCHA_KEY">
                            <div class="col-md-4">
                                <label class="control-label">{{translate('Site KEY')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="google_recaptcha_value" value="{{  get_setting('google_recaptcha_value') }}" placeholder="{{ translate('Site KEY') }}" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>

            @if (get_setting('ship_engine') == 1)
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 h6">{{translates('ship_engine_settings')}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('ship_engine.update') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="ship_engine_api_key">
                                <div class="col-md-4">
                                    <label class="control-label">{{translates('ship_engine_api_key')}}</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="ship_engine_api_key" value="{{  get_setting('ship_engine_api_key') }}" placeholder="{{ translates('ship_engine_api_key') }}" required>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
