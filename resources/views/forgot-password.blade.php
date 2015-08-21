@extends('layouts.master')

@section('content')
    <form class="ui form" method="post" action="/forgot">
        <div class="field">
            <label for="email">Primary email</label>
            <input id="email" name="email" placeholder="excited@foremail.alerts" type="email"/>
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
        <button class="big green ui button" type="submit">Send me a reset link</button>
    </form>
@endsection

@section('errors')@endsection
