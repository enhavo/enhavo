import {ActionManager} from "@enhavo/app/action/ActionManager";
import {ActionFactory} from "@enhavo/app/action/ActionFactory";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default interface WidgetAwareApplication extends ApplicationInterface
{
    getWidgetManager(): ActionManager;
    getWidgetRegistry(): ActionFactory;
}