<template>
    <div>
        <select style="width: 100%" :name="form.fullName" :ref="(el) => form.setElement(el as HTMLElement)" :multiple="form.multiple">
            <option selected v-for="item in form.value" :value="item.id">{{ item.text }}</option>
        </select>
    </div>
</template>

<script setup lang="ts">
import {onMounted} from "vue";
import {AutoCompleteForm} from "@enhavo/form/form/model/AutoCompleteForm";
import $ from "jquery";
import select2 from "select2";
import Sortable from 'sortablejs';

const props = defineProps<{
    form: AutoCompleteForm
}>()

const form = props.form;

function applySortable()
{
    let $list = $(form.element).find('ul.select2-selection__rendered');
    let listElement = <HTMLElement>$list.get(0);
    Sortable.create(listElement, {
        draggable: ".select2-search-choice",
        animation: 150,
        onUpdate: () => {
            // let data = <Array<any>>$element.select2('data');
            // let list = [];
            // for (let element of data) {
            //     list.push(element.id);
            // }
            // $element.val(list.join(','));
        },
        onEnd: () => {
            $list.find('.select2-search-field').appendTo($list.get(0));
        },
    });
}

function buildConfig()
{
    return {
        sorter: (data) => {
            return data;
        },
        debug: true,
        templateSelection: function (state: any) {
            if (form.multiple && form.editable) {
                return "<span class=\"icon icon-edit\" data-auto-complete-edit=\"" + state.id + "\"></span> " + state.text;
            } else {
                return state.text;
            }
        },
        minimumInputLength: form.minimumInputLength,
        ajax: {
            url: form.url,
            delay: 500,
            data: function (searchTerm: Select2QueryOptions, page: number) {
                return {
                    q: searchTerm.term,
                    page: page || 1
                };
            },
            results: function (data: any, page: any) {
                return data;
            },
            cache: true,
        },
        tags: form.multiple,
        placeholder: form.placeholder,
        allowClear: form.placeholder != null,
    };
}

onMounted(() => {
    select2($);
    $(form.element).select2(buildConfig());

    if (form.sortable) {
        applySortable();
    }
});
</script>
