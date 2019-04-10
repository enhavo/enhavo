import FormApp from "@enhavo/app/Form/FormApp";
import ActionManager from "@enhavo/app/Action/ActionManager";
import AbstractApplication from "@enhavo/app/AbstractApplication";
import AppInterface from "@enhavo/app/AppInterface";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";
import Form from "@enhavo/app/Form/Form";
import FormListener from "@enhavo/form/FormListener";
import FormRegistry from "@enhavo/form/FormRegistry";

export class FormApplication extends AbstractApplication implements ActionAwareApplication
{
    protected actionManager: ActionManager;
    protected actionRegistry: ActionRegistry;
    protected form: Form;
    protected formListener: FormListener;
    protected formRegistry: FormRegistry;

    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new FormApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
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

    public getForm(): Form
    {
        if(this.form == null) {
            this.form = new Form(
                this.getDataLoader().load(),
                this.getFormRegistry(),
                this.getFlashMessenger(),
                this.getEventDispatcher(),
                this.getView()
            );
        }
        return this.form;
    }

    public getFormListener(): FormListener
    {
        if(this.formListener == null) {
            this.formListener = new FormListener();
        }
        return this.formListener;
    }

    public getFormRegistry(): FormRegistry
    {
        if(this.formRegistry == null) {
            this.formRegistry = new FormRegistry(this);
            this.formRegistry.load();
        }
        return this.formRegistry;
    }
}

let application = new FormApplication();
export default application;