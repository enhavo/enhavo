import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default abstract class AbstractAction implements ActionInterface
{
    component: string;

    abstract execute(): void;
}