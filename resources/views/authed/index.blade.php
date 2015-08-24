@extends('layouts.master')

@section('content')
    <p>
        Set your email provider up to forward emails that you want as notifications to <em>receive@emailalerts.xyz</em>.
        Received emails will appear below!
    </p>
    @if(Auth::user()->notifications()->count() < 1)
        <p>
            Sent notifications will appear here, once some arrive.
        </p>
        <p>
            If you're expecting a forwarding verification email, the first email to arrive from any newly-verified email
            address will be available in its entirety on this page, once it arrives.
        </p>
    @else
        <div id="notifications">
            <div class="ui fluid search">
                <div class="ui fluid icon input">
                    <input class="prompt" placeholder="Search notifications..." type="text"/>
                    <i class="search icon"></i>
                </div>
                <div class="results"></div>
            </div>
            <div class="list ui cards">
                <div class="ui active centered inline loader"></div>
                <div style="display: none;">
                    <div class="ui fluid card" id="template">
                        <div class="content">
                            <div class="header subject"></div>
                            <span style="float: right;" class="meta created_at"></span>

                            <div class="meta email">
                            </div>
                            <div class="description data"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pagination-parent">
                <div class="ui pagination menu"></div>
            </div>
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
                        <?
                        /**
                         * @var $email \App\Email
                         */
                        $emailCount = $email->notifications->where('email_id', $email->id)->count();
                        ?>
                        – {{ $emailCount . ' ' . str_plural('notification', $emailCount) }}
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

@section('right_cards')
    <div class="{{ Auth::user()->pb_access_token ? "green" : "red" }} card">
        <div class="content">
            @if(Auth::user()->pb_access_token)
                <a href="/unlink_pushbullet"><i class="left floated hover close icon"></i></a>
            @endif
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
                    <a href="https://www.pushbullet.com/authorize?client_id={{ env('PUSHBULLET_CLIENT_ID') }}&response_type=code&redirect_uri={{ urlencode(url('/pb_auth')) }}">here</a>
                    to set up Pushbullet.
                @endif
            </div>
        </div>
    </div>
@endsection

@section('bottom')
    <script src="/js/list.min.js"></script>
    <script src="/js/list.pagination.min.js"></script>
    <script src="/js/moment.js"></script>
    <script>
        $.ajax(
                '/notifications',
                {
                    success: function (data) {
                        $.each(data, function (number, datum) {
                            datum['created_at'] = moment(datum['created_at']).fromNow();
                        });
                        $('.list.ui.cards > .active.loader').removeClass('active');
                        var list = new List(
                                'notifications',
                                {
                                    item: 'template',
                                    valueNames: ['subject'],
                                    page: 5,
                                    plugins: [ListPagination({})],
                                    searchField: "prompt"
                                },
                                data
                        );
                        list.on('updated', function() {
                            $('.ui.sticky').sticky('refresh');
                        });
                    }
                }
        );
    </script>
@endsection
