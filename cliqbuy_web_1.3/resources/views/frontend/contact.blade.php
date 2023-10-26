@extends('frontend.layouts.app')

@section('content')
<div class="contactus">
    <div class="container-fluid">

        <div class="col-lg-6 m-auto py-5">
            <div class="cls_contact card rounded p-4">
                <h1 class="h4 fw-600">{{ translate("contact_us")}}</h1>
                <form action="{{ route('contact_us') }}" class="mt-3" id="contact_form" method="POST">
                  @csrf
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{ translate("name")}} <em style="color:red">*</em></label>
                    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{ translate("name")}}" required="" name="name">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">{{ translate("email")}} <em style="color:red">*</em></label>
                    <input type="email" class="form-control" id="exampleInputPassword1" placeholder="{{ translate("email_address")}}" required name="email">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">{{ translate("feedback")}} <em style="color:red">*</em></label>
                    <textarea class="form-control" placeholder="{{ translate("feedback")}}" required name="description"></textarea>
                  </div>
                  <div class="text-right">
                      <button type="submit" class="contact_submit btn btn-primary">{{ translate("submit")}}</button>
                  </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

@endsection
