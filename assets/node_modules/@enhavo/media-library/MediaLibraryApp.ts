import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import View from "@enhavo/app/view/View";
import ActionManager from "@enhavo/app/action/ActionManager";
import MediaLibrary from "@enhavo/media-library/MediaLibrary";

export default class MediaLibraryApp
{
    private readonly flashMessenger: FlashMessenger;
    private readonly view: View;
    private readonly actionManager: ActionManager;
    private readonly mediaLibrary: MediaLibrary;

    public constructor(view: View, actionManager: ActionManager, flashMessenger: FlashMessenger, mediaLibrary: MediaLibrary)
    {
        this.view = view;
        this.flashMessenger = flashMessenger;
        this.actionManager = actionManager;
        this.mediaLibrary = mediaLibrary;
    }

    public init()
    {
        this.view.init();
        this.actionManager.init();
        this.flashMessenger.init();
        this.mediaLibrary.init();

        this.view.addDefaultCloseListener();
        this.view.ready();
    }
}
