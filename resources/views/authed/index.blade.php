@extends('layouts.master')

@section('content')
    @if(session('message'))
        <div class="ui positive message">
            {{ session('message') }}
        </div>
    @endif
    @if(count($errors) > 0)
        <div class="ui error message">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1>Sup</h1>
@endsection

@section('left')
    <div class="ui cards">
        @foreach($user->emails as $email)
            <div class="{{ $email->verified ? 'green' : 'red' }} card">
                <div class="content">
                    <a href="/removeemail/{{ $email->id }}"><i class="right floated hover close icon"></i></a>
                    <div class="header">{{ $email->email }}</div>
                    <div class="meta">
                        @if($email->email == $user->email)
                            Primary
                        @endif
                        {{ $email->verified ? 'Verified' : 'Unverified' }}
                    </div>
                </div>
            </div>
        @endforeach
        <div class="card">
            <div class="content">
                <form class="ui form" method="post" action="/addemail">
                    <div class="ui transparent input header" style="margin-bottom: 0;">
                        <input type="email" name="email" placeholder="new@emailto.add"/>
                    </div>
                    <div class="meta">
                        Pending addition
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                </form>
            </div>
        </div>
    </div>
@endsection
