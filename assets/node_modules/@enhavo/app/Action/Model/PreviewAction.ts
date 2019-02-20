import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class PreviewAction implements ActionInterface
{
    component: string;

    execute(): void
    {
        console.log('preview');
    }
}