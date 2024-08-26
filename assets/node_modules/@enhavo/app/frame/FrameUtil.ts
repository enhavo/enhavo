import * as pako from "pako";
import {Frame} from "./Frame";


export class FrameUtil
{
    public static getState(frames: Frame[]): string
    {
        const options: object[] = [];
        for (let frame of frames) {
            options.push(frame.getOptions());
        }

        let data = pako.deflate(JSON.stringify(options));
        return btoa(String.fromCharCode.apply(null, data));
    }

    public static getFramesOption(state: string): object[]
    {
        let data = atob(state);
        let uint8array = pako.inflate(data);
        let decoder = new TextDecoder();
        let stringData = decoder.decode(uint8array);
        let frames = JSON.parse(stringData);
        return frames;
    }
}
