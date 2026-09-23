<div class="item h-full">
    <div class="bg-white rounded-xl overflow-hidden border border-gray-300 transition-shadow duration-300 h-full w-full flex flex-col">
        <div class="relative">
            <div class="">
                <a href="{{route('product', $product->id)}}"><img src="{{$product->img_paths['medium']}}" alt="{{$product->title}}" class=""></a>
            </div>
        </div>
        <div class="py-5 px-2 md:px-3 flex flex-col flex-1">
            <a href="{{route('product', $product->id)}}">
                <h3 title="{{$product->title}}" class="text-gray-800 font-semibold text-xs md:text-lg mb-2 leading-tight line-clamp-1 text-center">
                    {{$product->title}}
                </h3>
            </a>
            <div class="flex justify-center items-center gap-2 mb-2">
                <div class="flex items-center gap-1">
                    <svg class="w-3 h-3 md:w-5 md:h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                    </svg>
                    <span class="text-gray-700 text-[11px] md:text-base font-semibold">{{number_format($product->average_rating ?? 5, 2)}}</span>
                </div>
                <span class="text-gray-400">|</span>
                <div class="flex items-center gap-1">
                    <div class="">
                        <div class="overflow-hidden">
                            <img src="{{ asset('img/checked.png') }}" alt="verified" width="16" height="16" class="w-[9px] h-[9px] md:w-4 md:h-4 shrink-0 object-contain float-left inline-block mr-1 mt-0.5 md:mt-0" style="width: unset">
                            <span class="text-gray-600 text-[11px] md:text-sm float-left inline-block">{{number_format($product->total_review ?? 0)}} Reviews</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-1 md:gap-2 mb-4 mt-auto">
                @if($product->regular_price)
                <span class="text-gray-400 line-through text-xs md:text-sm">৳{{number_format($product->regular_price)}}</span>
                @endif
                <span class="text-gray-900 font-bold text-base md:text-xl">৳{{number_format($product->sale_price)}}</span>
            </div>
            @if($product->type == 'Variable')
            <a href="{{route('product', $product->id)}}"  class="w-full inline-block text-center bg-[#2E5E99] hover:bg-[#2E5E99]/80 text-white font-bold py-2 md:py-3 text-sm md:text-base rounded-lg transition-colors duration-200">
                SELECT OPTION
            </a>
            @else
            <button onclick="addToCart({{$product->id}})" class="w-full inline-block text-center bg-[#2E5E99] hover:bg-[#2E5E99]/80 text-white font-bold py-2 md:py-3 text-sm md:text-base rounded-lg transition-colors duration-200">
                ADD TO CART
            </button>
            @endif
        </div>
    </div>
</div>
