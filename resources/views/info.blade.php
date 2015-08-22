@extends('layouts.master')

@section('content')
    <h1>Info</h1>
    <p>
        I would have called this an FAQ, but then I would have had to come up with fake questions that people never
        frequently asked me.
    </p>
    <p>
        Email is a fickle beast. As I continue to fix bugs and address issues, I learn this more and more. Basically, I
        want to give a run-down on how this thing is going to work.
    </p>
    <p>
        This system is meant to work without human interaction. Once you have things set up, it should just work. This
        means that you shouldn't be sending emails to EmailAlerts manually. You should have a filter that forwards them
        to the receive address. These are different things.
    </p>
    <p>
        I have only tested with Gmail, but a filter forwarding email actually keeps the email essentially the same. The
        sender remains unchanged. So, if you're filtering Twitch emails and sending them here (great use-case), the
        filter-forwarded email has a sender of <em>no-reply@twitch.tv</em> instead of your email. If you had manually
        forwarded the email, the opposite would be true.
    </p>
    <p>
        I don't know if this is true of other email services. It's hard to make one system that works for everyone. I
        have decided that only filter-forwarded emails are going to work with the system right now. This could change.
        It makes things easier for me this way.
    </p>
    <p>
        <strong>TL;DR</strong> – Email is hard. Don't manually send emails to EmailAlerts. Things might change.
    </p>
@endsection
