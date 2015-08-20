@extends('layouts.master')

@section('content')
    <form class="ui form" method="post" action="/signup">
        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" placeholder="excited@foremail.alerts" type="email" value="{{ \Illuminate\Support\Facades\Input::get('email') }}"/>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" placeholder="something_way_stronger_than_this" type="password" value="{{ \Illuminate\Support\Facades\Input::get('password') }}"/>
        </div>
        <div class="ui error message">
            @if(count($errors) > 0)
                <ul class="list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <script>document.getElementsByClassName('ui error message')[0].style['display'] = 'block';</script>
            @endif
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <button class="big green ui button" type="submit">Sign up</button>
    </form>
@endsection

@section('bottom')
    <script src="js/sign-up.js"></script>
@endsection

@section('errors')@endsection
