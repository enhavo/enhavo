import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import AppInterface from "@enhavo/app/AppInterface";
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/ViewStack/View";

export default class FormApp extends AbstractViewApp implements AppInterface
{
    private actionManager: ActionManager;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager)
    {
        super(loader, eventDispatcher, view);
        this.actionManager = actionManager;
    }
}