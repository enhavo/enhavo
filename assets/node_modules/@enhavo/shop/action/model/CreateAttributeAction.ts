import AbstractAction from "@enhavo/app/action/model/AbstractAction";
import View from "@enhavo/app/view/View";
import ModalManager from "@enhavo/app/modal/ModalManager";
import Router from "@enhavo/core/Router";
import AjaxFormModal from "@enhavo/app/modal/model/AjaxFormModal";
import Translator from "@enhavo/core/Translator";

export default class CreateAttributeAction extends AbstractAction
{
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
            route: "sylius_product_attribute_create_form",
            actionRoute: "sylius_product_attribute_create_form",
            saveLabel: this.translator.trans('enhavo_app.create'),
            actionHandler: async (modal: AjaxFormModal, data: any) => {
                data.data.trim();
                let html = data.data.trim();
                modal.element = <HTMLElement>$.parseHTML(html)[0];

                if (data.status === 200) {
                    let formData: any = modal.getFormData();
                    let url = this.router.generate("sylius_product_attribute_create", {
                        'type': formData.get('product_attribute_type')
                    });
                    this.view.open(url, 'edit-view');
                    return true;
                }

                return false;
            }
        };
        this.modalManager.push(modalData);
    }
}
