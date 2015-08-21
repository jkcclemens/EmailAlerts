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
    @if(Auth::user()->notifications()->count() < 1)
        <p>
            Sent notifications will appear here, once some arrive.
        </p>
        <p>
            If you're expecting a forwarding verification email, the first email to arrive from any newly-verified email
            address will be available in its entirety on this page, once it arrives.
        </p>
    @else
        <div class="ui cards">
            @foreach(Auth::user()->notifications as $notification)
                <div class="card">
                    <div class="content">
                        <div class="header">
                            {{ $notification->subject }}
                        </div>
                        <div class="meta">
                            {{ $notification->email->email }}
                        </div>
                        @if($notification->data)
                            <div class="description">
                                {{ $notification->data }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@section('left')
    <div class="ui cards">
        @foreach($user->emails as $email)
            <div class="{{ $email->verified ? 'green' : 'red' }} card">
                <div class="content">
                    @if(!($email->user->emails()->count() < 2 and $email->isPrimary()))
                        <a title="Remove this email" href="/removeemail/{{ $email->id }}"><i
                                    class="right floated hover close icon"></i></a>
                    @endif
                    @if(!$email->isPrimary() and $email->verified)
                        <a title="Make this your primary email" href="/makeprimary/{{ $email->id }}"><i
                                    class="right floated hover heart icon"></i></a>
                    @endif
                    <div class="header">{{ $email->email }}</div>
                    <div class="meta">
                        @if($email->isPrimary())
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

@section('right')
    <div class="ui cards">
        <div class="{{ Auth::user()->pb_access_token ? "green" : "red" }} card">
            <div class="content">
                <div class="header">
                    Pushbullet status
                </div>
                <div class="meta">
                    {{ Auth::user()->pb_access_token ? "Linked" : "Not linked" }}
                </div>
                <div class="description">
                    @if(Auth::user()->pb_access_token)
                        You're set up to receive notifications!
                    @else
                        You're not set up to receive notifications. Please, click
                        <a href="https://www.pushbullet.com/authorize?client_id={{ env('PUSHBULLET_CLIENT_ID') }}&response_type=code">here</a>
                        to set up Pushbullet.
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
