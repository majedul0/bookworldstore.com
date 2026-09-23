<li class="border-b-2 py-3">
    <div class="grid grid-cols-4 gap-3">
        <div>
            <img src="{{$cart['product']['img_paths']['small']}}" class="w-16 h-16 rounded object-cover border" alt="{{$cart['product']['title']}}">
        </div>

        <div class="flex-grow text-left col-span-3">
            <a href="" class="mt-2 block truncate">{{$cart->Product->title}}</a>
            <p class="text-xs font-[300] text-gray-500">{{$cart->quantity}} x ৳ {{$cart->ProductData->sale_price}}</p>
        </div>
    </div>
</li>
