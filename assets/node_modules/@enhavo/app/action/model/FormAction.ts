import AbstractAction from "@enhavo/app/action/model/AbstractAction";
import View from "@enhavo/app/view/View";
import ModalManager from "@enhavo/app/modal/ModalManager";
import Router from "@enhavo/core/Router";
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal";
import Translator from "@enhavo/core/Translator";
import {AxiosResponseHandler} from "@enhavo/app/util/AxiosResponseHandler";
import axios, {AxiosResponse} from "axios";
import * as $ from "jquery";

export default class FormAction extends AbstractAction
{
    public static readonly OPEN = 'open';
    public static readonly DOWNLOAD = 'download';
    public static readonly RELOAD = 'reload';

    private url: string;
    private saveLabel: string;
    private viewKey: string;
    private target: string;
    private openRoute: string;
    private openRouteParameters: object = {};
    private openRouteMapping: object = {};
    private openType: string = FormAction.OPEN;

    private readonly view: View;
    private readonly router: Router;
    private readonly modalManager: ModalManager;
    private readonly translator: Translator;

    constructor(view: View, router: Router, modalManager: ModalManager, translator: Translator) {
        super();
        this.view = view;
        this.router = router;
        this.modalManager = modalManager;
        this.translator = translator;
    }

    execute(): void
    {
        let modalData = {
            component: 'ajax-form-modal',
            url: this.url,
            saveLabel: this.translator.trans(this.saveLabel),
            actionHandler: async (modal: AjaxFormModal, data: any) => {
                if (data.status === 200) {
                    if (this.openType === FormAction.RELOAD) {
                        window.location.reload()
                    } else if (this.openRoute) {
                        this.open(modal, data)
                    } else if (this.openType === FormAction.DOWNLOAD) {
                        this.download(modal, data)
                    }

                    return true;
                } else if (data.status === 400) {
                    let html = AxiosResponseHandler.getBody(data).trim();
                    modal.element = <HTMLElement>$.parseHTML(html)[0];
                    return false;
                }

                return false;
            }
        };
        this.modalManager.push(modalData);
    }

    protected open(modal: AjaxFormModal, responseData: AxiosResponse<any>)
    {
        let data = AxiosResponseHandler.getBody(responseData)

        let html = data.trim();
        modal.element = <HTMLElement>$.parseHTML(html)[0];

        let formData: any = modal.getFormData();
        let openRouteParameters = Object.assign({}, this.openRouteParameters);
        for (const [key, value] of Object.entries(this.openRouteMapping)) {
            openRouteParameters[key] = formData[value];
        }

        if (this.target === '_self') {
            openRouteParameters['view_id'] = this.view.getId();
        }

        let url = this.router.generate(this.openRoute, openRouteParameters);
        if (this.openType === FormAction.OPEN) {
            if (this.target === '_view') {
                this.view.open(url, this.viewKey);
            } else {
                window.open(url, this.target);
            }
        } else if(this.openType === FormAction.DOWNLOAD) {

            axios.post(url, {}, {
                responseType: 'arraybuffer'
            })
                .then((response) => {
                    AxiosResponseHandler.download(response);
                })
                .catch(function (response) {
                    console.error(response);
                });
        }
    }

    protected download(modal: AjaxFormModal, data: any)
    {
        AxiosResponseHandler.download(data);
    }
}
