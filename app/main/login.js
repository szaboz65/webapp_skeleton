import { query, w2utils, w2form, w2alert } from './../libs/w2ui/w2ui.es6.js'

const conf = {
    loginHTML: `
        <div class="login-box">
            <div class="login-msg">&nbsp;</div>
            <div id="login-form">&nbsp;</div>
            <div class="login-footer">
                Copyright 2024
            </div>
        </div>`,

    login_form: {
        fields: [
            {field: 'login', type: 'email', required: true,
                html: {label: 'Login', attr: 'maxlength="128" style="width: 300px"'}},
            {field: 'pass', type: 'password', required: true,
                html: {label: 'Password', attr: 'maxlength="40" style="width: 300px"'}}
        ]
    },

    forgot_form: {
        fields: [
            {field: 'email', type: 'email', required: true,
                html: {label: 'Email', attr: 'maxlength="128" style="width: 300px"',
                text: `<br><br><br><h3>Instructions will be sent to this email address.</h3>
                        <p>Please, follow the instructions!</p>`}}
        ]
    },

    pwreset_form: {
        fields: [
            {field: 'pwemail', type: 'email', required: true,
                html: {label: 'Login', attr: 'maxlength="128" style="width: 300px"'}},
            {field: 'pw', type: 'password', required: true,
                html: {label: 'Password', attr: 'maxlength="40" style="width: 300px"'}},
            {field: 'pw2', type: 'password', required: true,
                html: {label: 'Password again', attr: 'maxlength="40" style="width: 300px"'}}
        ]
    }
};

class Login {
    constructor(callback) {
        this.ResetCode = this.getResetCode()

        this.login_form = new w2form(w2utils.extend({
            name: 'login_form',
            url: app.getContextUrl('login'),
            owner: this,
            actions: {
                'Login': function () {
                    setMessage();
                    this.save({}, (data) => {
                        if (data?.error === true) {
                            showErrors(this, data)
                        } else if (typeof callback === 'function') {
                            this.owner.pwreset_form.destroy()
                            this.owner.forgot_form.destroy()
                            this.owner.login_form.destroy()
                            callback()
                        }
                    });
                },
                custom: {
                    text: 'Forgot Password?',
                    class: 'custom-class',
                    onClick() {
                        this.owner.forgot_form.render(query('#login-form')[0]);
                    }
                }
            }
        }, conf.login_form));
        
        this.forgot_form = new w2form(w2utils.extend({
            name: 'forgot_form',
            url: app.getContextUrl('forgot'),
            owner: this,
            actions: {
                'Reset': function () {
                    setMessage();
                    this.save({}, (data) => {
                        if (data?.error === true) {
                            showErrors(this, data);
                        } else {
                            setMessage('Please follow the instructions in the sent mail!')
                        }
                    });
                },
                custom: {
                    text: 'Try to login again?',
                    class: 'custom-class',
                    onClick() {
                        this.owner.login_form.render(query('#login-form')[0]);
                    }
                }
            }
        }, conf.forgot_form));
        
        this.pwreset_form = new w2form(w2utils.extend({
            name: 'pwreset_form',
            url: app.getContextUrl('pwreset'),
            owner: this,
            actions: {
                'Reset': function () {
                    setMessage();
                    this.save({}, (data) => {
                        if (data?.error === true) {
                            showErrors(this, data);
                        } else {
                            setMessage('Password has been changed. Please login!')
                            this.owner.login_form.render(query('#login-form')[0]);
                        }
                    });
                },
                custom: {
                    text: 'Try to login again?',
                    class: 'custom-class',
                    onClick() {
                        this.owner.login_form.render(query('#login-form')[0]);
                    }
                }
            },
            onValidate: function (event)  {
                if (this.getValue('pw') !== this.getValue('pw2')) {
                    event.detail.errors.push({error: 'Passwords do not match.', field: this.get('pw')});
                }
            },
            onSubmit: (event) => {
                delete event.detail.postData.record.pw2
                w2utils.extend(event.detail.postData.record, {reset_code: this.ResetCode})
            }
        }, conf.pwreset_form));

        function setMessage (message) {
            if (typeof message === 'undefined') {
                message = '&nbsp;';
            }
            query('.login-box .login-msg').html(message);
        }
        
        function showErrors(form, data) {
            setMessage(data.message)
            data?.details?.forEach(e => {
                form.last.errors.push({field: form.get(e.field), error: e.message});
            });
            form.showErrors();
        }
    }

    getResetCode () {
        let search = document.location.search
        if (search.length && search[0] === '?') {
            search = search.substring(1)
            if (search.indexOf('reset_code') !== -1) {
                const part = search.split('reset_code=')
                if (part.length === 2) {
                    return part[1]
                }
            }
        }
        return null
    }
    
    start (app) {
        const layout = app.main.getLayout()
        layout.html('main', conf.loginHTML)

        if (this.ResetCode) {
            this.pwreset_form.render(query('#login-form')[0]);
            window.history.pushState({}, document.title, window.location.origin + window.location.pathname)
        } else {
            this.login_form.render(query('#login-form')[0]);
        }
    }
}

export default function login (app, callback) {
    (new Login(callback)).start(app);
}
