import MediaLibraryApp from "@enhavo/media/MediaLibrary/MediaLibraryApp";
import ActionManager from "@enhavo/app/Action/ActionManager";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import MediaLibrary from "@enhavo/media/MediaLibrary/MediaLibrary";

export class MediaLibraryApplication extends AbstractApplication implements ActionAwareApplication
{
    protected actionManager: ActionManager;
    protected actionRegistry: ActionRegistry;
    protected mediaLibrary: MediaLibrary;

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new MediaLibraryApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager(), this.getMediaLibrary());
        }
        return this.app;
    }

    public getActionManager(): ActionManager
    {
        if(this.actionManager == null) {
            this.actionManager = new ActionManager(this.getDataLoader().load().actions, this.getActionRegistry());
        }
        return this.actionManager;
    }

    public getActionRegistry(): ActionRegistry
    {
        if(this.actionRegistry == null) {
            this.actionRegistry = new ActionRegistry;
            this.actionRegistry.load(this);
        }
        return this.actionRegistry;
    }

    public getMediaLibrary(): MediaLibrary
    {
        if(this.mediaLibrary == null) {
            this.mediaLibrary = new MediaLibrary(this.getDataLoader().load().media, this.getRouter(), this.getEventDispatcher(), this.getView(), this.getTranslator());
        }
        return this.mediaLibrary;
    }
}

let application = new MediaLibraryApplication();
export default application;