<template>
    <div class="wysiwyg-inline-menu-form wysiwyg-table-inline-modal">
        <div class="form-row wysiwyg-inline-menu-form-row">
            <label>{{ translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.label', [], 'javascript') }}</label>
        </div>
        <div class="wysiwyg-inline-menu-form-actions">
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addColumnBefore().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.column_before', [], 'javascript')"
            >
                <i class="icon icon-table_column_add_before"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addColumnAfter().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.column_after', [], 'javascript')"
            >
                <i class="icon icon-table_column_add_after"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().deleteColumn().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.column_delete', [], 'javascript')"
            >
                <i class="icon icon-table_column_delete"></i>
            </a>
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addRowBefore().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.row_before', [], 'javascript')"
            >
                <i class="icon icon-table_row_add_before"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().addRowAfter().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.row_after', [], 'javascript')"
            >
                <i class="icon icon-table_row_add_after"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().deleteRow().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.row_delete', [], 'javascript')"
            >
                <i class="icon icon-table_row_delete"></i>
            </a>
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().mergeCells().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.merge', [], 'javascript')"
            >
                <i class="icon icon-table_merge_cells"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().splitCell().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.split', [], 'javascript')"
            >
                <i class="icon icon-table_split_cells"></i>
            </a>
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().toggleHeaderRow().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.toggle_header_row', [], 'javascript')"
            >
                <i class="icon icon-table_toggle_header_row"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().toggleHeaderColumn().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.toggle_header_col', [], 'javascript')"
            >
                <i class="icon icon-table_toggle_header_column"></i>
            </a>
            <a class="wysiwyg-button large-icon"
               @click.prevent="form.editor.chain().focus().toggleHeaderCell().run()"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.toggle_header_cell', [], 'javascript')"
            >
                <i class="icon icon-table_toggle_header_cell"></i>
            </a>
            <div class="wysiwyg-inline-menu-form-spacer">&nbsp;</div>
            <a class="wysiwyg-button large-icon"
               @click.prevent="deleteTable"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.table.delete_table', [], 'javascript')"
            >
                <i class="icon icon-delete_forever"></i>
            </a>
        </div>
    </div>
</template>
<script setup lang="ts">
import {inject} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    form: WysiwygForm
}>();

function deleteTable()
{
    props.form.openModal('form-wysiwyg-modal-yes-no', {
            prompt: translator.trans('enhavo_form.wysiwyg_form.command.table.inline_form.delete_table_prompt', {}, 'javascript'),
        })
        .then(() => {
            props.form.editor.chain().focus().deleteTable().run();
        })
        .catch(() => {
            // Cancelled
        })
    ;
}

</script>