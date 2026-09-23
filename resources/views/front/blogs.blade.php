@extends('front.layouts.master')
@section('head')
    @include('meta::manager', [
        'title' => 'Blogs - ' . env('APP_NAME'),
    ])
@endsection

@section('master')
<section class="bg-[#eaeaea] pt-2">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="pb-0 md:mb-4 text-center md:text-left">
                @include('front.layouts.breadcrumb', [
                    'title' => 'Blogs'
                ])
            </div>
        </div>

        <div class="pt-6 pb-14">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 cat_products">
                @foreach ($blogs as $blog)
                    @include('front.layouts.blog-loop')
                @endforeach
            </div>
        </div>

        <div class="pb-8">
            {{$blogs->links('pagination::tailwind')}}
        </div>
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
                    "name":"Articles",
                    "@id":"{{route('blogs')}}"
                },
                "@type":"ListItem",
                "position":"2"
            }
        ]
    }
</script>
@endsection
