import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class CloseAction implements ActionInterface
{
    component: string;

    execute(): void
    {
        console.log('close');
    }
}