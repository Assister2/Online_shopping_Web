@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{translate('Deleted User Reports')}}</h1>
    </div>
</div>

<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Deleted User Reports')}}</h5>
            </div>
        </div>
    
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="dropdown bootstrap-select mb-2 mb-md-0">
                    <select name="" id="" class="form-control aiz-selectpicker">
                        <option value="">Select User</option>
                        <option value="">User 1</option>
                        <option value="">User 2</option>
                        <option value="">User 3</option>
                    </select>
                </div>
                <div class="dropdown bootstrap-select mb-2 mb-md-0">
                    <select name="" id="" class="form-control aiz-selectpicker">
                        <option value="">Category</option>
                        <option value="">User 1</option>
                        <option value="">User 2</option>
                        <option value="">User 3</option>
                    </select>
                </div>
                </div>
            </div>

            <table class="table aiz-table mb-0 mt-4">
                <thead>
                    <tr>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                        <th data-breakpoints="lg">{{translate('Phone Number')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text">Demo name</td>
                        <td>trio@gmail.com</td>
                        <td>+919874563210</td>
                    </tr>
                </tbody>
            </table>

            <table class="table aiz-table mb-0 mt-4">
                <thead>
                    <tr>
                        <th>{{translate('Order Code')}}</th>
                        <th data-breakpoints="lg">{{translate('No of Products')}}</th>
                        <th data-breakpoints="lg">{{translate('Amount')}}</th>
                        <th data-breakpoints="lg">{{translate('Delivery Status')}}</th>
                        <th data-breakpoints="lg">{{translate('Payment Status')}}</th>
                        <th data-breakpoints="lg">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text">101</td>
                        <td>10</td>
                        <td>Rs.100/-</td>
                        <td>Delivered</td>
                        <td>Paid</td>
                        <td>
                            <a href="" class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                <i class="las la-eye"></i>
                            </a>
                            <a href="" class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                <i class="las la-download"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table aiz-table mb-0 mt-4">
                <thead>
                    <tr>
                        <th>{{translate('Ticket ID')}}</th>
                        <th data-breakpoints="lg">{{translate('Sending Date')}}</th>
                        <th data-breakpoints="lg">{{translate('User')}}</th>
                        <th data-breakpoints="lg">{{translate('Status')}}</th>
                        <th data-breakpoints="lg">{{translate('Last Reply')}}</th>
                        <th data-breakpoints="lg">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text">101</td>
                        <td>10/02/2022</td>
                        <td>User</td>
                        <td>Active</td>
                        <td>Reply</td>
                        <td>
                            <a href="" class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                <i class="las la-eye"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table aiz-table mb-0 mt-4">
                <thead>
                    <tr>
                        <th>{{translate('Date')}}</th>
                        <th data-breakpoints="lg">{{translate('Title')}}</th>
                        <th data-breakpoints="lg">{{translate('Sender')}}</th>
                        <th data-breakpoints="lg">{{translate('Reciever')}}</th>
                        <th data-breakpoints="lg">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text">20/12/2022</td>
                        <td>Title</td>
                        <td>Sender</td>
                        <td>Reciever</td>
                        <td>
                            <a href="" class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                <i class="las la-eye"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </form>
</div>
<div class="aiz-pagination">
    
</div>

@endsection