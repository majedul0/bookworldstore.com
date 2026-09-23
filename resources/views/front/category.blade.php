@extends('front.layouts.master')
@section('head')
    @include('meta::manager', [
        'title' => $category['title'] . ' - ' . env('APP_NAME'),
    ])
@endsection

@section('master')
@php
    $json_items = array();
@endphp
<section class="bg-[#eaeaea] pt-2">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="pb-0 md:mb-4 text-center md:text-left">
                @include('front.layouts.breadcrumb', [
                    'title' => $category['title'],
                    'url' => $category->route
                ])
            </div>

            <div class="text-center md:text-right">
                <p class="mt-3 text-gray-600">Total Result: {{$products->total()}}</p>
            </div>
        </div>

        <div class="pt-6 pb-14">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 cat_products">
                @foreach ($products as $key => $product)
                    @include('front.layouts.product-loop')
                    @php
                        $json_items[$key]["@type"] = "ListItem";
                        $json_items[$key]["position"] = $key + 1;
                        $json_items[$key]["url"] = $product->route;
                    @endphp
                @endforeach
            </div>

            @if(count($products) == 12)
            <div class="text-center">
                <button class="bg-[#01425f] text-white hover:bg-[#01425f] px-4 py-2 rounded-lg inline-block mt-8 loadMoreProductBtn">Load More</button>

                <button class="bg-gray-400 text-white hover:bg-[#01425f] px-4 py-2 rounded-lg inline-block mt-8 loadMoreProductLoader" style="display: none">
                    <svg class="animate-spin h-6 w-6 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>

                    Loading
                </button>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('footer')
<script type="application/ld+json">
    {
        "@context":"https://schema.org",
        "@type":"ItemList",
        "itemListElement":{!! json_encode($json_items, JSON_UNESCAPED_SLASHES) !!}
    }
</script>

<script type="application/ld+json">
    {
    "@context":"https://schema.org",
    "@type":"BreadcrumbList",
    "itemListElement":[
        {
            "item":{
                "name":"Home",
                "@id":"{{url('/')}}"
            },
            "@type":"ListItem",
            "position":"1"
        },
        {
            "item":{
                "name":"{{$category->title}}",
                "@id":"{{$category->route}}"
            },
            "@type":"ListItem",
            "position":"2"
        }
    ]
    }
</script>

<script>
    let product_skip = 12;
    $(document).on('click', '.loadMoreProductBtn', function(){
        $('.loadMoreProductBtn').hide();
        $('.loadMoreProductLoader').show();

        $.ajax({
            url: '{{route("loadProductAjax")}}',
            type: 'POST',
            dataType: 'JSON',
            data: {
                _token: "{{csrf_token()}}",
                skip: product_skip,
                category: "{{$category['id']}}",
                take: 12
            },
            success: function(response){
                $('.loadMoreProductBtn').show();
                $('.loadMoreProductLoader').hide();

                if(response.status){
                    $('.cat_products').append(response.html);

                    product_skip += 12;
                }else{
                    cAlert('error', response.message);

                    if(response.message == 'No more products!'){
                        $('.loadMoreProductBtn').remove();
                    }
                }
            },
            error: function(err){
                $('.loadMoreProductBtn').show();
                $('.loadMoreProductLoader').hide();

                cAlert('error', "Product load error!");
            }
        });
    });
</script>
@endsection
