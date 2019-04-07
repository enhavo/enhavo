

export default class Message
{
    static SUCCESS = 'success';
    static ERROR = 'error';
    static NOTICE = 'notice';
    static WARNING = 'warning';

    public message: string;
    public type: string;
    public ttl: number = 5;
}