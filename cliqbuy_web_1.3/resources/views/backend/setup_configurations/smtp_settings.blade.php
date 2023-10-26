@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Email Settings')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
                        <div class="col-md-9">
                            <select class="form-control aiz-selectpicker mb-2 mb-md-0" name="mail_driver" onchange="checkMailDriver()">
                                <option value="sendmail" @if (get_setting('mail_driver') == "sendmail") selected @endif>{{ translate('Sendmail') }}</option>
                                <option value="smtp" @if (get_setting('mail_driver') == "smtp") selected @endif>{{ translate('SMTP') }}</option>
                                <option value="mailgun" @if (get_setting('mail_driver') == "mailgun") selected @endif>{{ translate('Mailgun') }}</option>
                            </select>
                        </div>
                    </div>
                    <div id="smtp">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAIL HOST')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mail_host" value="{{  get_setting('mail_host') }}" placeholder="{{ translate('MAIL HOST') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAIL PORT')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mail_port" value="{{  get_setting('mail_port') }}" placeholder="{{ translate('MAIL PORT') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAIL USERNAME')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mail_username" value="{{  get_setting('mail_username') }}" placeholder="{{ translate('MAIL USERNAME') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAIL PASSWORD')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mail_password" value="{{  get_setting('mail_password') }}" placeholder="{{ translate('MAIL PASSWORD') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAIL ENCRYPTION')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mail_encryption" value="{{  get_setting('mail_encryption') }}" placeholder="{{ translate('MAIL ENCRYPTION') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAIL FROM ADDRESS')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="mail_from_address" value="{{  get_setting('mail_from_address') }}" placeholder="{{ translate('MAIL FROM ADDRESS') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAIL FROM NAME')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mail_from_name" value="{{  get_setting('mail_from_name') }}" placeholder="{{ translate('MAIL FROM NAME') }}">
                            </div>
                        </div>
                    </div>
                    <div id="mailgun">
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="MAILGUN_DOMAIN">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAILGUN DOMAIN')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mailgun_domain" value="{{  get_setting('mailgun_domain') }}" placeholder="{{ translate('MAILGUN DOMAIN') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="MAILGUN_SECRET">
                            <div class="col-md-3">
                                <label class="col-from-label">{{translate('MAILGUN SECRET')}}</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="mailgun_secret" value="{{  get_setting('mailgun_secret') }}" placeholder="{{ translate('MAILGUN SECRET') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save Configuration')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Test Email configuration')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('test.smtp') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" placeholder="{{ translate('Enter your email address') }}" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">{{ translate('Send test email') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{--
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Instruction')}}</h5>
            </div>
            <div class="card-body">
                <p class="text-danger">{{ translate('Please be carefull when you are configuring SMTP. For incorrect configuration you will get error at the time of order place, new registration, sending newsletter.') }}</p>
                <h6 class="text-muted">{{ translate('For Non-SSL') }}</h6>
                <ul class="list-group">
                    <li class="list-group-item text-dark">{{ translate('Select sendmail for Mail Driver if you face any issue after configuring smtp as Mail Driver ') }}</li>
                    <li class="list-group-item text-dark">{{ translate('Set Mail Host according to your server Mail Client Manual Settings') }}</li>
                    <li class="list-group-item text-dark">{{ translate('Set Mail port as 587') }}</li>
                    <li class="list-group-item text-dark">{{ translate('Set Mail Encryption as ssl if you face issue with tls') }}</li>
                </ul>
                <br>
                <h6 class="text-muted">{{ translate('For SSL') }}</h6>
                <ul class="list-group mar-no">
                    <li class="list-group-item text-dark">{{ translate('Select sendmail for Mail Driver if you face any issue after configuring smtp as Mail Driver') }}</li>
                    <li class="list-group-item text-dark">{{ translate('Set Mail Host according to your server Mail Client Manual Settings') }}</li>
                    <li class="list-group-item text-dark">{{ translate('Set Mail port as 465') }}</li>
                    <li class="list-group-item text-dark">{{ translate('Set Mail Encryption as ssl') }}</li>
                </ul>
            </div>
        </div>
        --}}
    </div>
</div>

@endsection

@section('script')

    <script type="text/javascript">
        $(document).ready(function(){
            checkMailDriver();
        });
        function checkMailDriver(){
            if($('select[name=MAIL_DRIVER]').val() == 'mailgun'){
                $('#mailgun').show();
                $('#smtp').hide();
            }
            else{
                $('#mailgun').hide();
                $('#smtp').show();
            }
        }
    </script>

@endsection
