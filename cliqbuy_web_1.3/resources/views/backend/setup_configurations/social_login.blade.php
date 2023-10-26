@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Google Login Credential')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Client ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="google_client_id" value="{{  get_setting('google_client_id') }}" placeholder="{{ translate('Google Client ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Client Secret')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="google_client_secret" value="{{  get_setting('google_client_secret') }}" placeholder="{{ translate('Google Client Secret') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Facebook Login Credential')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('App ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="facebook_client_id" value="{{ get_setting('facebook_client_id') }}" placeholder="{{ translate('Facebook Client ID') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('App Secret')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="facebook_client_secret" value="{{ get_setting('facebook_client_secret') }}" placeholder="{{ translate('Facebook Client Secret') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Apple Login Credential')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Service ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="apple_service_id" value="{{ get_setting('apple_service_id') }}" placeholder="{{ translate('Apple Service ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Team ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="apple_team_id" value="{{ get_setting('apple_team_id') }}" placeholder="{{ translate('Apple Team ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Key ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="apple_key_id" value="{{ get_setting('apple_key_id') }}" placeholder="{{ translate('Apple Key ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Key File')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="file" class="form-control" name="apple_key_file" value="{{ get_setting('apple_key_file') }}" placeholder="{{ translate('Apple Key File') }}" required>
                            @if ($errors->has('APPLE_KEY_FILE'))  
                                <span class="text-danger">
                                    <p>{{translate('Apple Key File is invalid')}}</p>
                                </span> 
                            @endif
                        </div>    
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
