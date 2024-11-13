<template>
    <div :key="form.id" v-show="form.isVisible()" >

        <input type="hidden"
           :name="form.fullName"
           :required="form.required"
           :disabled="form.disabled"
           v-model="form.value"
           :ref="(el) => form.setElement(el as HTMLElement)"
           @change="form.dispatchChange()"
        />

        <div v-if="form.editor">
            <button @click.prevent="form.editor.chain().focus().toggleBold().run()" :disabled="!form.editor.can().chain().focus().toggleBold().run()" :class="{ 'is-active': form.editor.isActive('bold') }">
                Bold
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleItalic().run()" :disabled="!form.editor.can().chain().focus().toggleItalic().run()" :class="{ 'is-active': form.editor.isActive('italic') }">
                Italic
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleStrike().run()" :disabled="!form.editor.can().chain().focus().toggleStrike().run()" :class="{ 'is-active': form.editor.isActive('strike') }">
                Strike
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleCode().run()" :disabled="!form.editor.can().chain().focus().toggleCode().run()" :class="{ 'is-active': form.editor.isActive('code') }">
                Code
            </button>
            <button @click.prevent="form.editor.chain().focus().unsetAllMarks().run()">
                Clear marks
            </button>
            <button @click.prevent="form.editor.chain().focus().clearNodes().run()">
                Clear nodes
            </button>
            <button @click.prevent="form.editor.chain().focus().setParagraph().run()" :class="{ 'is-active': form.editor.isActive('paragraph') }">
                Paragraph
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="{ 'is-active': form.editor.isActive('heading', { level: 1 }) }">
                H1
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': form.editor.isActive('heading', { level: 2 }) }">
                H2
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleHeading({ level: 3 }).run()" :class="{ 'is-active': form.editor.isActive('heading', { level: 3 }) }">
                H3
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleHeading({ level: 4 }).run()" :class="{ 'is-active': form.editor.isActive('heading', { level: 4 }) }">
                H4
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleHeading({ level: 5 }).run()" :class="{ 'is-active': form.editor.isActive('heading', { level: 5 }) }">
                H5
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleHeading({ level: 6 }).run()" :class="{ 'is-active': form.editor.isActive('heading', { level: 6 }) }">
                H6
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': form.editor.isActive('bulletList') }">
                Bullet list
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleOrderedList().run()" :class="{ 'is-active': form.editor.isActive('orderedList') }">
                Ordered list
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleCodeBlock().run()" :class="{ 'is-active': form.editor.isActive('codeBlock') }">
                Code block
            </button>
            <button @click.prevent="form.editor.chain().focus().toggleBlockquote().run()" :class="{ 'is-active': form.editor.isActive('blockquote') }">
                Blockquote
            </button>
            <button @click.prevent="form.editor.chain().focus().setHorizontalRule().run()">
                Horizontal rule
            </button>
            <button @click.prevent="form.editor.chain().focus().setHardBreak().run()">
                Hard break
            </button>
            <button @click.prevent="form.editor.chain().focus().undo().run()" :disabled="!form.editor.can().chain().focus().undo().run()">
                Undo
            </button>
            <button @click.prevent="form.editor.chain().focus().redo().run()" :disabled="!form.editor.can().chain().focus().redo().run()">
                Redo
            </button>
        </div>

        <editor-content :editor="form.editor" class="editor" />
    </div>
</template>

<script setup lang="ts">
import {onMounted, onBeforeUnmount} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {EditorContent} from '@tiptap/vue-3'

const props = defineProps<{
    form: WysiwygForm
}>()

onMounted(() => {
    props.form.initEditor()
});

onBeforeUnmount(() => {
    props.form.destroyEditor();
});

</script>

<style>
.editor strong {font-weight: bold}
</style>
