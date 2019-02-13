
export default class Event
{
    name: string;
    origin: any;
    target: any;
    history: string[] = [];

    constructor(name: string) {
        this.name = name;
    }
}