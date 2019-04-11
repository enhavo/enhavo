import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import AppInterface from "@enhavo/app/AppInterface";
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";

export default class MediaLibraryApp extends AbstractViewApp implements AppInterface
{
    private actionManager: ActionManager;
    protected data: FormatData;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager)
    {
        super(loader, eventDispatcher, view);
        //loader.load().format = _.extend(loader.load().format, new FormatData);
        this.actionManager = actionManager;
    }
}