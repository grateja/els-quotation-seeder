<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} product bullet</v-card-title>
                <v-card-text>
                    <v-select label="Bullet type" :items="['simple', 'item']" v-model="formData.bullet_type" variant="outlined" :error-messages="errors.get('bullet_type')"></v-select>
                    <v-text-field class="my-4" v-model="formData.description" label="Description" variant="outlined" :error-messages="errors.get('description')"/>
                    <v-expand-transition>
                        <div v-if="formData.bullet_type == 'item'">
                            <v-row>
                                <v-col cols="4">
                                    <v-combobox
                                        class="my-4"
                                        v-model="formData.unit"
                                        label="Unit"
                                        variant="outlined"
                                        :items="units"
                                        :error-messages="errors.get('unit')"
                                    ></v-combobox>
                                </v-col>
                                <v-col cols="8">
                                    <v-text-field class="my-4" v-model="formData.quantity" type="number" label="Quantity" variant="outlined" :error-messages="errors.get('quantity')"/>
                                </v-col>
                            </v-row>
                        </div>
                    </v-expand-transition>
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
    props: {
        bullet: {
            type: [Object, null],
            required: true
        },
        productId: {
            type: String,
            required: true
        },
        stackOrder: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            action: 'create',
            formData: {
                description: null,
                quantity: null,
                unit: null,
                bullet_type: 'simple',
            }
        }
    },
    methods: {
        submit() {
            let url = this.bullet ? `/api/product-bullets/${this.bullet.id}/update` : `/api/product-bullets/${this.productId}/create`

            if(this.action == 'create') {
                this.formData.stack_order = this.stackOrder;
            }

            this.$store.dispatch('post', {
                tag: 'save-product-bullet',
                url,
                formData: this.formData
            }).then((res, rej) => {
                this.$emit('close');
                this.$emit('save', {
                    action: this.action,
                    bullet: res.data
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
        },
        units() {
            return this.$store.getters['msc/getUnits'];
        },
        bulletTypes() {
            return this.$store.getters['msc/getBulletTypes'];
        }
    },
    watch: {
        bullet: {
            handler(newVal, oldVal) {
                if(newVal) {
                    this.formData.description = newVal.description;
                    this.formData.quantity = newVal.quantity;
                    this.formData.unit = newVal.unit;
                    this.formData.bullet_type = newVal.bullet_type;
                    this.formData.stack_order = newVal.stack_order;
                    this.action = 'update'
                } else {
                    this.formData.description = null;
                    this.formData.unit = null;
                    this.formData.unit_price = null;
                    this.formData.bullet_type = 'simple';
                    this.action = 'create'
                }
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
