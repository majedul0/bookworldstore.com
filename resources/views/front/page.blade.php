@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => $page->title . ' - ' . ($settings_g['title'] ?? ''),
        'image' => $page->media_id ? $page->img_paths['medium'] : null,
        'description' => $page->meta_description,
        'keywords' => $page->meta_tags
    ])

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;500;700&display=swap" rel="stylesheet">
@endsection

@section('master')
<div class="container">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="pb-0 md:mb-4 text-center md:text-left">
            @include('front.layouts.breadcrumb', [
                'title' => $page->title
            ])
        </div>
    </div>

    <div class="py-10">
        <h3 class="text-center text-2xl mb-5 font-semibold">{{$page->title}}</h3>

        <div class="dynamic_style">
            {!! $page->description !!}
        </div>
    </div>
</div>
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
                "name":"{{$page->title}}",
                "@id":"{{$page->route}}"
            },
            "@type":"ListItem",
            "position":"2"
        }
    ]
    }
</script>

@if($page->media_id && $page->meta_description && $page->description)
<script type="application/ld+json">
    {
    "@context": "http://schema.org/",
    "@type": "Article",
    "author": "EOMSBD",
    "publisher": {
        "name":"{{$settings_g['title']}}",
        "@type": "Organization",
        "url": "{{route('homepage')}}",
        "sameAs": [
            "https://www.facebook.com"
        ],
        "logo": {
        "@type": "ImageObject",
        "url": "{{$settings_g['logo']}}"
        },
        "contactPoint": [{
            "@type": "ContactPoint",
            "telephone": "{{$settings_g['mobile_number']}}",
            "contactType": "customer service"
        }]
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{route('homepage')}}"
    },
    "headline": "{{$page->title}}",
    "name": "{{$page->meta_description}}",
    "image": "{{$page->img_paths['medium']}}",
    "description": "{!!preg_replace("/\r|\n/", " ", $page->description)!!}",
    "datePublished": "{{$page->created_at}}",
    "dateModified": "{{$page->updated_at}}"
    }
    </script>
@endif

@endsection
