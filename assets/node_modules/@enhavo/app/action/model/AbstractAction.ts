import ActionInterface from "@enhavo/app/action/ActionInterface";

export default abstract class AbstractAction implements ActionInterface
{
    component: string;

    abstract execute(): void;
}