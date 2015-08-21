<h1>Hey there!</h1>

<p>
    Someone surfing the wild, wild web submitted a password reset request for this email ({{ $email }}). Hopefully that
    was you.
</p>
<p>
    Assuming it <em>was</em> you, go ahead and <a href="{{ $resetLink }}">click here</a> to reset your password.
</p>
<p>
    If you can't click that link, copy this into your address bar:
    <br/>
    {{ $resetLink }}
</p>
<p>
    <small>
        Oh, and if you didn't request this, don't sweat it. Your account is still safe.
    </small>
</p>
<p>
    Thanks a whole bunch,<br/>
    EmailAlerts
</p>
