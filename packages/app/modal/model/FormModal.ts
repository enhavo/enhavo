import { AbstractModal } from "@enhavo/app/modal/model/AbstractModal";
import {Router} from "@enhavo/framework/router/Router";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
import {Form} from "@enhavo/vue-form/model/Form";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

export class FormModal extends AbstractModal
{
    public loading: boolean = true;
    public form: Form = null;

    public formRoute: string;
    public formRouteParameters: object;
    public formUrl: string;
    public formParameter: string = 'form';

    public actionRoute: string;
    public actionRouteParameters: object;
    public actionUrl: string;
    public actionFormParameter: string = 'form';

    public actionHandler: (modal: FormModal, response: Response, error: string) => Promise<boolean>;
    public submitHandler: (modal: FormModal, data: any) => Promise<boolean>;

    public saveLabel: string = 'enhavo_app.save.label.save';
    public closeLabel: string = 'enhavo_app.abort';

    constructor(
        protected readonly router: Router,
        protected readonly formFactory: FormFactory,
    )
    {
        super();
    }

    onInit()
    {
        if (!this.component) {
            this.component = 'modal-form'
        }

        // check if form already created
        if (!this.form.key) {
            this.form = this.formFactory.create(this.form);
            this.loading = false;
        } else {
            this.loadForm();
        }
    }

    private async loadForm(): Promise<void>
    {
        let url = this.getFormUrl();

        this.loading = true;

        return new Promise((resolve, reject) => {
            fetch(url).then((response) => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            }).then((data) => {
                this.form = this.formFactory.create(data[this.actionFormParameter]);
                this.loading = false;
                resolve();
            }).catch(() => {
                reject();
            });
        });
    }

    private getFormUrl(): string
    {
        return this.formUrl ? this.formUrl : this.router.generate(this.formRoute, this.formRouteParameters);
    }

    async submit(): Promise<boolean>
    {
        if (this.submitHandler) {
            let data = this.getFormData();
            return this.submitHandler(this, data);
        } else {
            return this.sendForm();
        }
    }

    private sendForm(): Promise<boolean>
    {
        this.loading = true;
        return new Promise((resolve, reject) => {
            fetch(this.getActionUrl(), {
                method: 'POST',
                body: this.getFormData(),
            }).then((response) => {
                return this.receiveForm(response, resolve, reject);
            }).catch((error) => {
                console.error(error);
                this.loading = false;
                reject();
            });
        });
    }

    private async receiveForm(response: Response, resolve: (data?: any) => void, reject: () => void)
    {
        if (this.actionHandler) {
            this.actionHandler(this, response, null)
                .then((value) => {
                    resolve(value);
                })
                .catch((error) => {
                    console.error(error)
                    reject();
                });
        } else {
            let data = await response.json();
            let form = this.formFactory.create(data[this.actionFormParameter]);
            this.form.morphStart();
            this.form.morphMerge(form);
            this.form.morphFinish();
            resolve();
        }
        this.loading = false;
    }

    private getActionUrl()
    {
        if (this.actionUrl) {
            return this.actionUrl;
        }

        if (this.actionRoute) {
            return this.router.generate(this.actionRoute, this.actionRouteParameters);
        }

        return this.getFormUrl();
    }

    public getFormData(): FormData
    {
        return FormUtil.serializeForm(this.form);
    }
}
