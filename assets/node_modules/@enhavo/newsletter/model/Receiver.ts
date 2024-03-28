import Tracking from "@enhavo/newsletter/model/Tracking";

export default class Receiver
{
    tracking: Tracking[];
    sentAt: Date;
    email: string;

    public isRead(): boolean
    {
        for(let tracking of this.tracking) {
            if(tracking.type === 'open') {
                return true;
            }
        }
    }

    public getFirstReadDate(): Date
    {
        for(let tracking of this.tracking) {
            if(tracking.type === 'open') {
                return tracking.date;
            }
        }
    }

}
