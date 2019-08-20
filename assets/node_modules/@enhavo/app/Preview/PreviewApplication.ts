import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import PreviewApp from "@enhavo/app/Preview/PreviewApp";

export class PreviewApplication extends Application implements ActionAwareApplication
{
    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new PreviewApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
        }
        return this.app;
    }
}

let application = new PreviewApplication();
export default application;