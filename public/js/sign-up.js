$(".ui.form").form({
    on: 'blur',
    fields: {
        email: {
            identifier: 'email',
            rules: [
                {
                    type: 'empty',
                    prompt: 'You... need an email to use this.'
                },
                {
                    type: 'email',
                    prompt: 'To use this, you need to use a <em>valid</em> email address.'
                },
                {
                    type: 'not[excited@foremail.alerts]',
                    prompt: 'Nice try, smart-ass.'
                }
            ]
        },
        password: {
            identifier: 'password',
            rules: [
                {
                    type: 'empty',
                    prompt: 'You probably want to set a password.'
                },
                {
                    type: 'minLength[12]',
                    prompt: 'The password has to be at least twelve characters. For real.'
                },
                {
                    type: 'not[something_way_stronger_than_this]',
                    prompt: 'That\'s a good way to get your account broken into.'
                }
            ]
        }
    }
});
