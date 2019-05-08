import LoginApp from "@enhavo/user/Login/LoginApp";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export class LoginApplication extends AbstractApplication implements ActionAwareApplication
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