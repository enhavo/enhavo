import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/framework/model/ModelAwareInterface";

export interface DashboardWidgetInterface extends ComponentAwareInterface, ModelAwareInterface
{
    key: string;
    width: number;
    row: number;
    position: number;
}
