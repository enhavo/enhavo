import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class DropdownAction implements ActionInterface
{
    component: string;

    execute(): void
    {
        console.log('dropdown');
    }
}