@extends('front.layouts.master')
@section('head')
    @include('meta::manager', [
        'title' => ($settings_g['title'] ?? env('APP_NAME')) . ' - ' . ($settings_g['slogan'] ?? env('APP_NAME')),
        'description' => $settings_g['meta_description'] ?? '',
        'keywords' => $settings_g['keywords'] ?? '',
    ])
@endsection

@section('master')
@if (count($sliders))
<div>
    <div class="relative w-full overflow-hidden">
        <div class="auto-slider flex h-full transition-transform duration-700 ease-in-out">
            @foreach ($sliders as $slider)
            <div class="min-w-full h-full">
                <img src="{{ $slider->img_paths['original'] }}" class="w-full h-full object-cover" />
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<section class="py-10 lg:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-yellow-300 pb-2">Best Sellers</h2>
            {{-- <a href="#" class="text-gray-800 text-xl font-semibold px-3 py-1 bg-yellow-50 border border-yellow-400 hover:bg-yellow-400 transition-all duration-300 rounded">
                View All
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 inline ml-1 mb-1">
                    <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                </svg>
            </a> --}}
        </div>
        <div class="w-full relative py-3">
            <div class="owl-carousel owl-theme" id="slider1">
                @foreach ($featured_products as $product)
                    @include('front.layouts.product-loop')
                @endforeach
            </div>
            <button id="prev1" class="absolute top-1/2 -translate-y-1/2 left-1 hover:bg-black hover:text-white bg-gray-200/60 p-2 rounded-full shadow-md transition-all cursor-pointer opacity-65 duration-300 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                </svg>
            </button>
            <button id="next1" class="absolute top-1/2 -translate-y-1/2 right-1 hover:bg-black hover:text-white bg-gray-200/60 p-2 rounded-full shadow-md transition-all duration-300 z-10 cursor-pointer opacity-65">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</section>

{{-- <div>
    <div class="relative w-full overflow-hidden">
        <div class="auto-slider flex h-full transition-transform duration-700 ease-in-out">
            <div class="min-w-full h-full">
                <img src="./img/Frame_2.webp"
                class="w-full h-full object-cover" />
            </div>

            <div class="min-w-full h-full">
                <img src="./img/Frame_2.webp"
                class="w-full h-full object-cover" />
            </div>
        </div>
    </div>
</div> --}}

@foreach ($featured_categories as $category)
    <section class="py-10 lg:py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-yellow-300 pb-2">{{$category->title}}</h2>
                <a href="{{$category->route}}" class="text-gray-800 text-xl font-semibold px-3 py-1 bg-yellow-50 border border-yellow-400 hover:bg-yellow-400 transition-all duration-300 rounded">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4 inline ml-1 mb-1">
                        <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <div class="w-full relative py-3">
                <div class="owl-carousel owl-theme slider2">
                    @foreach ($category->Products->take(8) as $product)
                        @include('front.layouts.product-loop')
                    @endforeach
                </div>
                <button id="prev2" class="absolute top-1/2 -translate-y-1/2 left-1 hover:bg-black hover:text-white bg-gray-200/60 p-2 rounded-full shadow-md transition-all cursor-pointer opacity-65 duration-300 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button id="next2" class="absolute top-1/2 -translate-y-1/2 right-1 hover:bg-black hover:text-white bg-gray-200/60 p-2 rounded-full shadow-md transition-all duration-300 z-10 cursor-pointer opacity-65">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </section>
@endforeach
@endsection

@section('footer')
<script type="application/ld+json">
    {
    "@context":"http://schema.org",
    "@type":"WebSite",
    "url":"{{url('/')}}",
    "potentialAction":{
        "@type":"SearchAction",
        "target":"{{url('/search')}}?search={search}",
        "query-input":"required name=search"
    }
    }
</script>
@endsection
