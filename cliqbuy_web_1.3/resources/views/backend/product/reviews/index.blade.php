@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('Product Reviews')}}</h1>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row flex-grow-1">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Product Reviews')}}</h5>
                
            </div>
            <div class="col-md-6 col-xl-4 ml-auto mr-0">
                <form class="" id="sort_by_rating" action="{{ route('reviews.index') }}" method="GET">
                    <div class="" style="min-width: 200px;">
                        <select class="form-control aiz-selectpicker" name="rating" id="rating" onchange="filter_by_rating()">
                            <option value="" <?php echo ($rating=='') ? 'selected="selected"' : ''; ?>>{{translate('Filter by Rating')}}</option>
                            <option value="high_to_low" <?php echo ($rating=='high_to_low') ? 'selected="selected"' : ''; ?>>{{translate('Rating (High > Low)')}}</option>
                            <option value="low_to_high" <?php echo ($rating=='low_to_high') ? 'selected="selected"' : ''; ?>>{{translate('Rating (Low > High)')}}</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Product')}}</th>
                    <th data-breakpoints="lg">{{translate('Product Owner')}}</th>
                    <th data-breakpoints="lg">{{translate('User')}}</th>
                    <th>{{translate('Rating')}}</th>
                    <th data-breakpoints="lg">{{translate('Comment')}}</th>
                    <th data-breakpoints="lg">{{translate('Published')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $key => $review)
                    @if ($review->product != null && $review->user != null)
                        <tr>
                            <td>{{ ($key+1) + ($reviews->currentPage() - 1)*$reviews->perPage() }}</td>
                            <td>
                                <a href="{{ route('product', $review->product->slug) }}" target="_blank" class="text-reset text-truncate-2">{{ $review->product->getTranslation('name') }}</a>
                            </td>
                            <td>{{ ($review->product->user->deleted_at == '') ? $review->product->user->name : 'Deleted User' }}</td>
                            <td>{{ ($review->user->deleted_at == '') ? $review->user->name : 'Deleted User' }} ({{ $review->user->email }})</td>
                            <td>{{ $review->rating }}</td>
                            <td>{{ $review->comment }}</td>
                            <td><label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_published(this)" value="{{ $review->id }}" type="checkbox" <?php if($review->status == 1) echo "checked";?> >
                                <span class="slider round"></span></label>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="aiz-pagination">
    {{ $reviews->appends(request()->input())->links() }}
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('reviews.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published reviews updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function filter_by_rating(el){
            var rating = $('#rating').val();
            // if (rating != '') {
                $('#sort_by_rating').submit();
            // }
        }
    </script>
@endsection
