import { ComponentAwareInterface } from "@enhavo/app/model/ComponentAwareInterface";
import { ModelAwareInterface } from "@enhavo/app/model/ModelAwareInterface";

export interface BatchInterface extends ComponentAwareInterface, ModelAwareInterface
{
    key: string
    label: string
    execute(ids: number[]): Promise<boolean>;
}