import LoginApp from "@enhavo/user/Login/LoginApp";
import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export class LoginApplication extends Application implements ActionAwareApplication
{
    constructor()
    {
        super();
    }

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new LoginApp(this.getDataLoader(), this.getEventDispatcher(), this.getView());
        }
        return this.app;
    }
}

let application = new LoginApplication();
export default application;