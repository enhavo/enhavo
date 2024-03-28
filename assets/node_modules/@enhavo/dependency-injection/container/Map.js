
export default class Map
{
    constructor() {
        /** @type {Entry[]} */
        this.data = [];
    }

    add(key, value)
    {
        let index = this._getIndex(key);
        if (index === -1) {
            this.data.push(new Entry(key, value));
            return;
        }

        this.data[index].setValue(value);
    }

    getKeys()
    {
        let keys = [];
        for (let entry of this.data) {
            keys.push(entry.getKey());
        }
        return keys;
    }

    getValues()
    {
        let values = [];
        for (let entry of this.data) {
            values.push(entry.getValue());
        }
        return values;
    }

    /**
     * @param {string} key
     */
    get(key) {
        let index = this._getIndex(key);
        if (index !== -1) {
            return this.data[index].getValue();
        }
        return null;
    }

    /**
     * @param {string} key
     */
    has(key) {
        return this.get(key) !== null;
    }

    /**
     * @returns {number}
     * @private
     */
    _getIndex(key) {
        for (let i in this.data) {
            if (this.data[i].getKey() === key) {
                return i;
            }
        }
        return -1;
    }
}

class Entry
{
    constructor(key, value = null) {
        this._key = key;
        this._value = value;
    }

    getKey() {
        return this._key;
    }

    setKey(value) {
        this._key = value;
    }

    getValue() {
        return this._value;
    }

    setValue(value) {
        this._value = value;
    }
}
