import AbstractModal from "@enhavo/app/Modal/Model/AbstractModal";
import axios from "axios";

export default class AjaxFormModal extends AbstractModal
{
    public route: string;
    public routeParameters: object;
    public actionRoute: string;
    public actionRouteParameters: object;
    public actionHandler: (modal: AjaxFormModal, data: any) => Promise<boolean>;
    public element: HTMLElement = null;
    public saveLabel: string = 'save';
    public closeLabel: string = 'close';
    public loading: boolean = true;

    async loadForm(): Promise<void>
    {
        let url = this.application.getRouter().generate(this.route, this.routeParameters);

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

    async sendForm(data: any): Promise<boolean>
    {
        this.loading = true;

        return new Promise((resolve, reject) => {
            axios.post(this.getActionUrl(), data).then((responseData) => {
                if(this.actionHandler) {
                    this.actionHandler(this, responseData)
                        .then((value) => {
                            resolve(value);
                        })
                        .catch(() => {
                            reject();
                        });
                } else {
                    resolve(false);
                }
                this.loading = false;
            }).catch(() => {
                reject();
            });
        });
    }

    private getActionUrl()
    {
        if(this.actionRoute) {
            return this.application.getRouter().generate(this.actionRoute, this.actionRouteParameters);
        }
        return this.application.getRouter().generate(this.route, this.routeParameters);
    }
}