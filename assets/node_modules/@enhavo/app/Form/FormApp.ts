import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import AppInterface from "@enhavo/app/AppInterface";
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import RemoveEvent from "@enhavo/app/ViewStack/Event/RemoveEvent";
import FormData from "@enhavo/app/form/FormData";
import Confirm from "@enhavo/app/View/Confirm";
import Translator from "@enhavo/core/Translator";

export default class FormApp extends AbstractViewApp implements AppInterface
{
    private actionManager: ActionManager;
    private translator: Translator;
    protected data: FormData;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager, translator: Translator)
    {
        super(loader, eventDispatcher, view);
        this.actionManager = actionManager;
        this.translator = translator;
    }

    protected addCloseListener()
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
                    this.eventDispatcher.dispatch(new RemoveEvent(id));
                }
            }
        });
    }
}