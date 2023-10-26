@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="cls_bread">
    <ul>
        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
        <li><a>{{ translate('Commission History') }}</a></li>
    </ul>
</div>
    <div class="card">
        @include('backend.reports.partials.commission_history_section')
    </div>
@endsection