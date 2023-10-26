<div class="get" style="margin-top:50px;">
    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
        <div class="">
            <ul class="list-unstyled">
                <li class="fw-500">
                    <a class="text-reset py-3 pl-4 px-3 d-block" href="{{ route('products.category', \App\Category::find($first_level_id)->slug) }}">{{ \App\Category::find($first_level_id)->getTranslation('name') }}</a>
                </li>
                <div class="sub_cate_box">
                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)               
                    <li class="other_sub_cate">
                        <a class="text-reset pl-4 py-3 px-3 d-block" href="{{ route('products.category', \App\Category::find($second_level_id)->slug) }}">{{ \App\Category::find($second_level_id)->getTranslation('name') }}</a>
                    </li>
                @endforeach
                </div>
            </ul>
        </div>
    @endforeach
</div>
