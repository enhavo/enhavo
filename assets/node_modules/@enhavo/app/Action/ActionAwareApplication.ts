import ActionManager from "@enhavo/app/Action/ActionManager";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";


export default interface ActionAwareApplication
{
    getActionManager(): ActionManager;
    getActionRegistry(): ActionRegistry;
}