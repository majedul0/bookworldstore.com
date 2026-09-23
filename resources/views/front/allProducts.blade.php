@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'Shop - ' . ($settings_g['title'] ?? env('APP_NAME')),
    ])
@endsection

@section('master')
<section class="bg-[#eaeaea] pt-2">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="pb-0 md:mb-4 text-center md:text-left">
                @include('front.layouts.breadcrumb', [
                    'title' => 'Shop',
                    'url' => route('shop')
                ])
            </div>

            <div class="text-center md:text-right">
                <p class="mt-3 text-gray-600">Total Result: {{$products->total()}}</p>
            </div>
        </div>

        @if(count($products))
            <div class="pt-6 pb-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 cat_products">
                    @foreach ($products as $product)
                        @include('front.layouts.product-loop')
                    @endforeach
                </div>
            </div>

            <div class="pb-8">
                {{$products->links('pagination::tailwind')}}
            </div>
        @else
            <div class="text-center py-40">
                <p class="text-red-600 font-bold text-3xl mb-5">দুঃখিত কোন পণ্য পাওয়া যায়নি</p>

                <a href="{{route('homepage')}}" class="text-center rounded-md border-2 border-primary bg-primary px-6 py-2 text-base font-medium text-font-color-light shadow-sm hover:bg-white hover:text-primary text-white">Home</a>
            </div>
        @endif
    </div>
</section>
@endsection

@section('footer')
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
                    "name":"Shop",
                    "@id":"{{route('shop')}}"
                },
                "@type":"ListItem",
                "position":"2"
            }
        ]
    }
</script>
@endsection
