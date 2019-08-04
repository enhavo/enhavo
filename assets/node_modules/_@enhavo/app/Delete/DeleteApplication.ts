import DeleteApp from "@enhavo/app/Delete/DeleteApp";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";

export class DeleteApplication extends AbstractApplication
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