<footer id="contact" class="mt-16 border-t border-slate/10 bg-white">
    <div class="mx-auto max-w-section px-4 py-12 lg:px-6 lg:py-16">
        <div class="grid gap-12 lg:grid-cols-3">
            <div>
                <h2 class="text-lg font-semibold uppercase tracking-wider text-trust">Get in touch</h2>
                <p class="mt-4 text-slate">
                    123 Caregiving Avenue<br>
                    Singapore 049999
                </p>
                <p class="mt-2 text-slate">
                    <a href="tel:+6561234567" class="hover:text-gold">+65 6123 4567</a><br>
                    <a href="mailto:hello@eldercare.sg" class="hover:text-gold">hello@eldercare.sg</a>
                </p>
            </div>

            <div>
                <h2 class="text-lg font-semibold uppercase tracking-wider text-trust">Stay connected</h2>
                <p class="mt-4 text-slate">Receive caregiver tips, program updates, and visit availability alerts.</p>
                <form class="mt-4 flex flex-col gap-3 sm:flex-row" method="post" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    <label class="sr-only" for="newsletter-email">Email</label>
                    <input
                        class="w-full rounded-full border border-slate/20 bg-white px-4 py-3 text-sm shadow-sm focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold"
                        id="newsletter-email"
                        type="email"
                        placeholder="you@example.com"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >
                    <button type="submit" class="cta-button">Join newsletter</button>
                </form>
                <div class="mt-2 space-y-2 text-sm" aria-live="polite">
                    @if (session('newsletter_status'))
                        <p class="rounded-full bg-wellness/10 px-4 py-2 text-wellness/90">{{ session('newsletter_status') }}</p>
                    @endif
                    @if (session('newsletter_error'))
                        <p class="rounded-full bg-red-100 px-4 py-2 text-red-700">{{ session('newsletter_error') }}</p>
                    @endif
                    @error('email')
                        <p class="rounded-full bg-red-100 px-4 py-2 text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <p class="mt-2 text-xs text-slate">Protected by Mailchimp. Unsubscribe anytime.</p>
            </div>

            <div class="space-y-4">
                <h2 class="text-lg font-semibold uppercase tracking-wider text-trust">Trusted by</h2>
                <ul class="flex flex-wrap items-center gap-4 text-sm text-slate">
                    <li class="flex items-center gap-2 rounded-full bg-trust px-3 py-1 font-semibold text-white shadow-sm">MOH Certified</li>
                    <li class="flex items-center gap-2 rounded-full bg-trust px-3 py-1 font-semibold text-white shadow-sm">SG Care Alliance</li>
                    <li class="flex items-center gap-2 rounded-full bg-trust px-3 py-1 font-semibold text-white shadow-sm">ISO 9001</li>
                </ul>
                <div class="text-xs text-slate">
                    <p>© {{ now()->year }} ElderCare SG. All rights reserved.</p>
                    <p class="mt-1">Privacy · Terms · Accessibility</p>
                </div>
            </div>
        </div>
    </div>
</footer>
