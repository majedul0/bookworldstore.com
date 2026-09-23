<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{route('homepage')}}</loc>
    </url>
    <url>
        <loc>{{route('blogs')}}</loc>
    </url>
    @foreach ($pages as $page)
        <url>
            <loc>{{$page->route}}</loc>
        </url>
    @endforeach
</urlset>
