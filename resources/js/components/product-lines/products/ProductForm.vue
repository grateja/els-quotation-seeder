<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} product</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.brand" label="Brand" variant="outlined" :error-messages="errors.get('brand')"/>
                    <v-text-field class="my-4" v-model="formData.model" label="Model" variant="outlined" :error-messages="errors.get('model')"/>
                    <v-text-field class="my-4" v-model="formData.name" label="Name" variant="outlined" :error-messages="errors.get('name')"/>
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
                            <v-text-field class="my-4" v-model="formData.unit_price" type="number" label="Price" variant="outlined" :error-messages="errors.get('unit_price')"/>
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
<script>
export default {
    props: [
        'product', 'productLineId'
    ],
    data: () => {
        return {
            formData: {
                brand: null,
                model: null,
                name: null,
                unit_price: 0,
                currency_code: 'PHP'
            }
        }
    },
    methods: {
        submit() {
            let url = this.product ? `/api/products/${this.product.id}/${this.action}` : `/api/products/${this.action}`

            this.formData.product_line_id = this.productLineId;

            this.$store.dispatch('post', {
                tag: 'save-product',
                url,
                formData: this.formData
            }).then((res, rej) => {
                this.$emit('close');
                this.$emit('save', {
                    action: this.action,
                    product: res.data
                });
            });
        }
    },
    computed: {
        errors() {
            return this.$store.getters.getErrors;
        },
        currencyCodes() {
            return this.$store.getters['msc/getCurrencyCodes'];
        }
    },
    watch: {
        product: {
            handler(newVal, oldVal) {
                if(newVal) {
                    this.formData.brand = newVal.brand;
                    this.formData.model = newVal.model;
                    this.formData.name = newVal.name;
                    this.formData.unit_price = newVal.unit_price;
                    this.formData.currency_code = newVal.currency_code;
                    this.action = 'update'
                } else {
                    this.formData.brand = null;
                    this.formData.model = null;
                    this.formData.name = null;
                    this.formData.unit_price = null;
                    this.formData.currency_code = 'PHP';
                    this.action = 'create'
                }
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
