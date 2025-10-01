<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md shadow-sm">
    <nav class="mx-auto flex max-w-section items-center justify-between px-4 py-4 lg:px-6" x-data="{ open: false, localeMenu: false }" @click.outside="localeMenu = false">
        <a href="/" class="flex items-center gap-2 text-lg font-heading text-trust">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gold text-trust shadow-sm">EC</span>
            <span class="hidden font-semibold uppercase tracking-wider sm:inline">ElderCare SG</span>
        </a>

        <button
            class="inline-flex items-center rounded-full bg-white p-2 text-trust shadow-card ring-1 ring-slate/10 transition hover:bg-gold/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold lg:hidden"
            @click="open = !open"
            :aria-expanded="open"
            aria-controls="primary-navigation"
            type="button"
        >
            <span class="sr-only">Toggle navigation</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <div
            id="primary-navigation"
            class="absolute inset-x-0 top-full origin-top rounded-b-3xl bg-white px-6 pb-6 pt-4 shadow-card transition duration-300 ease-out lg:static lg:flex lg:w-auto lg:translate-y-0 lg:items-center lg:gap-10 lg:bg-transparent lg:p-0 lg:shadow-none"
            x-show="open"
            x-transition.scale.origin-top
            x-cloak
        >
            <ul class="space-y-4 text-base font-medium text-slate lg:flex lg:items-center lg:gap-8 lg:space-y-0">
                <li><a class="hover:text-gold" href="#programs">Programs</a></li>
                <li><a class="hover:text-gold" href="#philosophy">Care Philosophy</a></li>
                <li><a class="hover:text-gold" href="#testimonials">Stories</a></li>
                <li><a class="hover:text-gold" href="#tour">Virtual Tour</a></li>
                <li><a class="hover:text-gold" href="#contact">Contact</a></li>
            </ul>

            <div class="mt-6 flex flex-col gap-3 lg:mt-0 lg:flex-row lg:items-center lg:gap-4">
                <div class="relative" x-data="{ activeLocale: '{{ app()->getLocale() }}' }" x-init="window.addEventListener('locale-changed', event => activeLocale = event.detail.locale)">
                    <button
                        type="button"
                        class="inline-flex items-center gap-1 rounded-full border border-slate/20 px-3 py-1 text-sm font-semibold uppercase tracking-wide text-trust transition hover:border-gold/60 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold"
                        @click="localeMenu = !localeMenu"
                        :aria-expanded="localeMenu"
                        aria-haspopup="true"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6" />
                        </svg>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>

                    <div
                        x-cloak
                        x-show="localeMenu"
                        x-transition.origin.top
                        class="absolute left-0 mt-2 w-36 overflow-hidden rounded-2xl border border-slate/10 bg-white shadow-card"
                        role="menu"
                        aria-label="{{ __('navigation.choose_language') }}"
                    >
                        @foreach (config('app.supported_locales') as $locale)
                            <form method="POST" action="{{ route('locale.switch', $locale) }}" role="none">
                                @csrf
                                <button
                                    type="submit"
                                    class="flex w-full items-center justify-between px-4 py-2 text-left text-sm font-medium text-slate transition hover:bg-gold/10"
                                    :class="{ 'text-trust font-semibold': activeLocale === '{{ $locale }}' }"
                                    @click="localeMenu = false"
                                >
                                    <span>{{ __('navigation.locale_label.' . $locale) }}</span>
                                    <span x-show="activeLocale === '{{ $locale }}'" class="sr-only">{{ __('navigation.current_language') }}</span>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
                <a href="#tour" class="pill-tag">Virtual tour</a>
                <a href="#booking" class="cta-button">Book a visit</a>
            </div>
        </div>
    </nav>
</header>
