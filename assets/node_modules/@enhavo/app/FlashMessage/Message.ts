

export default class Message
{
    static SUCCESS = 'success';
    static ERROR = 'error';
    static NOTICE = 'notice';
    static WARNING = 'warning';

    public message: string;
    public type: string;
    public ttl: number = 5;

    constructor(type: string = null, message: string = null) {
        if(type) {
            this.type = type;
        }
        if(message) {
            this.message = message;
        }
    }
}