@extends('front.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'Contact Us - ' . ($settings_g['title'] ?? env('APP_NAME')),
        'description' => $settings_g['meta_description'] ?? '',
        'keywords' => $settings_g['keywords'] ?? '',
    ])
@endsection

@section('master')
    @php
        $contactEmail = $settings_g['email'] ?? 'info@example.com';
        $contactPhone = $settings_g['mobile_number'] ?? '';
        $addressParts = array_filter([$settings_g['street'] ?? null, $settings_g['city'] ?? null, $settings_g['state'] ?? null, $settings_g['country'] ?? null, $settings_g['zip'] ?? null]);
        $contactAddress = count($addressParts) ? implode(', ', $addressParts) : 'Uttara, Dhaka, Bangladesh - 1230';
        $socials = Info::SettingsGroupKey('social');
    @endphp

    <section class="bg-white py-5 md:py-10">
        <div class="container mx-auto px-4">
            <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-3xl border-2 border-green-600 bg-green-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                    <h1 class="mb-4 text-2xl font-bold text-gray-800">HERE FOR YOU</h1>

                    <div class="mb-4">
                        <p class="mb-1 text-sm font-semibold text-gray-700">CORPORATE ADDRESS</p>
                        <p class="text-sm leading-relaxed text-gray-700">{{ $contactAddress }}</p>
                    </div>

                    @if ($contactPhone)
                        <a href="tel:{{ $contactPhone }}" class="mb-3 flex items-center gap-3">
                            <span class="flex shrink-0 rounded-full bg-green-700 p-2 text-white">
                                <i class="fas fa-phone h-5 w-5 text-center leading-5"></i>
                            </span>
                            <span class="text-sm font-semibold text-green-700">Phone: {{ $contactPhone }}</span>
                        </a>
                    @endif

                    <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-3">
                        <span class="flex shrink-0 rounded-full bg-blue-600 p-2 text-white">
                            <i class="fas fa-envelope h-5 w-5 text-center leading-5"></i>
                        </span>
                        <span class="break-all text-sm font-semibold text-blue-600">Email: {{ $contactEmail }}</span>
                    </a>
                </div>

                <div class="rounded-3xl border-2 border-green-600 bg-green-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">Join Our Team</h2>
                    <p class="mb-6 text-sm leading-relaxed text-gray-700">
                        Looking for job opportunities? Send us your resume:
                    </p>
                    <a href="mailto:hr@{{ parse_url(url('/'), PHP_URL_HOST) }}" class="flex items-center gap-3">
                        <span class="flex shrink-0 rounded-full bg-blue-600 p-2 text-white">
                            <i class="fas fa-envelope h-5 w-5 text-center leading-5"></i>
                        </span>
                        <span class="break-all text-sm font-semibold text-blue-600">Email: {{ $contactEmail }}</span>
                    </a>
                </div>

                <div class="rounded-3xl border-2 border-green-600 bg-green-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">Business Collaboration</h2>
                    <p class="mb-6 text-sm leading-relaxed text-gray-700">
                        For business-related proposals, contact us:
                    </p>
                    <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-3">
                        <span class="flex shrink-0 rounded-full bg-blue-600 p-2 text-white">
                            <i class="fas fa-envelope h-5 w-5 text-center leading-5"></i>
                        </span>
                        <span class="break-all text-sm font-semibold text-blue-600">Email: {{ $contactEmail }}</span>
                    </a>
                </div>

                <div class="rounded-3xl border-2 border-green-600 bg-green-100 p-8 shadow-sm transition-shadow hover:shadow-md">
                    <h2 class="mb-4 text-2xl font-bold text-gray-800">Influencer Partnerships</h2>
                    <p class="mb-6 text-sm leading-relaxed text-gray-700">
                        Want to collaborate as an influencer? Drop us an email:
                    </p>
                    <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-3">
                        <span class="flex shrink-0 rounded-full bg-blue-600 p-2 text-white">
                            <i class="fas fa-envelope h-5 w-5 text-center leading-5"></i>
                        </span>
                        <span class="break-all text-sm font-semibold text-blue-600">Email: {{ $contactEmail }}</span>
                    </a>
                </div>

                <div class="rounded-3xl border-2 border-yellow-500 bg-yellow-100 p-8 shadow-sm transition-shadow hover:shadow-md md:col-span-2 lg:col-span-1">
                    <h2 class="mb-6 text-2xl font-bold text-gray-800">Find Us On Social</h2>
                    <div class="flex flex-wrap gap-4">
                        @if ($socials['facebook'] ?? null)
                            <a href="{{ $socials['facebook'] }}" class="rounded-lg bg-blue-600 p-4 text-white shadow-md transition-colors hover:bg-blue-700" aria-label="Facebook">
                                <i class="fab fa-facebook-f h-6 w-6 text-center text-2xl leading-6"></i>
                            </a>
                        @endif
                        @if ($socials['tiktok'] ?? null)
                            <a href="{{ $socials['tiktok'] }}" class="rounded-lg bg-gray-800 p-4 text-white shadow-md transition-colors hover:bg-gray-900" aria-label="TikTok">
                                <i class="fab fa-tiktok h-6 w-6 text-center text-2xl leading-6"></i>
                            </a>
                        @endif
                        @if ($socials['instagram'] ?? null)
                            <a href="{{ $socials['instagram'] }}" class="rounded-lg bg-gray-900 p-4 text-white shadow-md transition-colors hover:bg-black" aria-label="Instagram">
                                <i class="fab fa-instagram h-6 w-6 text-center text-2xl leading-6"></i>
                            </a>
                        @endif
                        @if ($socials['youtube'] ?? null)
                            <a href="{{ $socials['youtube'] }}" class="rounded-lg bg-red-600 p-4 text-white shadow-md transition-colors hover:bg-red-700" aria-label="YouTube">
                                <i class="fab fa-youtube h-6 w-6 text-center text-2xl leading-6"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white md:p-5">
                <h2 class="mb-4 text-center text-3xl font-bold text-gray-800 md:text-4xl">Drop Us A Line</h2>
                <p class="mx-auto mb-8 max-w-2xl text-center text-gray-600">
                    We're happy to answer any questions you have or provide you with an estimate. Just send us a message in the form below with any questions you may have.
                </p>

                <form action="{{ route('contactUs.store') }}" method="post" class="mx-auto max-w-4xl space-y-6">
                    @csrf

                    @if (session('contact_submitted'))
                        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-700">
                            Thank you! Your message has been sent. We will get back to you soon.
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" required class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone number" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">

                    <textarea name="message" placeholder="Message" rows="6" required class="w-full resize-none rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('message') }}</textarea>

                    <button type="submit" class="cursor-pointer rounded-lg bg-green-500 px-8 py-3 font-bold text-white shadow-md transition-colors duration-200 hover:bg-green-600 hover:shadow-lg">
                        SEND MESSAGE
                    </button>
                </form>
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
                    "name":"Contact Us",
                    "@id":"{{route('contactUs')}}"
                },
                "@type":"ListItem",
                "position":"2"
            }
        ]
    }
</script>
@endsection
