<template>
    <div :class="className" v-if="isService">
        <label :for="id" class="form-label">{{ label }} <sup v-if="isRequired">*</sup></label>
        <select class="select2 form-control form-select" :id="id" :multiple="isMultiple" v-model="model"
            placeholder="--Select--" :required="isRequired">
            <option :value="option.id" v-for="option in options" :key="option.id">
                {{ option.name + ' (' + option.categories[0].name + ') - Rs. ' + option.price + '.00' }}
            </option>
        </select>
        <div class="text-danger">
            {{ error }}
        </div>
    </div>
    <div :class="className" v-else>
        <label :for="id" class="form-label">{{ label }} <sup v-if="isRequired">*</sup></label>
        <select class="select2 form-control form-select" :id="id" :multiple="isMultiple" v-model="model"
            placeholder="--Select--" :required="isRequired">
            <option :value="option.id" v-for="option in options" :key="option.id">
                {{ option.name }}
            </option>
        </select>
        <div class="text-danger">
            {{ error }}
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true,
        },
        className: {
            type: String,
            default: "mb-4 col-md-6"
        },
        label: {
            type: String,
            required: true,
        },
        error: {
            type: String,
            default: "",
        },
        isRequired: {
            type: Boolean,
            default: true,
        },
        isMultiple: {
            type: Boolean,
            default: false,
        },
        isService: {
            type: Boolean,
            default: false,
        },
        options: {
            type: Array,
            default: () => [],
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
        };
    },
    emits: ['update:modelValue', 'onChange'],
    mounted() {
        this.initSelect2();
    },
    methods: {
        initSelect2() {
            const vm = this;
            $(this.$el).find('.select2').select2({
                placeholder: this.placeholder,
                minimumResultsForSearch: this.showSearch ? 1 : Infinity,
                dropdownParent: $(this.$el).parent(),
            }).on('change', function() {
                const selectedValues = $(this).val();
                if (vm.model !== selectedValues) {
                    vm.model = selectedValues;
                    vm.$emit('update:modelValue', selectedValues);
                    vm.$emit('onChange', selectedValues);
                }
            });
        }
    },
    watch: {
        modelValue(newValue) {
            if (this.model !== newValue) {
                this.model = newValue;
                $(this.$el).find('.select2').val(newValue).trigger('change');
            }
        }
    },
};
</script>
