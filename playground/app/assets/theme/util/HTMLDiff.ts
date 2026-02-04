import * as $ from "jquery";

export class HTMLDiff
{
    public static isEqual(a: HTMLElement, b: HTMLElement): boolean
    {
        let $a = $(a);
        let $b = $(b);

        if ($a.text() !== $b.text()) {
            return false;
        } else if (!HTMLDiff.containSameAttributes(a, b)) {
            return false;
        } else if (!HTMLDiff.containSameAttributes(b, a)) {
            return false;
        } else if ($a.children().length !== $b.children().length) {
            return false;
        } else {
            for (let index in $a.children().toArray()) {
                let childA = $a.children().toArray()[index];
                let childB = $b.children().toArray()[index];
                if (!HTMLDiff.isEqual(childA, childB)) {
                    return false;
                }
            }
        }

        return true;
    }

    private static containSameAttributes(a: HTMLElement, b: HTMLElement): boolean
    {
        if (a.attributes.length !== b.attributes.length) {
            return false;
        }

        for (let i = 0; i < a.attributes.length; i++) {
            let attributeA = a.attributes[i];

            let exists = false;
            for (let n = 0; n < b.attributes.length; n++) {
                let attributeB = b.attributes[i];
                if (attributeB.name === attributeA.name && attributeB.value === attributeA.value) {
                    exists = true;
                    break;
                }
            }

            if (!exists) {
                return false;
            }
        }

        return true;
    }
}
