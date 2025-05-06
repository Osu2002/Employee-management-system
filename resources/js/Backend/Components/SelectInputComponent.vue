<template>
    <div
        :class="'select2-' + id + '-wrapper col-6 mb-4'"
        v-if="isService == true"
    >
        <label class="form-label" :for="id" v-if="label"
            >{{ label }}
            <sup class="text-danger" v-if="isRequired">*</sup></label
        >
        <select
            class="form-select select2"
            :id="id"
            :multiple="isMultiple"
            v-model="model"
            :disabled="isDisabled"
            :required="isRequired"
            @change="handleChange"
        >
            <option value="">-- Select --</option>
            <option
                :value="option.id"
                v-for="option in options"
                :key="option.id"
                v-bind:selected="option.id == model"
            >
                {{
                    option.name +
                    " (" +
                    option.categories[0].name +
                    ") - Rs. " +
                    option.price +
                    ".00"
                }}
            </option>
        </select>
        <div class="text-danger">{{ error }}</div>
    </div>
    <div :class="'select2-' + id + '-wrapper col-6 mb-4'" v-else>
        <label class="form-label" :for="id" v-if="label"
            >{{ label }}
            <sup class="text-danger" v-if="isRequired">*</sup></label
        >
        <select
            class="form-select select2"
            :id="id"
            :multiple="isMultiple"
            v-model="model"
            :disabled="isDisabled"
            :required="isRequired"
            @change="handleChange"
        >
            <option value="">-- Select --</option>
            <option
                :value="option.id"
                v-for="option in options"
                :key="option.id"
                v-bind:selected="option.id == model"
            >
                {{ option.name }}
            </option>
        </select>
        <div class="text-danger">{{ error }}</div>
    </div>
</template>

<script>
import { ref } from "vue";

export default {
    props: {
        id: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            default: null,
        },
        placeholder: {
            type: String,
            default: "",
        },
        error: {
            type: String,
            default: "",
        },
        isRequired: {
            type: Boolean,
            default: false,
        },
        isMultiple: {
            type: Boolean,
            default: false,
        },
        isDisabled: {
            type: Boolean,
            default: false,
        },
        isService: {
            type: Boolean,
            default: false,
        },
        options: {
            type: Array,
            default: [],
        },
        modelValue: {
            default: "",
        },
        showSearch: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            model: this.modelValue,
            input: null,
        };
    },
    emits: ["update:model-value", "onChange"],
    mounted() {
        var self = this;
        self.input = $(this.$el)
            .find("select")
            .select2({
                placeholder: this.placeholder,
                minimumResultsForSearch: this.showSearch ? 1 : Infinity,
                dropdownParent: $(this.$el).parent(),
            })
            .on("select2:select select2:unselect", (evt) => {
                self.$emit("update:modelValue", $(evt.target).val());
                self.$emit("onChange", $(evt.target).val());
            });
    },
    beforeUnmount() {
        this.input.select2("destroy");
    },

    watch: {
        modelValue(currentValue) {
            this.model = currentValue;
            this.input.val([currentValue]);
            this.input.trigger("change");
        },
    },
};
</script>

<style scoped></style>
