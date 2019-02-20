import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class FilterAction implements ActionInterface
{
    component: string;

    execute(): void
    {
        console.log('filter');
    }
}