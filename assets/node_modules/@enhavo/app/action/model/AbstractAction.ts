import { ActionInterface } from "@enhavo/app/action/ActionInterface";

export abstract class AbstractAction implements ActionInterface
{
    component: string;
    model: string;
    icon: string;
    label: string;
    key: string
    abstract execute(): void;
}