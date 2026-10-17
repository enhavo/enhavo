import { AbstractAction } from "@enhavo/app/action/model/AbstractAction";
import View from "@enhavo/app/view/View";
import ModalManager from "@enhavo/app/modal/ModalManager";
import {Router} from "@enhavo/framework/router/Router";
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal";
import {Translator} from "@enhavo/framework/translator/Translator";
import {ResponseHandler} from "@enhavo/framework/util/ResponseHandler";
import $ from "jquery";

export class FormAction extends AbstractAction
{
    public static readonly OPEN = 'open';
    public static readonly DOWNLOAD = 'download';
    public static readonly RELOAD = 'reload';

    private url: string;
    private saveLabel: string;
    private frameKey: string;
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
            actionHandler: async (modal: AjaxFormModal, response: Response) => {
                if (response.status === 200) {
                    if (this.openType === FormAction.RELOAD) {
                        window.location.reload()
                    } else if (this.openRoute) {
                        await this.open(modal, response)
                    } else if (this.openType === FormAction.DOWNLOAD) {
                        await this.download(modal, response)
                    }

                    return true;
                } else if (response.status === 400) {
                    let html = (await ResponseHandler.getBody(response)).trim();
                    modal.element = <HTMLElement>$.parseHTML(html)[0];
                    return false;
                }

                return false;
            }
        };
        this.modalManager.push(modalData);
    }

    protected async open(modal: AjaxFormModal, response: Response)
    {
        let data = await ResponseHandler.getBody(response)

        let html = data.trim();
        modal.element = <HTMLElement>$.parseHTML(html)[0];

        let formData: any = modal.getFormData();
        let openRouteParameters = Object.assign({}, this.openRouteParameters);
        for (const [key, value] of Object.entries(this.openRouteMapping)) {
            openRouteParameters[key] = formData.get(value);
        }

        if (this.target === '_self') {
            openRouteParameters['view_id'] = this.view.getId();
        }

        let url = this.router.generate(this.openRoute, openRouteParameters);
        if (this.openType === FormAction.OPEN) {
            if (this.target === '_frame') {
                this.view.open(url, this.frameKey);
            } else {
                window.open(url, this.target);
            }
        } else if(this.openType === FormAction.DOWNLOAD) {

            fetch(url, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({}),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return ResponseHandler.download(response);
                })
                .catch(function (response) {
                    console.error(response);
                });
        }
    }

    protected download(modal: AjaxFormModal, response: Response)
    {
        return ResponseHandler.download(response);
    }
}
