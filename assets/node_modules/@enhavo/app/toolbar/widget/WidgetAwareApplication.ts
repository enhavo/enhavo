import ActionManager from "@enhavo/app/action/ActionManager";
import ActionRegistry from "@enhavo/app/action/ActionRegistry";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default interface WidgetAwareApplication extends ApplicationInterface
{
    getWidgetManager(): ActionManager;
    getWidgetRegistry(): ActionRegistry;
}