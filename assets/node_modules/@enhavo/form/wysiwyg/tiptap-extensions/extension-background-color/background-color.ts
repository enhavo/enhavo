import '@tiptap/extension-text-style';

import { Extension } from '@tiptap/core';

export type ColorOptions = {
    /**
     * The types where the color can be applied
     * @default ['textStyle']
     * @example ['heading', 'paragraph']
     */
    types: string[],
}

declare module '@tiptap/core' {
    interface Commands<ReturnType> {
        backgroundColor: {
            /**
             * Set the background color
             * @param backgroundColor The color to set
             * @example editor.commands.setColor('red')
             */
            setBackgroundColor: (backgroundColor: string) => ReturnType,

            /**
             * Unset the background color
             * @example editor.commands.unsetColor()
             */
            unsetBackgroundColor: () => ReturnType,
        }
    }
}

/**
 * This extension allows you to set the background color your text. Derived from the official extension "color"
 */
export const BackgroundColor = Extension.create<ColorOptions>({
    name: 'backgroundColor',

    addOptions() {
        return {
            types: ['textStyle'],
        }
    },

    addGlobalAttributes() {
        return [
            {
                types: this.options.types,
                attributes: {
                    backgroundColor: {
                        default: null,
                        parseHTML: element => element.style.backgroundColor?.replace(/['"]+/g, ''),
                        renderHTML: attributes => {
                            if (!attributes.backgroundColor) {
                                return {}
                            }

                            return {
                                style: `background-color: ${attributes.backgroundColor}`,
                            }
                        },
                    },
                },
            },
        ]
    },

    addCommands() {
        return {
            setBackgroundColor: (backgroundColor) => ({ chain }) => {
                return chain()
                    .setMark('textStyle', { backgroundColor })
                    .run()
            },
            unsetBackgroundColor: () => ({ chain }) => {
                return chain()
                    .setMark('textStyle', { backgroundColor: null })
                    .removeEmptyTextStyle()
                    .run()
            },
        }
    },
});
