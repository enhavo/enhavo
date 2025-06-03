import {Router} from "@enhavo/app/routing/Router";
import {Form} from "@enhavo/vue-form/model/Form";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

export class UserManager
{
    public loading: boolean;
    public loadingData: boolean = true;

    public loginForm: Form

    public resetPasswordRequestForm: Form
    public resetPasswordConfirmForm: Form
    public resetPasswordConfirmTokenError: boolean

    public branding: Branding;

    constructor(
        private router: Router,
        private formFactory: FormFactory,
    ) {

    }
    public loadLogin()
    {
        this.loginForm = null;
        this.loading = true;
        const url = this.router.generate('enhavo_user_admin_api_login_form');
        fetch(url).then((response) => {
            response.json().then((data: any) => {
                this.loading = false;
                this.loginForm = this.formFactory.create(data.form);
            });
        });
    }

    public loadLoginData()
    {
        if (this.branding) {
            return;
        }

        this.loadingData = true;
        const url = this.router.generate('enhavo_user_admin_api_login_data');
        fetch(url).then((response) => {
            response.json().then((data: any) => {
                this.branding = data.branding;
                this.loadingData = false;
            });
        });
    }

    public login()
    {
        this.loading = true;

        const urlParams = new URLSearchParams(window.location.search);
        const redirect = urlParams.get('redirect');

        const url = this.router.generate('enhavo_user_admin_api_login_form', {
            redirect: redirect,
        });
        const data = FormUtil.serializeForm(this.loginForm);

        fetch(url, {
            method: 'POST',
            body: data,
        }).then((response) => {
            response.json().then((data: any) => {
                if (data.success) {
                    // @ts-ignore
                    window.location.href = data.redirect;
                    return;
                }

                this.loading = false;
                this.loginForm = this.formFactory.create(data.form);
            });
        });
    }

    public loadResetPasswordRequest()
    {
        this.loading = true;
        this.resetPasswordRequestForm = null;

        const url = this.router.generate('enhavo_user_admin_api_reset_password_request');
        fetch(url).then((response) => {
            response.json().then((data: any) => {
                this.loading = false;
                this.resetPasswordRequestForm = this.formFactory.create(data.form);

            });
        });
    }

    public resetPasswordRequest(): Promise<ResetPasswordData>
    {
        this.loading = true;

        return new Promise((resolve, reject) => {
            const url = this.router.generate('enhavo_user_admin_api_reset_password_request');
            const data = FormUtil.serializeForm(this.resetPasswordRequestForm);

            fetch(url, {
                method: 'POST',
                body: data,
            }).then((response) => {
                response.json().then((data: any) => {
                    this.loading = false;
                    this.resetPasswordRequestForm = this.formFactory.create(data.form);
                    if (data.success) {
                        resolve(new ResetPasswordData(data.success, data.redirect))
                    }
                });
            });
        })
    }

    public loadResetPasswordConfirm(token: string)
    {
        this.loading = true;
        this.resetPasswordConfirmForm = null;
        this.resetPasswordConfirmTokenError = false;

        const url = this.router.generate('enhavo_user_admin_api_reset_password_confirm', {
            token: token
        });

        fetch(url).then((response) => {
            if (response.status === 200) {
                response.json().then((data: any) => {
                    this.loading = false;
                    this.resetPasswordConfirmForm = this.formFactory.create(data.form);
                });
            } else if (response.status === 404) {
                this.loading = false;
                this.resetPasswordConfirmTokenError = true;
            }
        });
    }

    public resetPasswordConfirm(token: string): Promise<ResetPasswordData>
    {
        this.loading = true;

        const url = this.router.generate('enhavo_user_admin_api_reset_password_confirm', {
            token: token
        });

        return new Promise((resolve, reject) => {
            const data = FormUtil.serializeForm(this.resetPasswordConfirmForm);

            fetch(url, {
                method: 'POST',
                body: data,
            }).then((response) => {
                response.json().then((data: any) => {
                    this.loading = false;
                    this.resetPasswordConfirmForm = this.formFactory.create(data.form);
                    if (data.success) {
                        if (data.autoLogin) {
                            // @ts-ignore
                            window.location.href = data.redirect;
                        } else {
                            resolve(new ResetPasswordData(data.success, data.redirect))
                        }
                    }
                });
            });
        })
    }
}

class Branding
{
    enableCreatedBy: boolean;
    enableVersion: boolean;
    enable: boolean;
    version: string;
    text: string;
    logo: string;
    backgroundImage: string;
}

export class ResetPasswordData
{
    success: boolean;
    url: string;

    constructor(success: boolean, url: string) {
        this.success = success;
        this.url = url;
    }
}
