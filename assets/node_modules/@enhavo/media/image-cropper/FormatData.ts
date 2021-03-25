
export default class FormatData
{
    url: string;
    x: number;
    y: number;
    width: number;
    height: number;
    ratio: number;
    rotate: number = 0;
    scaleX: number = 1;
    scaleY: number = 1;
    changed: boolean = false;

    getData() {
        if (this.x !== null && this.y !== null && this.width !== null && this.height !== null) {
            return {
                x: this.x,
                y: this.y,
                width: this.width,
                height: this.height,
                scaleX: this.scaleX,
                scaleY: this.scaleY,
                rotate: this.rotate,
            }
        }
        return null;
    }
}
