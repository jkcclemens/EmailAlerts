@extends('layouts.bootstrap')

@section('base')
        <!--suppress HtmlUnknownTag -->
<div class="grid-25">
    @section('left')&nbsp;@show
</div>
<div class="grid-50">
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
<div class="grid-25" style="text-align: right;">
    @if(Auth::check())
        Welcome back! | <a href="/logout">Log out</a>
    @else
        <a href="/signup">Sign up</a> | <a href="/login">Log in</a>
    @endif
    @yield('right')
</div>
@endsection
