import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface ColumnInterface extends ComponentAwareInterface, ModelAwareInterface
{
    sortable: boolean;
    sortingDirection: string;
    key: string;
    visibleCondition: string;
    visible: boolean;
    width: number;
    label: string;
    display: boolean;
    condition: string;

    isVisible(): boolean;
    checkVisibility(): void
}
