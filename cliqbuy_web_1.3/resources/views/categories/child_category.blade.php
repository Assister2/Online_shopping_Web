@php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '--';
    }

    $selected_top10_categories  = get_setting('top10_categories');
    $selected_top10_categories  = ($selected_top10_categories) ? json_decode(get_setting('top10_categories'), true) : [];
@endphp
<option value="{{ $child_category->id }}" <?php if(in_array($child_category->id, $selected_top10_categories)) { echo 'selected="selected"'; } ?>>{{ $value." ".$child_category->getTranslation('name') }}</option>
@if ($child_category->categories)
    @foreach ($child_category->categories as $childCategory)
        @include('categories.child_category', ['child_category' => $childCategory])
    @endforeach
@endif
