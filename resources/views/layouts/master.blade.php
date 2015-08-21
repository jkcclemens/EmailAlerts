@extends('layouts.bootstrap')

@section('base')
        <!--suppress HtmlUnknownTag -->
<div class="grid-50 push-25" id="main">
    <div class="ui left rail">
        <div class="ui sticky">
            @yield('left')
        </div>
    </div>
    <div class="ui right rail" style="text-align: right;">
        <div class="ui sticky">
            <div class="ui cards">
                <div class="card">
                    <div class="content">
                        @if(Auth::check())
                            <div class="header">Welcome back!</div>
                            <div class="meta">
                                <a href="/logout">Log out</a>
                            </div>
                        @else
                            <div class="header">Howdy!</div>
                            <a href="/signup">Sign up</a> or <a href="/login">log in</a>
                        @endif
                    </div>
                </div>
                @yield('right_cards')
            </div>
        </div>
        @yield('right')
    </div>
    <div class="title">
        <h2 class="ui center aligned icon header">
            <i class="mail outline icon"></i>

            <div class="content">
                <a href="/">EmailAlerts</a>

                <div class="sub header">Turn pesky emails into push notifications.</div>
            </div>
        </h2>
    </div>
    @yield('content')
</div>
@endsection
