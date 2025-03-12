import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {ResourceInputManager} from "../../manager/ResourceInputManager";
import {UiManager} from "@enhavo/app/ui/UiManager";
import {FlashMessenger, FlashMessage} from "@enhavo/app/flash-message/FlashMessenger";
import {Translator} from "@enhavo/app/translation/Translator";
import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {InputChangedEvent} from "@enhavo/app/manager/ResourceInputManager";
import {ClientInterface} from "@enhavo/app/client/ClientInterface";

export class AutoSaveAction extends AbstractAction
{
    public url: string;
    public on: boolean = true;
    public timeoutId: number;
    public timeout: number;
    public iconSync: string;
    public iconInactive: string;

    constructor(
        private readonly flashMessenger: FlashMessenger,
        private readonly uiManager: UiManager,
        private readonly resourceInputManager: ResourceInputManager,
        private readonly translator: Translator,
        private readonly formEventDispatcher: FormEventDispatcher,
        private readonly frameManager: FrameManager,
        private readonly client: ClientInterface,
    ) {
        super();
    }

    async execute(): Promise<void>
    {
        if (!this.iconSync) {
            this.iconSync = this.icon;
        }

        if (this.on) {
            this.on = false;
            this.icon = this.iconInactive;
        } else {
            this.on = true;
            this.icon = this.iconSync;
        }
    }

    async mounted(): Promise<void>
    {
        this.formEventDispatcher.on('change', () => {
            if (this.on) {
                this.addTimout();
            }
        });

        this.frameManager.on('input_changed', () => {
            this.removeTimeout()
        });
    }

    private addTimout()
    {
        this.removeTimeout()

        this.timeoutId = window.setTimeout(async () => {
            if (!this.on) {
                return;
            }

            const transport = await this.resourceInputManager.save(null, true);

            if (transport.ok && transport.response.ok) {
                this.frameManager.dispatch(new InputChangedEvent(this.resourceInputManager.resource));
                this.flashMessenger.addMessage(new FlashMessage(this.translator.trans('enhavo_app.auto_save', {}, 'javascript'), FlashMessage.SUCCESS));
            } else {
                this.client.handleError(transport, {
                    validation: true,
                });
            }
        }, this.timeout * 1000);
    }

    private removeTimeout()
    {
        if (this.timeoutId) {
            window.clearTimeout(this.timeoutId);
            this.timeoutId = null;
        }
    }
}
