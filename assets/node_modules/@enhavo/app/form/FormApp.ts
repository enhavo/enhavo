import ActionManager from "@enhavo/app/action/ActionManager";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import View from "@enhavo/app/view/View";
import FormData from "@enhavo/app/form/FormData";
import Translator from "@enhavo/core/Translator";
import ModalManager from "@enhavo/app/modal/ModalManager";
import Form from "@enhavo/app/form/Form";

export default class FormApp
{
    private data: FormData;
    private readonly eventDispatcher: FrameEventDispatcher;
    private readonly view: View;
    private readonly actionManager: ActionManager;
    private readonly translator: Translator;
    private readonly modalManager: ModalManager;
    private readonly form: Form;

    constructor(
        data: any,
        eventDispatcher: FrameEventDispatcher,
        view: View,
        actionManager: ActionManager,
        translator: Translator,
        modalManager: ModalManager,
        form: Form,
    ) {
        this.data = data;

        this.view = view;
        this.eventDispatcher = eventDispatcher;
        this.translator = translator;
        this.actionManager = actionManager;
        this.modalManager = modalManager;
        this.form = form;
    }

    init() {
        this.actionManager.init();
        this.modalManager.init();
        this.form.init();
        this.view.init();

        this.form.load();
        this.addCloseListener();
        this.view.ready();
        this.view.checkUrl();
    }

    private addCloseListener()
    {
        this.eventDispatcher.on('close', (event: CloseEvent) => {
            if(this.view.getId() === event.id) {

                if(this.data.formChanged) {
                    this.view.confirm(new Confirm(
                        this.translator.trans('enhavo_app.view.message.not_saved'),
                        () => {
                            event.resolve();
                            let id = this.view.getId();
                            this.eventDispatcher.dispatch(new RemoveEvent(id));
                        },
                        () => {
                            event.reject();
                        },
                        this.translator.trans('enhavo_app.view.label.cancel'),
                        this.translator.trans('enhavo_app.view.label.close')
                    ));
                } else {
                    event.resolve();
                    let id = this.view.getId();
                    this.eventDispatcher.dispatch(new RemoveEvent(id, event.saveState));
                }
            }
        });
    }
}
