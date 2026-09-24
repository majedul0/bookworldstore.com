<!doctype html>
<html>

@php
    // $categories = App\Models\Product\Category::with('Categories', 'Categories.Categories')->where('for', 'product')->where('category_id', null)->active()->get();
    $cart_summary = App\Repositories\CartRepo::summary();
    $main_menu = cache()->remember('main_menu', (60 * 60 * 24 * 90), function(){
        return App\Models\Menu::with('SingleMenuItems', 'SingleMenuItems.Page', 'SingleMenuItems.Category', 'MenuItems', 'MenuItems', 'MenuItems.Category')->where('name', 'Main Menu')->first();
    });

    $categories = cache()->remember('homepage_categories', (60 * 60 * 24 * 90), function(){
        return App\Models\Product\Category::where('for', 'product')->where('category_id', null)->active()->select('id', 'title', 'slug')->get();
    });

    $widgets = cache()->remember('footer_widgets', (60 * 60 * 24 * 90), function(){
        return App\Models\Widget::with('Menu', 'Menu.SingleMenuItems')->where('status', 1)->where('placement', 'Footer')->orderBy('position')->get();
    });
    $socials = Info::SettingsGroupKey('social');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/front/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <link rel="shortcut icon" href="{{$settings_g['favicon'] ?? asset('assets/img/favicon.ico')}}" type="image/x-icon">

    @yield('head')
</head>

<body>
    <!-- Top Bar -->
    <div class="bg-black py-2 text-white">
        <div class="container mx-auto flex items-center justify-between px-4 text-sm">
            @php
                $announcement_parts = array_filter(array_map('trim', explode('|', $settings_g['announcement_text'] ?? '')));
            @endphp
            @if(count($announcement_parts))
                <div class="mr-4 flex-1 overflow-hidden">
                    <div class="marquee-track inline-flex whitespace-nowrap">
                        @for ($i = 0; $i < 2; $i++)
                            <span class="marquee-content" @if($i > 0) aria-hidden="true" @endif>
                                @foreach ($announcement_parts as $part)
                                    {{ $part }} <span class="mx-6 text-gray-500">•</span>
                                @endforeach
                            </span>
                        @endfor
                    </div>
                </div>
            @endif
            <div class="flex items-center gap-4 shrink-0">
                @if ($socials['facebook'] ?? null)
                <a href="{{ $socials['facebook'] }}" class="transition hover:text-gray-300"><i class="fab fa-facebook"></i></a>
                @endif
                @if ($socials['youtube'] ?? null)
                <a href="{{$socials['youtube']}}" class="transition hover:text-gray-300"><i class="fab fa-youtube"></i></a>
                @endif
                @if ($socials['tiktok'] ?? null)
                <a href="{{$socials['tiktok']}}" class="transition hover:text-gray-300"><i class="fab fa-tiktok"></i></a>
                @endif
                @if ($socials['whatsapp'] ?? null)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socials['whatsapp']) }}" target="_blank" class="transition hover:text-gray-300"><i class="fab fa-whatsapp"></i></a>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{route('homepage')}}"><img class="h-12 md:h-16" src="{{$settings_g['logo'] ?? ''}}" alt=""></a>
                </div>

                <!-- Mobile Search (next to logo) -->
                <form action="{{route('search')}}" method="GET" class="relative mx-3 flex-1 lg:hidden pr-1">
                    <input type="search" name="search" placeholder="Search.." class="w-full rounded border border-gray-200 px-4 py-1.5 text-sm text-black/90 outline-none">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500"><i class="fas fa-search"></i></button>
                </form>

                <!-- Desktop Menu -->
                <div class="hidden flex-col gap-1 lg:flex w-full max-w-[650px]">
                    <form action="{{route('search')}}" method="GET" class="relative w-full">
                        <input type="search" name="search" placeholder="Search.." class="w-full rounded border border-gray-200 px-5 py-1 text-base text-black/90 outline-none">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"><i class="fas fa-search"></i></button>
                    </form>
                    <div class="flex items-center space-x-8">
                        @if($main_menu)
                        @foreach ($main_menu->SingleMenuItems as $menu_item)
                        @if(count($menu_item->Items))
                            <div class="dropdown relative">
                                <button type="button" class="dropdown-toggle flex cursor-pointer items-center font-medium text-gray-700 transition hover:text-black">
                                    {{$menu_item->menu_info['text']}} <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                </button>
                                <div class="dropdown-menu absolute left-0 top-full hidden w-48 rounded-md bg-white py-2 shadow-lg">
                                    <a href="{{$menu_item->menu_info['url']}}" class="block px-4 py-2 font-semibold text-gray-700 hover:bg-gray-100 hover:text-black">{{$menu_item->menu_info['text']}}</a>
                                    @foreach ($menu_item->Items as $item)
                                        <a href="{{$item->menu_info['url']}}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-black">{{$item->menu_info['text']}}</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{$menu_item->menu_info['url']}}" class="font-medium text-gray-700 transition hover:text-black">{{$menu_item->menu_info['text']}}</a>
                        @endif
                        @endforeach
                        @else
                        <p>Please create "Main Menu"</p>
                        @endif
                    </div>
                </div>

                <!-- Cart and Mobile Menu Button -->
                <div class="flex items-center gap-4">
                    <a href="{{route('cart')}}" class="relative">
                        <i class="fas fa-shopping-cart text-xl text-gray-700"></i>
                        <span class="absolute -right-2 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-black text-[10px] text-white top_cart_count">{{$cart_summary['count']}}</span>
                    </a>
                    <a href="{{route('cart')}}" class="font-medium text-gray-700 top_cart_summary">{{number_format($cart_summary['product_total'])}}৳</a>
                    <a href="#" class="text-2xl text-gray-700 lg:hidden" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!--
    hidden
    -->
    <!-- Mobile Menu -->
    <div class="mobile-menu fixed left-0 top-0 z-50 h-full w-80 overflow-y-auto bg-white" id="mobileMenu">
        <div class="p-6">
            <div class="mb-8 flex items-center justify-between">
                <span class="text-2xl font-bold text-black">Menu</span>
                <a href="#" class="text-2xl text-gray-700" id="closeMobileMenu">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <nav class="space-y-4">
                @if($main_menu)
                @foreach ($main_menu->SingleMenuItems as $menu_item)
                @if(count($menu_item->Items))
                <div>
                    <button type="button" class="mobile-dropdown-toggle flex w-full items-center justify-between border-b border-b-gray-300 py-2 text-left font-medium text-gray-700 hover:text-black">
                       {{$menu_item->menu_info['text']}} <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="mobile-dropdown-menu mt-2 hidden space-y-2 pl-4">
                        <a href="{{$menu_item->menu_info['url']}}" class="block py-1 font-semibold text-gray-700 hover:text-black">{{$menu_item->menu_info['text']}}</a>
                        @foreach ($menu_item->Items as $item)
                        <a href="{{$item->menu_info['url']}}" class="block py-1 text-gray-600 hover:text-black">{{$item->menu_info['text']}}</a>
                        @endforeach
                    </div>
                </div>
                @else
                <a href="{{$menu_item->menu_info['url']}}" class="block border-b border-b-gray-300 py-2 font-medium text-gray-700 hover:text-black">{{$menu_item->menu_info['text']}}</a>
                @endif
                @endforeach
                @else
                <p>Please create "Main Menu"</p>
                @endif
            </nav>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="fixed inset-0 z-40 hidden bg-black/50" id="mobileMenuOverlay"></div>

    <!-- Fixed Social Media Icons -->
    <div class="social-fixed flex flex-col gap-3 rounded-l-lg bg-white p-2 md:p-3 shadow-lg">
        @if ($socials['facebook'] ?? null)
        <a href="{{$socials['facebook']}}" class="transform text-xl md:text-2xl text-black transition hover:scale-110 hover:text-gray-700">
            <i class="fab fa-facebook"></i>
        </a>
        @endif
        @if ($socials['youtube'] ?? null)
        <a href="{{$socials['youtube']}}" class="transform text-xl md:text-2xl text-pink-600 transition hover:scale-110 hover:text-pink-700">
            <i class="fab fa-youtube"></i>
        </a>
        @endif
        @if ($socials['tiktok'] ?? null)
        <a href="{{$socials['tiktok']}}" class="transform text-xl md:text-2xl text-gray-800 transition hover:scale-110 hover:text-gray-900">
            <i class="fab fa-tiktok"></i>
        </a>
        @endif
        @if ($socials['whatsapp'] ?? null)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socials['whatsapp']) }}" target="_blank" class="transform text-xl md:text-2xl text-green-600 transition hover:scale-110 hover:text-green-700">
            <i class="fab fa-whatsapp"></i>
        </a>
        @endif
    </div>

    {{-- <div class="bg-yellow-50 py-2">
        <div class="container mx-auto">
            <div class="flex items-center justify-around">
                <div class="flex items-center gap-1">
                    <svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-4">
                        <path d="M4.3817 19.5859C4.3817 19.3617 4.3568 19.2621 4.2572 19.2621L3.68449 19.5361C3.68449 19.4115 3.60979 19.3368 3.48529 19.287L3.28609 19.2621C3.11178 19.2621 3.03708 19.287 2.78808 19.4364C2.71338 19.287 2.61378 19.1127 2.53907 18.9633C1.89166 17.7183 1.21935 15.8508 0.920549 15.0291C0.771146 14.6058 0.621744 13.7343 0.447441 12.4145C0.646644 12.539 0.796047 12.5888 0.870748 12.5888C0.97035 12.5888 1.09485 12.4145 1.19445 12.0659C1.24425 12.1406 1.34386 12.1655 1.46836 12.1655C1.54306 12.1655 1.64266 12.1406 1.69246 12.0659L2.09087 11.4683L2.53907 11.6177H2.56398C2.61378 11.6177 2.68848 11.543 2.81298 11.4683C2.93748 11.3936 3.03708 11.3438 3.11178 11.3438L3.18649 11.3687C3.58489 11.5679 3.8588 11.9165 3.9833 12.4643C4.2821 13.7343 4.55601 14.3568 4.87971 14.3568C5.15362 14.3568 5.52712 14.0331 5.92553 13.4106C6.32394 12.788 6.72234 11.9414 7.17055 10.9205C7.19545 11.1197 7.22035 11.2193 7.27015 11.2193C7.41955 11.2193 7.79306 10.3478 8.71437 8.87868C10.0839 6.66254 13.4703 2.40458 14.3419 1.80697C14.9893 1.35876 15.4873 0.935453 15.8359 0.561947C15.7861 0.810951 15.7363 0.985254 15.7363 1.05995C15.7363 1.13466 15.7861 1.15956 15.8359 1.15956L16.5331 0.810951V0.910552C16.5331 1.03505 16.558 1.10976 16.6327 1.10976C16.7323 1.10976 17.1307 0.711349 17.1805 0.561947L17.1307 0.910552L17.9773 0.412545L17.7781 0.860752C18.0271 0.686449 18.2263 0.586847 18.3508 0.586847C18.4753 0.586847 18.55 0.786051 18.55 0.910552C18.55 1.10976 18.3757 1.38366 18.1018 1.73227C17.803 2.13067 17.056 2.90258 14.815 5.46733C13.8439 6.56294 9.53609 12.4145 8.71437 13.809L7.17055 16.4235C6.49824 17.544 6.07493 18.2661 5.85083 18.54C5.62672 18.8139 5.35282 19.0878 5.02911 19.3368L4.80501 19.2123L4.60581 19.3368L4.3817 19.5859Z" fill="#79C34E"></path>
                    </svg>
                    <p class="text-base font-light">COD Available</p>
                </div>
                <div class="flex items-center gap-1">
                    <svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-4">
                        <path d="M4.3817 19.5859C4.3817 19.3617 4.3568 19.2621 4.2572 19.2621L3.68449 19.5361C3.68449 19.4115 3.60979 19.3368 3.48529 19.287L3.28609 19.2621C3.11178 19.2621 3.03708 19.287 2.78808 19.4364C2.71338 19.287 2.61378 19.1127 2.53907 18.9633C1.89166 17.7183 1.21935 15.8508 0.920549 15.0291C0.771146 14.6058 0.621744 13.7343 0.447441 12.4145C0.646644 12.539 0.796047 12.5888 0.870748 12.5888C0.97035 12.5888 1.09485 12.4145 1.19445 12.0659C1.24425 12.1406 1.34386 12.1655 1.46836 12.1655C1.54306 12.1655 1.64266 12.1406 1.69246 12.0659L2.09087 11.4683L2.53907 11.6177H2.56398C2.61378 11.6177 2.68848 11.543 2.81298 11.4683C2.93748 11.3936 3.03708 11.3438 3.11178 11.3438L3.18649 11.3687C3.58489 11.5679 3.8588 11.9165 3.9833 12.4643C4.2821 13.7343 4.55601 14.3568 4.87971 14.3568C5.15362 14.3568 5.52712 14.0331 5.92553 13.4106C6.32394 12.788 6.72234 11.9414 7.17055 10.9205C7.19545 11.1197 7.22035 11.2193 7.27015 11.2193C7.41955 11.2193 7.79306 10.3478 8.71437 8.87868C10.0839 6.66254 13.4703 2.40458 14.3419 1.80697C14.9893 1.35876 15.4873 0.935453 15.8359 0.561947C15.7861 0.810951 15.7363 0.985254 15.7363 1.05995C15.7363 1.13466 15.7861 1.15956 15.8359 1.15956L16.5331 0.810951V0.910552C16.5331 1.03505 16.558 1.10976 16.6327 1.10976C16.7323 1.10976 17.1307 0.711349 17.1805 0.561947L17.1307 0.910552L17.9773 0.412545L17.7781 0.860752C18.0271 0.686449 18.2263 0.586847 18.3508 0.586847C18.4753 0.586847 18.55 0.786051 18.55 0.910552C18.55 1.10976 18.3757 1.38366 18.1018 1.73227C17.803 2.13067 17.056 2.90258 14.815 5.46733C13.8439 6.56294 9.53609 12.4145 8.71437 13.809L7.17055 16.4235C6.49824 17.544 6.07493 18.2661 5.85083 18.54C5.62672 18.8139 5.35282 19.0878 5.02911 19.3368L4.80501 19.2123L4.60581 19.3368L4.3817 19.5859Z" fill="#79C34E"></path>
                    </svg>
                    <p class="text-base font-light">COD Available</p>
                </div>
                <div class="flex items-center gap-1">
                    <svg width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-4">
                        <path d="M4.3817 19.5859C4.3817 19.3617 4.3568 19.2621 4.2572 19.2621L3.68449 19.5361C3.68449 19.4115 3.60979 19.3368 3.48529 19.287L3.28609 19.2621C3.11178 19.2621 3.03708 19.287 2.78808 19.4364C2.71338 19.287 2.61378 19.1127 2.53907 18.9633C1.89166 17.7183 1.21935 15.8508 0.920549 15.0291C0.771146 14.6058 0.621744 13.7343 0.447441 12.4145C0.646644 12.539 0.796047 12.5888 0.870748 12.5888C0.97035 12.5888 1.09485 12.4145 1.19445 12.0659C1.24425 12.1406 1.34386 12.1655 1.46836 12.1655C1.54306 12.1655 1.64266 12.1406 1.69246 12.0659L2.09087 11.4683L2.53907 11.6177H2.56398C2.61378 11.6177 2.68848 11.543 2.81298 11.4683C2.93748 11.3936 3.03708 11.3438 3.11178 11.3438L3.18649 11.3687C3.58489 11.5679 3.8588 11.9165 3.9833 12.4643C4.2821 13.7343 4.55601 14.3568 4.87971 14.3568C5.15362 14.3568 5.52712 14.0331 5.92553 13.4106C6.32394 12.788 6.72234 11.9414 7.17055 10.9205C7.19545 11.1197 7.22035 11.2193 7.27015 11.2193C7.41955 11.2193 7.79306 10.3478 8.71437 8.87868C10.0839 6.66254 13.4703 2.40458 14.3419 1.80697C14.9893 1.35876 15.4873 0.935453 15.8359 0.561947C15.7861 0.810951 15.7363 0.985254 15.7363 1.05995C15.7363 1.13466 15.7861 1.15956 15.8359 1.15956L16.5331 0.810951V0.910552C16.5331 1.03505 16.558 1.10976 16.6327 1.10976C16.7323 1.10976 17.1307 0.711349 17.1805 0.561947L17.1307 0.910552L17.9773 0.412545L17.7781 0.860752C18.0271 0.686449 18.2263 0.586847 18.3508 0.586847C18.4753 0.586847 18.55 0.786051 18.55 0.910552C18.55 1.10976 18.3757 1.38366 18.1018 1.73227C17.803 2.13067 17.056 2.90258 14.815 5.46733C13.8439 6.56294 9.53609 12.4145 8.71437 13.809L7.17055 16.4235C6.49824 17.544 6.07493 18.2661 5.85083 18.54C5.62672 18.8139 5.35282 19.0878 5.02911 19.3368L4.80501 19.2123L4.60581 19.3368L4.3817 19.5859Z" fill="#79C34E"></path>
                    </svg>
                    <p class="text-base font-light">COD Available</p>
                </div>
            </div>
        </div>
    </div> --}}

    @yield('master')

    <!-- Footer -->
    <footer class="bg-gray-900 py-12 text-white">
        <div class="container mx-auto px-4">
            <div class="mb-8 grid gap-8 md:grid-cols-4">
                @foreach ($widgets as $widget)
                    @if($widget->type == 'Social Links')
                        <div>
                            <h4 class="mb-4 text-lg font-semibold">{{$widget->title}}</h4>
                            <ul class="space-y-2 text-gray-400">
                                <li><i class="fas fa-phone mr-2"></i> {{$settings_g['mobile_number'] ?? ''}}</li>
                                <li><i class="fas fa-envelope mr-2"></i> {{$settings_g['email'] ?? ''}}</li>
                                <li class="mt-4 flex gap-3">
                                    @if ($socials['facebook'] ?? null)
                                    <a href="{{$socials['facebook']}}" class="transition hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                                    @endif
                                    @if ($socials['youtube'] ?? null)
                                    <a href="{{$socials['youtube']}}" class="transition hover:text-white"><i class="fab fa-youtube text-xl"></i></a>
                                    @endif
                                    @if ($socials['tiktok'] ?? null)
                                    <a href="{{$socials['tiktok']}}" class="transition hover:text-white"><i class="fab fa-tiktok text-xl"></i></a>
                                    @endif
                                    @if ($socials['whatsapp'] ?? null)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socials['whatsapp']) }}" target="_blank" class="transition hover:text-white"><i class="fab fa-whatsapp text-xl"></i></a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    @elseif($widget->type == 'Info')
                        <div>
                            <a href="{{route('homepage')}}"><img class="h-16" src="{{$settings_g['logo'] ?? ''}}" alt=""></a>
                            <div class="text-gray-400">{!! $widget->text !!}</div>
                        </div>
                    @elseif($widget->type == 'Menu')
                        <div>
                            <h4 class="mb-4 text-lg font-semibold">{{$widget->title}}</h4>
                            @if($widget->type == 'Menu' && $widget->Menu)
                            <ul class="space-y-2 text-gray-400">
                                @foreach ($widget->Menu->SingleMenuItems as $item)
                                <li><a href="{{$item->menu_info['url']}}" class="transition hover:text-white">{{$item->menu_info['text']}}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    @else
                        <h4 class="mb-4 text-lg font-semibold">{{$widget->title}}</h4>
                        <div class="text-gray-400">{!! $widget->text !!}</div>
                    @endif
                @endforeach
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>{!! $settings_g['copyright'] ?? '' !!}</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.3.0/dist/sweetalert2.all.min.js"></script>

    <script src="{{ asset('front/js/main.js') }}?c=3"></script>

    @if(session('success-alert'))
    <script>
        cAlert('success', "{{session('success-alert')}}");
    </script>
    @endif

    @if(session('error-alert'))
    <script>
        cAlert('error', "{{session('error-alert')}}");
    </script>
    @endif

    @yield('footer')

    <script>
        function addToCart(product_id, type = 'common'){
            let single_cart_quantity = $('#quantityInput').val();
            let product_variation = null;
            if(type == 'single'){
                product_variation = $('.product_variation:checked').val();
            }

            $.ajax({
                url: '{{route("cart.add")}}',
                method: 'POST',
                dataType: 'JSON',
                data: {_token: '{{csrf_token()}}', product_id, product_variation, quantity: single_cart_quantity},
                success: function(result){
                    cAlert('success', "Card added success!");
                    $('.top_cart_count').html(result.cart_count);
                    $('.top_cart_summary').html(result.cart_amount);
                },
                error: function(){
                    cAlert('success', "Something wrong!");
                }
            });
        }
    </script>
</body>

</html>
