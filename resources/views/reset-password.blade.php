@extends('layouts.master')

@section('content')
    <form class="ui form" method="post" action="/reset">
        <div class="field">
            <label for="new_password">New password</label>
            <input id="new_password" name="new_password" placeholder="something_stronger_than_this" type="password"/>
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
        <input type="hidden" name="id" value="{{ $id }}"/>
        <input type="hidden" name="reset_key" value="{{ $resetKey }}"/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <button class="big green ui button" type="submit">Change password</button>
    </form>
@endsection

@section('errors')@endsection
