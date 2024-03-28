import ActionInterface from "@enhavo/app/action/ActionInterface";

export default abstract class AbstractAction implements ActionInterface
{
    component: string;
    icon: string;
    label: string;

    abstract execute(): void;
}