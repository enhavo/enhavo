import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import AppInterface from "@enhavo/app/AppInterface";
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import DataEvent from "@enhavo/app/ViewStack/Event/DataEvent";
import * as $ from "jquery"

export default class PreviewApp extends AbstractViewApp implements AppInterface
{
    private actionManager: ActionManager;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager)
    {
        super(loader, eventDispatcher, view);
        this.actionManager = actionManager;

        eventDispatcher.on('data', (event: DataEvent) => {
            if(event.id == view.getId()) {
                this.data.inputs = event.data;
                // delay submit so vue has time to update form
                setTimeout(() => {
                    this.submit();
                }, 500);
            }
        });
    }

    submit()
    {
        $('form').submit();
    }
}