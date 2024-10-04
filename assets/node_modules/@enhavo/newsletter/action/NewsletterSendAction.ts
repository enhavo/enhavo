import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";
import axios from "axios";
import {Translator} from "@enhavo/app/translation/Translator";
import {Router} from "@enhavo/app/routing/Router";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher";

export class NewsletterSendAction extends AbstractAction
{
    public resourceId: number;
    private formChanged: boolean = false;

    constructor(
        private readonly translator: Translator,
        private readonly router: Router,
        private readonly flashMessenger: FlashMessenger,
        private readonly uiManager: UiManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly formEventDispatcher: FormEventDispatcher,
    ) {
        super();
    }

    execute(): void
    {
        if (this.formChanged) {
            this.uiManager.alert(this.translator.trans('enhavo_newsletter.send.message.form_changed'));
            return;
        } else {
            this.confirmAndSend();
        }
    }

    private confirmAndSend()
    {
        this.uiManager.confirm({
            message: this.translator.trans('enhavo_newsletter.send.message.confirm'),
            denyLabel: this.translator.trans('enhavo_app.cancel'),
            acceptLabel: this.translator.trans('enhavo_newsletter.send.action.send'),
        }).then(() => {

        });
    }

    private send()
    {
        this.uiManager.loading(true);
        let url = this.router.generate('enhavo_newsletter_newsletter_send', {
            id: this.resourceId,
        });
        axios
            .post(url, {})
            .then((data) => {
                this.uiManager.loading(false);
                this.flashMessenger.add(data.data.message, data.data.type);
            })
            .catch((error) => {
                this.uiManager.loading(false);
                if(error.response.status == 400) {
                    this.flashMessenger.error(error.response.data.message);
                } else {
                    this.flashMessenger.error(this.translator.trans('enhavo_app.error'));
                }
            })
    }

    private mounted(): void
    {
        this.resourceInputManager.onLoaded().then(() => {
            this.formEventDispatcher.on('change', () => {
                this.formChanged = true;
            });
        });
    }
}

