import AbstractModal from "@enhavo/app/modal/model/AbstractModal";
import axios from "axios";
import * as _ from "lodash";
import ModalManager from "@enhavo/app/modal/ModalManager";
import Router from "@enhavo/core/Router";

export default class AjaxFormModal extends AbstractModal
{
    public route: string;
    public routeParameters: object;
    public actionRoute: string;
    public actionRouteParameters: object;
    public actionUrl: string;
    public actionHandler: (modal: AjaxFormModal, data: any, error: string) => Promise<boolean>;
    public submitHandler: (modal: AjaxFormModal, data: any) => Promise<boolean>;
    public element: HTMLElement = null;
    public form: HTMLElement = null;
    public saveLabel: string = 'enhavo_app.save';
    public closeLabel: string = 'enhavo_app.abort';
    public loading: boolean = true;
    public data: any;

    protected readonly router: Router;

    constructor(modalManager: ModalManager, router: Router)
    {
        super(modalManager);
        this.router = router;
    }

    async loadForm(): Promise<void>
    {
        let url = this.router.generate(this.route, this.routeParameters);

        this.loading = true;

        return new Promise((resolve, reject) => {
            axios.get(url).then((data) => {
                let html = data.data.trim();
                this.element = <HTMLElement>$.parseHTML(html)[0];
                this.loading = false;
                resolve();
            }).catch(() => {
                reject();
            });
        });
    }

    async submit(): Promise<boolean>
    {
        let data = this.getFormData();
        if (this.submitHandler) {
            return this.submitHandler(this, data);
        } else {
            return this.sendForm(data);
        }
    }

    private sendForm(data: any): Promise<boolean>
    {
        this.loading = true;
        return new Promise((resolve, reject) => {
            if(this.data) {
                data = _.extend(data, this.data);
            }

            axios.post(this.getActionUrl(), this.buildQuery(data)).then((responseData) => {
                this.receiveForm(responseData, resolve, reject)
            }).catch((error) => {
                this.receiveForm(error.response, resolve, reject)
            });
        });
    }

    private receiveForm(responseData: any, resolve: (data?: any) => void, reject: () => void)
    {
        if(this.actionHandler) {
            this.actionHandler(this, responseData, null)
                .then((value) => {
                    resolve(value);
                })
                .catch(() => {
                    reject();
                });
        } else {
            let html = responseData.data.trim();
            this.element = <HTMLElement>$.parseHTML(html)[0];
            resolve();
        }
        this.loading = false;
    }

    private getActionUrl()
    {
        if(this.actionUrl) {
            return this.actionUrl;
        }

        if(this.actionRoute) {
            return this.router.generate(this.actionRoute, this.actionRouteParameters);
        }

        return this.router.generate(this.route, this.routeParameters);
    }

    private buildQuery(obj: object, prefix: string = ''): string
    {
        let args = [];
        if(typeof(obj) == 'object') {
            for(let i in obj) {
                if(prefix == '') {
                    args[args.length] = this.buildQuery(obj[i], encodeURIComponent(i));
                } else {
                    args[args.length] = this.buildQuery(obj[i], prefix+'['+encodeURIComponent(i)+']');
                }
            }
        } else {
            args[args.length]=prefix+'='+encodeURIComponent(obj);
        }
        return args.join('&');
    }

    public getFormData(): object
    {
        let formData = {};
        let data = $(this.form).serializeArray();
        for(let row of data) {
            formData[row.name] = row.value;
        }
        return formData;
    }
}
