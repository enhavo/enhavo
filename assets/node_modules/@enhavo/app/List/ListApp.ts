import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";

export default class ListApp extends AbstractViewApp
{
    private actionManager: ActionManager;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager)
    {
        super(loader, eventDispatcher, view);
        this.actionManager = actionManager;
    }
}