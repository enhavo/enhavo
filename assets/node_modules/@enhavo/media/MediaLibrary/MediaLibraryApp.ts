import ActionManager from "@enhavo/app/Action/ActionManager";
import View from "@enhavo/app/View/View";
import MediaLibrary from "@enhavo/media/MediaLibrary/MediaLibrary";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";

export default class MediaLibraryApp
{
    private readonly view: View;
    private readonly mediaLibrary: MediaLibrary;
    private readonly actionManager: ActionManager;
    private readonly flashMessenger: FlashMessenger;

    constructor(view: View, actionManager: ActionManager, mediaLibrary: MediaLibrary, flashMessenger: FlashMessenger)
    {
        this.view = view;
        this.actionManager = actionManager;
        this.mediaLibrary = mediaLibrary;
        this.flashMessenger = flashMessenger;
    }

    init() {
        this.view.init();
        this.actionManager.init();
        this.mediaLibrary.init();
        this.flashMessenger.init();

        this.view.addDefaultCloseListener();
        this.view.ready();
    }
}
