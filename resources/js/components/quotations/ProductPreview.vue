<template>
    <form @submit.prevent="submit">
        <v-card v-if="product">
            <v-card-actions>
                <v-chip><h3>{{product.brand}} - {{ product.model }}</h3></v-chip>
                <h3>{{ product.name }}</h3>
                <v-spacer></v-spacer>
                <v-chip>{{ product.price }}</v-chip>
            </v-card-actions>
            <v-divider></v-divider>
            <v-card-text>
                <ul class="ml-8 list-none">
                    <li v-for="(bullet, index) in product.bullets" :key="bullet.id">
                        <span :class="[`list-style-${bullet.bullet_type}`, {'crossed': deleted(bullet)}]">
                            {{ bullet.label }}
                        </span>
                        <v-btn @click="excludeToggle(bullet)" size="x-small" icon>
                            <v-icon size="small">{{ deleted(bullet) ? 'mdi-plus' : 'mdi-close' }}</v-icon>
                        </v-btn>
                    </li>
                </ul>
                <v-row>
                    <v-col cols="12" sm="6">
                        <v-text-field v-model="quantity" class="mt-8" variant="outlined" label="Quantity"></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6">
                        <v-text-field v-model="discount" class="mt-8" variant="outlined" label="Discount"></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-btn color="primary" type="submit">select</v-btn>
            </v-card-actions>
        </v-card>
    </form>
</template>
<script>
export default {
    props: {
        quotationId: {
            type: String,
            required: true
        },
        product: {
            type: Object,
            required: true
        },
        quotationProduct: {
            type: [Object, null],
            required: true
        }
    },
    data: () => {
        return {
            discount: null,
            quantity: 1,
            action: 'create',
            excludedBullets: []
        }
    },
    methods: {
        submit() {
            let productBulletIds = this.product.bullets.filter (
                item => !this.excludedBullets.includes(item)
            ).map(item => item.id)
            this.$store.dispatch('post', {
                tag: 'save-quotation-product',
                url: `/api/quotation-products/${this.quotationId}/${this.action}`,
                formData: {
                    product_id: this.product.id,
                    quantity: this.quantity,
                    discount: this.discount,
                    productBulletIds
                }
            });

            // this.$emit('ok', {
            //     productId: this.product.id,
            //     quantity: this.quantity,
            //     discount: this.discount,
            // })
        },
        excludeToggle(bullet) {
            if (this.excludedBullets.includes(bullet)) {
                this.excludedBullets = this.excludedBullets.filter(item => item.id != bullet.id)
            } else {
                this.excludedBullets.push(bullet)
            }
        },
        deleted(bullet) {
            return this.excludedBullets.includes(bullet)
        }
    },
    watch: {
        quotationProduct: {
            handler(newVal) {
                if(newVal) {
                    this.discount = newVal.discount
                    this.quantity = newVal.quantity
                    this.action = 'update'
                } else {
                    this.discount = 0
                    this.quantity = 1
                    this.action = 'create'
                }
            },
            immediate: true
        }
    }
}
</script>
