import { AbstractModal } from "@enhavo/app/modal/model/AbstractModal";
import axios from "axios";
import Router from "@enhavo/core/Router";
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

    public actionHandler: (modal: FormModal, data: any, error: string) => Promise<boolean>;
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
            axios.get(url).then((data) => {
                this.form = this.formFactory.create(data.data[this.actionFormParameter]);
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
            axios.post(this.getActionUrl(), this.getFormData(), {
                headers: {'Content-Type': 'application/x-www-form-urlencoded' },
                responseType: 'arraybuffer'
            }).then((responseData) => {
                this.receiveForm(responseData, resolve, reject)
            }).catch((error) => {
                this.receiveForm(error.response, resolve, reject)
            });
        });
    }

    private receiveForm(responseData: any, resolve: (data?: any) => void, reject: () => void)
    {
        if (this.actionHandler) {
            this.actionHandler(this, responseData, null)
                .then((value) => {
                    resolve(value);
                })
                .catch((error) => {
                    console.error(error)
                    reject();
                });
        } else {
            let form = this.formFactory.create(responseData.data[this.actionFormParameter]);
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
