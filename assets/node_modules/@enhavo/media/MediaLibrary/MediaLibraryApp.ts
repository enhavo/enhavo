import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import AppInterface from "@enhavo/app/AppInterface";
import ViewApp from "@enhavo/app/ViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import MediaLibrary from "@enhavo/media/MediaLibrary/MediaLibrary";

export default class MediaLibraryApp extends ViewApp implements AppInterface
{
    private actionManager: ActionManager;
    private mediaLibrary: MediaLibrary;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager, mediaLibrary: MediaLibrary)
    {
        super(loader, eventDispatcher, view);
        this.actionManager = actionManager;
        this.mediaLibrary = mediaLibrary;
    }
}