@extends('layouts.master')

@section('content')
    @if(session('message'))
        <div class="ui positive message">
            {{ session('message') }}
        </div>
    @endif
    @yield('errors')
    <h2>Why?</h2>

    <p>
        I get emails from tons of sources, many of which I look at, acknowledge, and delete. <em>It's a waste of
            time.</em> Why not just have those emails become push notifications?
    </p>

    <h2>How?</h2>

    <p>
        By leveraging the power of your email provider's filters and
        <a href="https://pushbullet.com">Pushbullet</a>, this becomes possible.
    </p>

    <p>
        Simply register, verify your email (and add more, if necessary), then set your email provider to forward
        and delete emails that you want to see as push notifications.
    </p>

    <p>
        When we receive a forwarded email, we strip out any "Fwd:"-like material and send a notification with the
        subject of the email as the content. Simple.
    </p>
@endsection

@section('errors')
    @if(count($errors) > 0)
        <div class="ui error message">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
