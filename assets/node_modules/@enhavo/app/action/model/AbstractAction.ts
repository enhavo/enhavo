import { ActionInterface } from "@enhavo/app/action/ActionInterface";

export abstract class AbstractAction implements ActionInterface
{
    component: string = 'action-action';
    model: string;
    icon: string;
    label: string;
    key: string
    class: string;
    abstract execute(): void;
    morph(source: ActionInterface): void {}
    mounted(): void {}
}
