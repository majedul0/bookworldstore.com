<div class="bg-white">
    <a href="{{route('blog', $blog['id'])}}" class="border inline-block">
        <img src="{{$blog['img_paths']['medium']}}" alt="{{$blog['title']}}">
    </a>

    <div class="p-2">
        <h5>{{$blog['title']}}</h5>
        <p>{{$blog['short_description'] ?? $blog['title']}}</p>

        <a href="{{route('blog', $blog['id'])}}" class="bg-[#01425f] text-white hover:bg-[#01425f] px-4 py-2 rounded-lg inline-block mt-4">Read More</a>
    </div>
</div>
