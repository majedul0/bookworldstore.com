<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        @foreach ($products as $product)
            <title>{{$product->Name()}}</title>
            <link>{{$product->Route()}}</link>
            <description>
                {{app()->getLocale() == 'en' ? $product->description : $product->oDescription}}
            </description>

            <item>
                <g:id>{{$product->id}}</g:id>
                <g:title>{{$product->Name()}}</g:title>
                <g:description>
                    {{$product->Route()}}
                </g:description>
                <g:link>
                    {{$product->Route()}}
                </g:link>
                <g:image_link>{{$product->img_paths['original']}}</g:image_link>
                <g:condition>new</g:condition>
                <g:availability>in stock</g:availability>
                <g:price>{{$product->first_price}} BDT</g:price>
                <g:shipping>
                <g:country>BD</g:country>
                <g:service>Standard</g:service>
                </g:shipping>

                <g:gtin>{{$product->id}}</g:gtin>
                <g:brand>{{$product->others_arr->brand ?? env('APP_NAME')}}</g:brand>
                <g:mpn>{{$product->id}}/BD</g:mpn>


                {{-- <g:google_product_category>
                Electronics > Video > Televisions > Flat Panel Televisions
                </g:google_product_category>
                <g:product_type>Consumer Electronics > TVs > Flat Panel TVs</g:product_type> --}}
            </item>
        @endforeach
    </channel>
</rss>
