import Tracking from "@enhavo/newsletter/Model/Tracking";

export default class Receiver
{
    tracking: Tracking[];
    sentAt: Date;

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
