@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="cls_bread">
    <ul>
        <li><a href="{{ route('mainmenu') }}">{{ translate('main') }}</a></li>
        <li><a>{{ translate('Product Reviews')}}</a></li>
    </ul>
</div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Product Reviews') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Product')}}</th>
                        <th data-breakpoints="lg">{{ translate('Customer')}}</th>
                        <th>{{ translate('Rating')}}</th>
                        <th data-breakpoints="lg">{{ translate('Comment')}}</th>
                        <th data-breakpoints="lg">{{ translate('Published')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $key => $value)
                        @php
                            $review = \App\Review::find($value->id);
                        @endphp
                        @if($review != null && $review->product != null && $review->user != null)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    <a href="{{ route('product', $review->product->slug) }}" target="_blank">{{  $review->product->getTranslation('name') }}</a>
                                </td>
                                <td>{{ ($review->user->deleted_at == '') ? $review->user->name : 'Deleted User' }}</td>
                                <td>
                                    <span class="rating rating-sm">
                                        @for ($i=0; $i < $review->rating; $i++)
                                            <i class="las la-star active"></i>
                                        @endfor
                                        @for ($i=0; $i < 5-$review->rating; $i++)
                                            <i class="las la-star"></i>
                                        @endfor
                                    </span>
                                </td>
                                <td>{{ $review->comment }}</td>
                                <td>
                                    @if ($review->status == 1)
                                        <span class="badge badge-inline badge-success">{{  translate('Published') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{  translate('Unpublished') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="aiz-pagination">
        {{ $reviews->links() }}
      </div>

@endsection
