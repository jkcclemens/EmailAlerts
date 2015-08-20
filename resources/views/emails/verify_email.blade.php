<h1>Howdy!</h1>

<p>
    Someone out there in the big bad world (hopefully you) wants to verify this email address ({{ $email }})!
</p>
<p>
    If it really was you and you're still on-board, go ahead and click <a href="{{ $verify_link }}">here</a> to verify
    it.
</p>
<p>
    If you can't click that link, copy this into your address bar:
    <br/>
    {{ $verify_link }}
</p>
<p>
    <small>
        Oh, and if it wasn't you that requested this, just ignore it. No one can use your email for anything unless it's
        verified.
    </small>
</p>
<p>
    Thanks a million,<br/>
    EmailAlerts
</p>
