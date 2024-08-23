export class Frame
{
    id: string;
    url: string;
    key: string;
    label: string = '';
    priority: number = 0;
    width: string = null;
    loaded: boolean = false;
    minimize: boolean = false;
    keepMinimized: boolean = false;
    focus: boolean = false;
    removed: boolean = false;
    closeable: boolean = true;
    position: number = 0;
    keepAlive: boolean = false;
    display: boolean = true;
    parameters: object;

    private children: string[] = [];
}
