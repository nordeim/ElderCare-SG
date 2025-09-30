<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscriptionRequest;
use App\Services\MailchimpService;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function __invoke(NewsletterSubscriptionRequest $request, MailchimpService $mailchimp): RedirectResponse
    {
        $email = $request->validated()['email'];

        $subscribed = $mailchimp->subscribe($email);

        if ($subscribed) {
            return back()->with('newsletter_status', 'Thanks for subscribing! Please confirm your email to complete the process.');
        }

        return back()
            ->withInput()
            ->with('newsletter_error', 'We could not add you right now. Please try again later or contact us directly.');
    }
}
