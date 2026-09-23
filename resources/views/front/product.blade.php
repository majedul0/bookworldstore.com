@extends('front.layouts.master')
@section('head')
    @include('meta::manager', [
        'title' => $product->title . ' - ' . env('APP_NAME'),
        'description' => $product->meta_description ? $product->meta_description : $product->title,
        'image' => $product->img_paths['original'],
        'keywords' => $product->meta_tags ?? $product->title,
    ])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
@endsection

@section('master')
    @php
        $contents = [
            'id' => $product->id,
            'quantity' => 1
        ];
        $content_ids = [$product->id];
    @endphp

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-5 md:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Left Column - Image Gallery -->
            <div class="space-y-4 lg:sticky lg:top-8 lg:self-start">
                <!-- Main Image -->
                <div class="image-container rounded-lg overflow-hidden aspect-square flex items-center justify-center"
                    id="mainImageContainer">
                    <img id="mainImage" src="{{$product->img_paths['original']}}" alt="{{$product->title}}" class="w-full h-full object-contain">
                </div>

                <!-- Thumbnail Gallery -->
                <div class="grid grid-cols-8 gap-2">
                    <div class="thumbnail active border-2 border-green-500 rounded-lg overflow-hidden aspect-square"
                        data-image="{{$product->img_paths['original']}}">
                        <img src="{{$product->img_paths['small']}}" alt="{{$product->title}}" class="w-full h-full object-contain">
                    </div>

                    @foreach ($product->Gallery as $gallery)
                    <div class="thumbnail border-2 border-gray-200 rounded-lg overflow-hidden aspect-square bg-white"
                        data-image="{{$gallery->paths['original']}}">
                        <img src="{{$gallery->paths['small']}}" alt="{{$product->title}}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column - Product Details -->
            <div class="space-y-3 lg:space-y-6">
                <div>
                    <h1 class="text-xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 md:mb-3">
                        {{$product->title}}
                    </h1>
                    {{-- <p class="text-green-600 font-medium mb-2 md:mb-4">
                        Supports real hair recovery in Stages 1-5 of hair loss
                    </p> --}}

                    <!-- Reviews -->
                    <div class="flex items-center space-x-2 md:space-x-3 mb-2 md:mb-4">
                        <div class="flex star-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            {{-- <i class="fas fa-star-half-alt"></i> --}}
                        </div>
                        {{-- <a href="#" class="text-blue-600 hover:underline">3802 Reviews</a> --}}
                    </div>
                </div>

                <!-- Price Section -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center space-x-4 mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-gray-900">৳{{$product->prices['sale_price']}}</span>
                        @if($product->prices['regular_price'] > 0)
                        <span class="text-xl original-price">৳{{$product->prices['regular_price']}}</span>
                        @endif
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold badge">
                            Lowest price <i class="fas fa-info-circle"></i>
                        </span>
                    </div>
                    {{-- <p class="text-sm text-gray-600">Incl. of all taxes</p> --}}
                </div>

                @if($product->type == 'Variable')
                <div>
                    <h3 class="text-lg font-semibold mb-3">Select Variant</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $variations = $product->VariableProductData;
                        @endphp
                        @foreach ($variations as $variation)
                            <label class="variant-option relative bg-white border-2 border-gray-200 rounded-lg px-4 py-3 cursor-pointer hover:border-green-500 hover:shadow-md transition-all">
                                <input type="radio" class="product_variation" name="product_variation" {{$loop->index == 0 ? 'checked' : ''}} value="{{$variation->id}}">
                                @if($variation->regular_price)
                                <div class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 rounded-bl-lg rounded-tr-lg text-xs font-bold">
                                    Save {{$variation->regular_price - $variation->sale_price}}/-
                                </div>
                                @endif
                                <div class="mt-2">
                                    <div class="text-2xl font-bold text-gray-900 mb-2">{{$variation->attribute_items_string}}</div>
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-lg font-semibold text-gray-900">৳{{$variation->sale_price}}</span>
                                        @if($variation->regular_price)
                                        <span class="text-sm original-price">৳{{$variation->regular_price}}</span>
                                        @endif
                                    </div>
                                    {{-- <div class="text-green-600 font-medium text-sm">₹3.98 / ml</div> --}}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quantity and Add to Cart -->
                <div class="space-y-4">
                    <div
                        class="flex items-center justify-between bg-white rounded-lg border border-gray-200 p-2 md:p-4">
                        <div class="flex items-center space-x-4">
                            <span class="font-semibold text-gray-700">Quantity:</span>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button
                                    class="px-3 md:px-4 py-1 md:py-2 hover:bg-gray-100 transition-colors cursor-pointer"
                                    id="decreaseBtn">
                                    <i class="fas fa-minus text-gray-600"></i>
                                </button>
                                <input type="text" value="1"
                                    class="w-14 md:w-16 text-center border-x border-gray-300 py-1 md:py-2 font-semibold"
                                    id="quantityInput" readonly>
                                <button
                                    class="px-3 md:px-4 py-1 md:py-2 hover:bg-gray-100 transition-colors cursor-pointer"
                                    id="increaseBtn">
                                    <i class="fas fa-plus text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="flex star-rating text-sm">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                {{-- <i class="fas fa-star-half-alt"></i> --}}
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="font-semibold">(5.0)</span>
                        </div>
                    </div>

                    <button type="button" class="cursor-pointer w-full bg-[#2E5E99] hover:bg-[#2E5E99]/80 text-white font-bold py-3 md:py-4 rounded-lg text-lg transition-colors shadow-md hover:shadow-lg" onclick="addToCart('{{$product->id}}', 'single')">
                        ADD TO CART
                    </button>
                </div>

                {{-- <!-- Product Features -->
                <div class="grid grid-cols-5 gap-3 pt-4">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-2 bg-green-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-leaf text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-700">Best of<br>Ayurveda</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-2 bg-green-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-hand-holding-heart text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-700">Dermats<br>Approved</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-2 bg-green-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-paw text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-700">Cruelty<br>Free</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-2 bg-green-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-ban text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-700">No<br>Toxins</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-2 bg-green-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-certificate text-green-600 text-2xl"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-700">FDA<br>Approved</p>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- Product Description Section with Tabs -->
        <div class="mt-8 md:mt-12 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Tab Navigation -->
            <div class="flex flex-wrap border-b border-gray-200 bg-gray-50">
                <button
                    class="cursor-pointer tab-button px-4 md:px-6 py-2 md:py-4 font-semibold text-blue-600 border-b-2 border-blue-600 hover:bg-gray-100 transition-colors"
                    data-tab="description">
                    Description
                </button>
                {{-- <button
                    class="cursor-pointer tab-button px-4 md:px-6 py-2 md:py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-100 transition-colors"
                    data-tab="ingredients">
                    Ingredients
                </button>
                <button
                    class="cursor-pointer tab-button px-4 md:px-6 py-2 md:py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-100 transition-colors"
                    data-tab="howto">
                    How to Use
                </button>
                <button
                    class="cursor-pointer tab-button px-4 md:px-6 py-2 md:py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-100 transition-colors"
                    data-tab="benefits">
                    Benefits
                </button>
                <button
                    class="cursor-pointer tab-button px-4 md:px-6 py-2 md:py-4 font-semibold text-gray-600 border-b-2 border-transparent hover:bg-gray-100 transition-colors"
                    data-tab="recommended">
                    Recommended
                </button> --}}
            </div>

            <!-- Tab Content -->
            <div class="p-5 md:p-8">
                <!-- Description Tab -->
                <div class="tab-content" id="description">
                    <div class="dynamic_style">
                        {!! $product->description !!}
                    </div>

                    @php
                        $total_reviews = $product->total_review;
                        $avg_rating = round($product->average_rating, 1);
                        $bar_colors = [5=>'bg-green-500', 4=>'bg-green-400', 3=>'bg-yellow-400', 2=>'bg-orange-300', 1=>'bg-red-300'];
                    @endphp
                    <div class="boverflow-hidden pt-6 md:pt-10">

                        @if(session('review_submitted'))
                        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700 text-center">
                            Thank you! Your review has been submitted and is pending approval.
                        </div>
                        @endif

                        <!-- ── Summary Row ── -->
                        <div class="flex flex-col items-center sm:flex-row gap-6 py-6 border-b border-gray-100">

                            <!-- Title + Score -->
                            <div class="shrink-0 text-center sm:text-left">
                                <h1 class="text-xl font-bold text-gray-900">Customer Reviews</h1>
                                <div class="flex items-center gap-1 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= round($avg_rating) ? 'text-amber-400' : 'text-gray-300' }} text-base leading-none">★</span>
                                    @endfor
                                    <span class="text-sm font-semibold text-gray-700 ml-1">{{ $avg_rating > 0 ? $avg_rating : '0' }} out of 5</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">Based on {{ $total_reviews }} {{ Str::plural('review', $total_reviews) }}</p>
                            </div>

                            <!-- Divider -->
                            <div class="hidden sm:block w-px self-stretch bg-gray-100"></div>

                            <!-- Bars -->
                            <div class="flex-1 w-full space-y-1.5">
                                @foreach([5,4,3,2,1] as $star)
                                @php
                                    $count = $rating_counts[$star] ?? 0;
                                    $pct = $total_reviews > 0 ? round($count / $total_reviews * 100, 1) : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="text-xs w-16 shrink-0">
                                        @for($i = 1; $i <= 5; $i++)<span class="{{ $i <= $star ? 'text-amber-400' : 'text-gray-300' }}">★</span>@endfor
                                    </span>
                                    <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-full {{ $bar_colors[$star] }} rounded-full" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-6 text-right shrink-0">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>

                            <!-- Write a Review Button -->
                            <div class="shrink-0">
                                <button id="writeBtn" onclick="toggleForm()"
                                    class="cursor-pointer bg-green-500 hover:bg-green-600 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-full transition-all shadow-sm">
                                    Write a review
                                </button>
                            </div>

                        </div>

                        <!-- ── Collapsible Form ── -->
                        <div id="formSection">
                            <div class="px-2 py-7 border-t border-gray-100">
                                <h2 class="text-base font-bold text-gray-800 text-center mb-5">Write a Review</h2>

                                <form id="reviewForm" class="space-y-4" method="POST" action="{{ route('product.review.store', $product->id) }}">
                                    @csrf

                                    <!-- Star Rating -->
                                    <div class="text-center">
                                        <p class="text-sm text-gray-500 mb-1">Rating <span class="text-red-400">*</span></p>
                                        <div class="star-input">
                                            <input type="radio" name="rating" id="s5" value="5" required><label for="s5">★</label>
                                            <input type="radio" name="rating" id="s4" value="4"><label for="s4">★</label>
                                            <input type="radio" name="rating" id="s3" value="3"><label for="s3">★</label>
                                            <input type="radio" name="rating" id="s2" value="2"><label for="s2">★</label>
                                            <input type="radio" name="rating" id="s1" value="1"><label for="s1">★</label>
                                        </div>
                                        @error('rating')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <!-- Review Title -->
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <label class="text-sm text-gray-600 font-medium">Review Title</label>
                                            <span id="titleCount" class="text-xs text-gray-400">0 / 100</span>
                                        </div>
                                        <input type="text" name="title" id="reviewTitle" maxlength="100"
                                            placeholder="Give your review a title"
                                            value="{{ old('title') }}"
                                            oninput="document.getElementById('titleCount').textContent=this.value.length+' / 100'"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                                    </div>

                                    <!-- Review Content -->
                                    <div>
                                        <label class="block text-sm text-gray-600 font-medium mb-1">Review Content <span class="text-red-400">*</span></label>
                                        <textarea name="review" rows="4" placeholder="Start writing here..." required
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 resize-none transition-all">{{ old('review') }}</textarea>
                                        @error('review')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <!-- Name + Email side by side -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-600 font-medium mb-1">Display name <span class="text-red-400">*</span></label>
                                            <input type="text" name="name" placeholder="Display name" required
                                                value="{{ old('name') }}"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                                            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-600 font-medium mb-1">Email address <span class="text-red-400">*</span></label>
                                            <input type="email" name="email" placeholder="Your email address" required
                                                value="{{ old('email') }}"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 placeholder-gray-400 transition-all" />
                                            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <button type="button" onclick="cancelReview()"
                                            class="flex-1 cursor-pointer border border-gray-200 text-gray-500 rounded-full py-2.5 text-sm font-medium hover:bg-gray-50 transition-all">
                                            Cancel review
                                        </button>
                                        <button type="submit"
                                            class="flex-1 cursor-pointer bg-green-500 hover:bg-green-600 active:scale-95 text-white rounded-full py-2.5 text-sm font-semibold transition-all shadow-sm">
                                            Submit Review
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- ── Approved Reviews List ── -->
                        @if($reviews->count())
                        <div class="mt-6 space-y-5 border-t border-gray-100 pt-6">
                            @foreach($reviews as $review)
                            <div class="flex gap-4">
                                <div class="shrink-0 w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm uppercase">
                                    {{ substr($review->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-sm font-semibold text-gray-800">{{ $review->name }}</span>
                                        <span class="text-amber-400 text-xs leading-none">
                                            @for($i=1;$i<=5;$i++)<span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }}">★</span>@endfor
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($review->title)
                                    <p class="text-sm font-medium text-gray-700 mt-1">{{ $review->title }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $review->review }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-sm text-gray-400 mt-8 pb-4">No reviews yet. Be the first to review!</p>
                        @endif

                    </div>
                </div>

                {{-- <!-- Ingredients Tab -->
                <div class="tab-content hidden" id="ingredients">
                    <h2 class="text-2xl font-bold mb-4">Key Ingredients</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Activated Charcoal</h4>
                            <p class="text-gray-700 text-sm">Deep detox and pore cleansing</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Neem & Tea Tree Oil</h4>
                            <p class="text-gray-700 text-sm">Antibacterial and anti-acne properties</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Aloe Vera</h4>
                            <p class="text-gray-700 text-sm">Soothes and hydrates skin</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Fruit Extracts</h4>
                            <p class="text-gray-700 text-sm">Pomegranate, Carrot, Lemon, Apple, Dragon Fruit</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Coconut Water</h4>
                            <p class="text-gray-700 text-sm">Refreshes and revitalizes</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-bold text-lg mb-2">Curcumin Extract</h4>
                            <p class="text-gray-700 text-sm">Anti-inflammatory benefits</p>
                        </div>
                    </div>
                </div>

                <!-- How to Use Tab -->
                <div class="tab-content hidden" id="howto">
                    <h2 class="text-2xl font-bold mb-4">How to Use</h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                1</div>
                            <div>
                                <h4 class="font-semibold mb-1">Wet Your Face</h4>
                                <p class="text-gray-700">Splash your face with lukewarm water to open up pores.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                2</div>
                            <div>
                                <h4 class="font-semibold mb-1">Apply Facewash</h4>
                                <p class="text-gray-700">Take a small amount and gently massage in circular motions.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                3</div>
                            <div>
                                <h4 class="font-semibold mb-1">Rinse Thoroughly</h4>
                                <p class="text-gray-700">Rinse with cool water and pat dry with a clean towel.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                4</div>
                            <div>
                                <h4 class="font-semibold mb-1">Use Daily</h4>
                                <p class="text-gray-700">For best results, use twice daily - morning and night.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefits Tab -->
                <div class="tab-content hidden" id="benefits">
                    <h2 class="text-2xl font-bold mb-4">Key Benefits</h2>
                    <ul class="space-y-3">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <span class="text-gray-700">Deep cleanses and detoxifies pores</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <span class="text-gray-700">Reduces acne and prevents breakouts</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <span class="text-gray-700">Controls excess oil production</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <span class="text-gray-700">Brightens and evens skin tone</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <span class="text-gray-700">Provides antioxidant protection</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <span class="text-gray-700">Soothes inflammation and redness</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-1"></i>
                            <span class="text-gray-700">Suitable for all skin types, especially oily and
                                acne-prone</span>
                        </li>
                    </ul>
                </div>

                <!-- Recommended Tab -->
                <div class="tab-content hidden" id="recommended">
                    <h2 class="text-2xl font-bold mb-4">Recommended For</h2>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="text-center p-6 bg-gray-50 rounded-lg">
                            <i class="fas fa-user text-4xl text-blue-600 mb-3"></i>
                            <h4 class="font-semibold mb-2">Oily Skin</h4>
                            <p class="text-gray-700 text-sm">Controls excess sebum production</p>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-4xl text-blue-600 mb-3"></i>
                            <h4 class="font-semibold mb-2">Acne-Prone Skin</h4>
                            <p class="text-gray-700 text-sm">Prevents and reduces breakouts</p>
                        </div>
                        <div class="text-center p-6 bg-gray-50 rounded-lg">
                            <i class="fas fa-cloud text-4xl text-blue-600 mb-3"></i>
                            <h4 class="font-semibold mb-2">Dull Skin</h4>
                            <p class="text-gray-700 text-sm">Brightens and revitalizes complexion</p>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <section class="py-10 lg:py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-yellow-300 pb-2">Related Products</h2>
            </div>

            <div class="w-full relative py-3">
                <div class="owl-carousel owl-theme" id="slider1">
                    @foreach ($related_products as $related_product)
                    @include('front.layouts.product-loop', [
                        'product' => $related_product
                    ])
                    @endforeach
                </div>
                <button id="prev1"
                    class="absolute top-1/2 -translate-y-1/2 left-1 hover:bg-green-500 hover:text-black bg-gray-200/60 p-2 rounded-full shadow-md transition-all cursor-pointer opacity-65 duration-300 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <button id="next1"
                    class="absolute top-1/2 -translate-y-1/2 right-1 hover:bg-green-500 hover:text-black bg-gray-200/60 p-2 rounded-full shadow-md transition-all duration-300 z-10 cursor-pointer opacity-65">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Zoom Modal -->
    <div class="zoom-modal" id="zoomModal">
        <img id="zoomedImage" src="" alt="Zoomed Image">
    </div>
@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
    $(document).ready(function () {
        // Initialize Slider 1
        var slider1 = $("#slider1");
        slider1.owlCarousel({
            items: 1,
            loop: true,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            responsive: {
                0: { items: 2, slideBy: 1, margin: 20 },
                600: { items: 2, slideBy: 1, margin: 20 },
                1000: { items: 4, slideBy: 1, margin: 20 }
            }
        });

        // Custom Navigation for Slider 1
        $("#prev1").click(function () {
            slider1.trigger('prev.owl.carousel');
        });
        $("#next1").click(function () {
            slider1.trigger('next.owl.carousel');
        });

        // Initialize Slider 2
        var slider2 = $("#slider2");
        slider2.owlCarousel({
            items: 1,
            loop: true,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            responsive: {
                0: { items: 2, slideBy: 1, margin: 20 },
                600: { items: 2, slideBy: 1, margin: 20 },
                1000: { items: 4, slideBy: 1, margin: 20 }
            }
        });

        // Custom Navigation for Slider 1
        $("#prev2").click(function () {
            slider2.trigger('prev.owl.carousel');
        });
        $("#next2").click(function () {
            slider2.trigger('next.owl.carousel');
        });
    });
</script>

<script>
    // Image Gallery Functionality
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('mainImage');
    const mainImageContainer = document.getElementById('mainImageContainer');
    const zoomModal = document.getElementById('zoomModal');
    const zoomedImage = document.getElementById('zoomedImage');

    // Thumbnail click handler
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active', 'border-green-500'));
            thumbnails.forEach(t => t.classList.add('border-gray-200'));

            // Add active class to clicked thumbnail
            this.classList.add('active', 'border-green-500');
            this.classList.remove('border-gray-200');

            // Update main image
            const imageSrc = this.getAttribute('data-image');
            mainImage.src = imageSrc;
        });
    });

    // Zoom functionality
    mainImageContainer.addEventListener('click', function () {
        zoomedImage.src = mainImage.src;
        zoomModal.classList.add('active');
    });

    zoomModal.addEventListener('click', function () {
        zoomModal.classList.remove('active');
    });

    // Close zoom modal on ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            zoomModal.classList.remove('active');
        }
    });

    // Quantity buttons
    const decreaseBtn = document.getElementById('decreaseBtn');
    const increaseBtn = document.getElementById('increaseBtn');
    const quantityInput = document.getElementById('quantityInput');

    decreaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    increaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    });

    // // Variant Selection
    // const variantOptions = document.querySelectorAll('.variant-option');

    // variantOptions.forEach(option => {
    //     option.addEventListener('click', function () {
    //         // Remove active state from all variants
    //         variantOptions.forEach(opt => {
    //             opt.classList.remove('active', 'bg-green-50', 'border-green-500');
    //             opt.classList.add('bg-white', 'border-gray-200');
    //             // Reset text color for size
    //             const sizeText = opt.querySelector('.text-2xl');
    //             if (sizeText) {
    //                 sizeText.classList.remove('text-green-600');
    //                 sizeText.classList.add('text-gray-900');
    //             }
    //         });

    //         // Add active state to clicked variant
    //         this.classList.add('active', 'bg-green-50', 'border-green-500');
    //         this.classList.remove('bg-white', 'border-gray-200');

    //         // Update size text color
    //         const sizeText = this.querySelector('.text-2xl');
    //         if (sizeText) {
    //             sizeText.classList.add('text-green-600');
    //             sizeText.classList.remove('text-gray-900');
    //         }

    //         // You can add code here to update the main price display based on selected variant
    //         const selectedVariant = this.getAttribute('data-variant');
    //         console.log('Selected variant:', selectedVariant);
    //     });
    // });

    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Remove active state from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('text-blue-600', 'border-blue-600');
                btn.classList.add('text-gray-600', 'border-transparent');
            });

            // Add active state to clicked tab
            this.classList.add('text-blue-600', 'border-blue-600');
            this.classList.remove('text-gray-600', 'border-transparent');

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show target tab content
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });
</script>

<script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.remove('hidden');
    });

    closeMobileMenu.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.add('hidden');
    });

    mobileMenuOverlay.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.add('hidden');
    });

    function toggleMobileDropdown(id) {
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    // Slider Functionality
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');

    function showSlide(n) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('opacity-100'));
        dots.forEach(dot => dot.classList.add('opacity-50'));

        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.remove('opacity-50');
        dots[currentSlide].classList.add('opacity-100');
    }

    function changeSlide(n) {
        showSlide(n);
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    // Auto slide
    setInterval(() => {
        nextSlide();
    }, 5000);

    // Initialize first dot
    showSlide(0);
</script>

<script>
    function toggleForm() {
        const form = document.getElementById('formSection');
        const btn = document.getElementById('writeBtn');
        const isOpen = form.classList.toggle('open');
        btn.textContent = isOpen ? 'Cancel review' : 'Write a review';
    }

    function cancelReview() {
        document.getElementById('reviewForm').reset();
        document.getElementById('titleCount').textContent = '0 / 100';
        const form = document.getElementById('formSection');
        form.classList.remove('open');
        document.getElementById('writeBtn').textContent = 'Write a review';
    }

    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('formSection').classList.add('open');
        document.getElementById('writeBtn').textContent = 'Cancel review';
    });
    @endif
</script>

    <script type="application/ld+json">
        {
            "@context":"https://schema.org",
            "@type":"Product",
            "image":"{{$product['img_paths']['original']}}",
            "name":"{{$product['title']}}",
            "description":"{{ preg_replace("/\r|\n/", " ", (($product->meta_description) ? $product->meta_description : $product['title'])) }}",
            "sku":"code-{{$product->id}}",
            "mpn": "{{$product->id}}",
            "category":"{{count($product->Categories) ? $product->Categories[0]->title : env('APP_NAME')}}",
            "aggregateRating":[
                {
                "@type":"AggregateRating",
                "bestRating":"5",
                "ratingValue":"5",
                "ratingCount":"1",
                "worstRating":"1"
                }
            ],
            "review": [{
            "@type": "Review",
            "reviewRating": {
                "@type": "Rating",
                "ratingValue": "5"
            },
            "author": {
                "@type": "Person",
                "name": "{{env('APP_NAME')}} {{$product->title}}"
            },
            "reviewBody": "I really enjoyed {{$product->title}}."
            }],
            "brand":[
                {
                "@type":"Brand",
                "name":"{{$web['title'] ?? env('APP_NAME')}}"
                }
            ],
            "url":"{{$product->route}}",
            "offers":[
                {
                "@type":"AggregateOffer",
                "seller":{
                    "@type":"Organization",
                    "name":"{{$web['title'] ?? env('APP_NAME')}}"
                },
                "priceCurrency":"BDT",
                "offerCount":1,
                "lowPrice":{{$product->sale_price}},
                "highPrice":{{$product->sale_price}},
                "availability":"https://schema.org/InStock",
                "url":"{{$product->route}}"
                }
            ]
        }
    </script>

    <script>
        let inside_dhaka_delivery_charge = 60;
        let outside_dhaka_delivery_charge = 100;

        // Function to change the main image when a thumbnail is clicked
        function changeImage(element, image) {
            const mainImage = document.getElementById('mainImage');
            mainImage.src = image;
            // Remove zoomed-in class if it exists

            mainImage.classList.remove('zoomed');
            mainImage.style.transform = 'scale(1)';
            zoomedIn = false;
            zoomButton.textContent = 'Zoom';
        }

        // Zoom in and zoom out feature
        document.addEventListener('DOMContentLoaded', function() {
            const zoomButton = document.getElementById('zoomButton');
            let zoomedIn = false;

            // Main Image Hover for zoom
            mainImage.addEventListener('mouseenter', function() {
                mainImage.classList.add('zoomed');
                mainImage.style.transform = 'scale(2)';
                zoomedIn = true;
                zoomButton.textContent = 'Zoom Out';
            });

            mainImage.addEventListener('mouseleave', function() {
                mainImage.classList.remove('zoomed');
                mainImage.style.transform = 'scale(1)';
                zoomedIn = false;
                zoomButton.textContent = 'Zoom';
            });

            // Button event for Zoom
            zoomButton.addEventListener('click', function() {
                if (!zoomedIn) {
                    mainImage.classList.add('zoomed');
                    mainImage.style.transform = 'scale(2)';
                    zoomedIn = true;
                    zoomButton.textContent = 'Zoom Out';
                } else {
                    mainImage.classList.remove('zoomed');
                    mainImage.style.transform = 'scale(1)';
                    zoomedIn = false;
                    zoomButton.textContent = 'Zoom';
                }
            });

            mainImage.addEventListener('mousemove', function(e) {
                if (zoomedIn) {
                    const {
                        left,
                        top,
                        width,
                        height
                    } = mainImage.getBoundingClientRect();
                    const x = (e.clientX - left) / width * 100;
                    const y = (e.clientY - top) / height * 100;
                    mainImage.style.transformOrigin = `${x}% ${y}%`;
                }
            });

        });

        // Product Quantity
        function changeQuantity(amount) {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            let single_product_price_input = $('.single_product_price_input').val();
            let single_product_price_old_input = $('.single_product_price_old_input').val();

            if (!isNaN(currentValue)) {
                currentValue += amount;
                if (currentValue < 1) currentValue = 1;
                quantityInput.value = currentValue;

                $('.single_product_price_total').html(single_product_price_input * currentValue);
                if(single_product_price_old_input){
                    $('.single_product_price_old_total').html(single_product_price_old_input * currentValue);
                    $('.single_product_price_old_total').show();
                }else{
                    $('.single_product_price_old_total').hide();
                }
            }
        }

        // Get Variable price
        $(document).on('change', '.co_radio', function() {
            // Get Data
            let product = "{{ $product->id }}";
            let attribute_values = $("input.co_radio:checked").map(function() {
                return $(this).val();
            });

            let values = attribute_values.get();
            values = values.sort();
            let quantity = $('#quantity').val();

            // Ajax Action
            $.ajax({
                url: "{{ route('product.variationPrice') }}",
                method: "POST",
                data: {
                    values,
                    product,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "JSON",
                success: function(result) {
                    if (result.status == true) {
                        // $('.add_to_cart').removeClass('disabled');
                        // $('.no_stock_alert').hide();
                        $('.single_product_price').html(result.sale_price);
                        $('.single_product_price_input').val(result.sale_price);
                        if(result.old_price){
                            $('.single_product_price_old').html(result.old_price);
                            $('.single_product_price_old').show();

                            $('.single_product_price_old_total').html(result.old_price * quantity);
                            $('.single_product_price_old_total').show();

                            $('.single_product_price_old_input').val(result.old_price);
                        }else{
                            $('.single_product_price_old').hide();
                            $('.single_product_price_old_total').hide();
                            $('.single_product_price_old_input').val('');
                        }
                        $('.single_product_price_total').html(result.sale_price * quantity);
                        $('#sales_price').val(result.sale_price);
                        $('.product_data_id').val(result.product_data_id);
                        // $('.maximum_stock').val(result.stock);
                        // $('.cart_quantity_input').val(1);
                    } else {
                        $('.product_data_id').val('');
                    }

                    // if(result.sku){
                    //     $('.sku_code').html(result.sku);
                    // }else{
                    //     $('.sku_code').html('N/A');
                    // }
                },
                error: function() {
                    console.log('Variation price ajax error!');
                }
            });
        });

        $(document).on('submit', '.addtoCartForm', function(e) {
            let hidden_product_data = $('.product_data_id').val();

            if (!hidden_product_data) {
                e.preventDefault();
                cAlert('error', 'অনুগ্রহ করে প্রোডাক্ট অপসন সঠিকভাবে সিলেক্ট করুন!');
                return false;
            }

            return true;
        });

        $(document).ready(function() {
            $('.tab-header').click(function() {
                // Toggle the tab content
                $(this).find('svg').toggleClass('rotate-180');
                $(this).next('.tab-content').slideToggle(300);

                // Optional: close other tabs
                $('.tab-header').not(this).find('svg').removeClass('rotate-180');
                $('.tab-content').not($(this).next('.tab-content')).slideUp(300);
            });
        });

        $(document).on('change', '.change_area', function(){
            let area = $(this).val();
            let product_total_input = $('.product_total_input').val();

            if(area == 'Outside Dhaka'){
                $('.shipping_charge').val(Number(outside_dhaka_delivery_charge));
                // $('.shipping_charge_text').html(Number(outside_dhaka_delivery_charge));
                $('.grand_total').html((Number(product_total_input) + Number(outside_dhaka_delivery_charge)).toFixed(2));
            }else{
                $('.shipping_charge').val(Number(inside_dhaka_delivery_charge));
                // $('.shipping_charge_text').html(Number(inside_dhaka_delivery_charge));
                $('.grand_total').html((Number(product_total_input) + Number(inside_dhaka_delivery_charge)).toFixed(2));
            }
        });

        $(document).on('submit', '.disableDoubleClickOnSubmit', function(){
            $('#page_loader').show();
        });

        if (typeof fbq === "function"){
            fbq('track', 'InitiateCheckout');
        }

        $(document).on('focusout', '.information_field', function(){
            activityTrack('Information Inserted');
        });

        function activityTrack(event){
            let shipping_mobile_number = $('.mobile_number').val() || '';
            if(shipping_mobile_number.length >= 11){
                let shipping_name = $('.shipping_name').val();
                let shipping_address = $('.shipping_address').val();
                let shipping_charge = $('.shipping_charge').val();
                let uu_id = $('.uu_id').val();

                $.ajax({
                    url: "{{route('orderFailedTrackSaas')}}",
                    method: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        uid: uu_id,
                        shipping_name,
                        event,
                        shipping_mobile_number,
                        shipping_address,
                        shipping_charge
                    },
                    success: function(){},
                    error: function(){}
                });
            }
        }
    </script>
@endsection
