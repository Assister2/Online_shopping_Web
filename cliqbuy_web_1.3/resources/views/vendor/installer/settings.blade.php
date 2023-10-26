@extends('vendor.installer.layouts.master')

@section('title', trans('messages.install.settings.title'))
@section('container')
{!! Form::open(['url'=>route('LaravelInstaller::database'),'method'=>'post']) !!}

<div class="buttons">
    <ul>
        <li>Database Migration Completed</li>
    </ul>
    <button class="button" type="submit">
        {{ trans('messages.install.next') }}
    </button>
</div>
{!! Form::close() !!}
@stop
