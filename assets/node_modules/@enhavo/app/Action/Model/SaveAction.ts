import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class SaveAction implements ActionInterface
{
    component: string;

    execute(): void
    {
        console.log('save');
    }
}