import ActionManager from "@enhavo/app/action/ActionManager";
import View from "@enhavo/app/view/View";
import MediaLibrary from "@enhavo/media/media-library/MediaLibrary";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";

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
