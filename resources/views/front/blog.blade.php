@extends('front.layouts.master')
@section('head')
@include('meta::manager', [
    'title' => $blog['title'] . ' - ' . env('APP_NAME'),
])
@endsection

@section('master')
<div class="container">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="pb-0 md:mb-4 text-center md:text-left">
            @include('front.layouts.breadcrumb', [
                'title' => $blog['title']
            ])
        </div>
    </div>

    <div class="py-10">
        <h3 class="text-center text-2xl mb-5 font-semibold">{{$blog['title']}}</h3>

        <div class="dynamic_style">
            {!! $blog->description !!}
        </div>
    </div>

    @if(count($related_blogs))
    <div class="py-10">
        <h3 class="text-center text-lg mb-5">Related Blogs</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach ($related_blogs as $related_blog)
                @include('front.layouts.blog-loop', [
                    'blog' => $related_blog
                ])
            @endforeach
        </div>
    </div>
    @endif
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
				"name":"Articles",
				"@id":"{{route('blogs')}}"
			},
			"@type":"ListItem",
			"position":"2"
		},
		{
			"item":{
				"name":"{{$blog->title}}",
				"@id":"{{$blog->route}}"
			},
			"@type":"ListItem",
			"position":"3"
		}
	]
	}
</script>

<script type="application/ld+json">
	{
	"@context": "http://schema.org/",
	"author": {
	  "@type": "Person",
	  "name": "{{env('APP_NAME')}}",
	  "url":"{{url('/')}}"
	},
	"publisher": {
		"name":"{{$web['title'] ?? env('APP_NAME')}}",
		"@type": "Organization",
		"url": "{{url('/')}}",
		"sameAs": [
			"{{env('SEO_FACEBOOK_LINK')}}",
            "{{env('SEO_YOUTOBE_LINK')}}"
		],
		"logo": {
			"@type": "ImageObject",
			"url": "{{$web['logo'] ?? ''}}"
		},
		"contactPoint": [{
			"@type": "ContactPoint",
			"telephone": "{{$web['phone'] ?? ''}}",
			"contactType": "customer service"
		}]
	},
	"mainEntityOfPage": {
		"@type": "WebPage",
		"@id": "{{url('/')}}"
	},
	"headline": "{{ $blog->title }}",
	"name": "{{$blog->title}}",
	"image": "{{ $blog->img_paths['original'] }}",
	"description": "{{str_replace('"', '', preg_replace("/\r|\n/", " ", $blog->description))}}",
	"datePublished": "{{$blog->created_at}}",
	"dateModified": "{{$blog->updated_at}}"
	}
</script>
@endsection
