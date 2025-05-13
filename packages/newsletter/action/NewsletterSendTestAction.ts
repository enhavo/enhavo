import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {AjaxFormModal} from "@enhavo/app/modal/model/AjaxFormModal";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";
import {ModalManager} from "@enhavo/app/modal/ModalManager";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";

export class NewsletterSendTestAction extends AbstractAction
{
    public form: any;

    constructor(
        private readonly flashMessenger: FlashMessenger,
        private readonly translator: Translator,
        private readonly modalManager: ModalManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly formFactory: FormFactory,
    ) {
        super();
    }

    execute(): void
    {
        this.modalManager.push({
            model: 'FormModal',
            form: this.form,
            actionHandler: (modal: AjaxFormModal, data: any, error: string) => {
                return new Promise((resolve, reject) => {
                    if (data.status == 400) {
                        this.flashMessenger.error(data.data.message);
                        resolve(false);
                        return;
                    } else if (error) {
                        this.flashMessenger.error(this.translator.trans(error));
                        resolve(false);
                        return;
                    }
                    this.flashMessenger.add(data.data.message, data.data.type);
                    resolve(true);
                })
            }
        });
    }
}