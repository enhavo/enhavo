import ActionManager from "@enhavo/app/Action/ActionManager";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default interface WidgetAwareApplication extends ApplicationInterface
{
    getWidgetManager(): ActionManager;
    getWidgetRegistry(): ActionRegistry;
}