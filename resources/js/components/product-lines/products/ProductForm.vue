<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} product</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.brand" label="Brand" variant="outlined" :error-messages="errors.get('save-product.brand')"/>
                    <v-text-field class="my-4" v-model="formData.model" label="Model" variant="outlined" :error-messages="errors.get('save-product.model')"/>
                    <v-text-field class="my-4" v-model="formData.name" label="Name" variant="outlined" :error-messages="errors.get('save-product.name')"/>
                    <v-row>
                        <v-col cols="4">
                            <v-combobox
                                class="my-4"
                                v-model="formData.currency_code"
                                label="Currency code"
                                variant="outlined"
                                :items="currencyCodes"
                                :error-messages="errors.get('currency_code')"
                            ></v-combobox>
                        </v-col>
                        <v-col cols="8">
                            <v-text-field class="my-4" v-model="formData.unit_price" type="number" label="Price" variant="outlined" :error-messages="errors.get('save-product.unit_price')"/>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text="Close" @click="$emit('close')"/>
                    <v-btn type="submit" color="primary">Save</v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </div>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';
import { useRequestStore } from '@/store/requestStore.js';
import { useMscStore } from '@/store/mscStore.js'

const mscStore = useMscStore()
const props = defineProps(['product', 'productLineId'])
const emit = defineEmits(['close', 'save'])
const errors = useRequestStore()
const currencyCodes = mscStore.currencyCodes

errors.clear()

let action = 'create'
const saving = ref(false)
const formData = ref({
    brand: null,
    model: null,
    name: null,
    currency_code: 'PHP',
    unit_price: 0,
})

const submit = () => {
    errors.clear()
    saving.value = true

    const url = (props.product ? `/api/products/${props.product.id}/${action}` : `/api/products/${action}`) + '?tag=save-product'

    formData.value.product_line_id = props.productLineId

    axios.post(url, formData.value).then(res => {
        emit('close')
        emit('save', {
            action: action,
            product: res.data
        });
    }).finally(() => {
        saving.value = false
    })
}

watch(() => props.product, (newProduct) => {
    if(newProduct) {
        formData.value.brand = newProduct.brand;
        formData.value.model = newProduct.model;
        formData.value.name = newProduct.name;
        formData.value.unit_price = newProduct.unit_price;
        formData.value.currency_code = newProduct.currency_code;
        action = 'update'
    } else {
        formData.value.brand = null;
        formData.value.model = null;
        formData.value.name = null;
        formData.value.unit_price = null;
        formData.value.currency_code = 'PHP';
        action = 'create'
    }
}, {immediate: true})
</script>
