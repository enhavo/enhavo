import DeleteApp from "@enhavo/app/Delete/DeleteApp";
import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";

export class DeleteApplication extends Application
{
    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new DeleteApp(this.getDataLoader(), this.getEventDispatcher(), this.getView());
        }
        return this.app;
    }
}

let application = new DeleteApplication();
export default application;