import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class DeleteAction implements ActionInterface
{
    component: string;

    execute(): void
    {
        console.log('delete');
    }
}