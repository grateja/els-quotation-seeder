<template>
    <v-container>
        <v-card v-if="product">
            <v-card-actions>
                <v-chip><h3>{{product.brand}} - {{ product.model }}</h3></v-chip>
                <h3>{{ product.name }}</h3>
                <v-spacer></v-spacer>
                <v-chip>{{ product.price }}</v-chip>
            </v-card-actions>
        </v-card>
        <v-data-table-virtual
            v-if="product"
            :items="product.bullets"
            :loading="loading"
            :headers="header"
        >

            <template v-slot:item.description="{ item, index }">
                <v-icon
                    class="me-2"
                    size="small"
                    @click="moveDown(item, index)"
                    :disabled="isAtBottom(item.stack_order)"
                >
                mdi-arrow-down
                </v-icon>
                <v-icon
                    class="me-2"
                    size="small"
                    @click="moveUp(item, index)"
                    :disabled="isAtTop(item.stack_order)"
                >
                mdi-arrow-up
                </v-icon>
                <template v-if="item.bullet_type == 'item'">
                    <span class="font-weight-bold">{{ item.description }}</span>
                </template>
                <template v-else>
                    <span class="ml-8">{{ item.description }}</span>
                </template>
            </template>

            <template v-slot:item.actions="{ item, index }">
                <v-icon
                    class="me-2"
                    size="small"
                    @click="openAddEdit(item, null)"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    class="me-2"
                    size="small"
                    @click="openDelete(item)"
                >
                    mdi-trash-can
                </v-icon>
            </template>
        </v-data-table-virtual>
        <v-btn @click="openAddEdit(null, null)" color="primary" class="my-4">Add description</v-btn>
        <v-dialog v-model="openBullet" max-width="500" v-if="product">
            <ProductBulletForm :stack-order="lastStackOrder" :productId="product.id" :bullet="bullet" @close="openBullet = false" @save="updateItems" />
        </v-dialog>
    </v-container>
</template>

<script>
import axios from 'axios';
import ProductBulletForm from './bullets/ProductBulletForm.vue';

export default {
    components: {
        ProductBulletForm,
    },
    data: () => {
        return {
            loading: false,
            product: null,
            bullet: null,
            parentBullet: null,
            openBullet: false,
            header: [
                { title: 'Quantity', key: 'quantity_unit', sortable: false },
                { title: 'Description', key: 'description', sortable: false },
                { title: 'Actions', key: 'actions', sortable: false },
            ]
        }
    },
    methods: {
        getProduct(productId) {
            this.loading = true;
            axios.get(`/api/products/${productId}/details`).then((res, rej) => {
                this.product = res.data;
            }).finally(() => {
                this.loading = false
            })
        },
        openAddEdit(bullet, parentBullet) {
            this.bullet = bullet
            this.openBullet = true;
            this.parentBullet = parentBullet;
        },
        openDelete(bullet) {
            if(confirm("Delete this bullet?")) {
                axios.delete(`/api/product-bullets/${bullet.id}`).then(() => {
                    this.product.bullets = this.product.bullets.filter (item => item.id != bullet.id )
                })
            }
        },
        moveUp(bullet, index) {
            let bullets = this.product.bullets;
            axios.post(`/api/product-bullets/${bullet.id}/move-up`).then((res, rej) => {
                bullets[index] = res.data.moveDown;
                bullets[index - 1] = res.data.moveUp;
            });
        },
        moveDown(bullet, index) {
            let bullets = this.product.bullets;
            axios.post(`/api/product-bullets/${bullet.id}/move-down`).then((res, rej) => {
                bullets[index] = res.data.moveUp;
                bullets[index + 1] = res.data.moveDown;
            });
        },
        updateItems(data) {
            let bullets = this.product.bullets;

            if(data.action == 'create') {
                bullets.push(data.bullet);
            } else {
                let index = bullets.findIndex(item => item.id === data.bullet.id);

                if (index !== -1) {
                    bullets[index] = { ...bullets[index], ...data.bullet };
                }
            }
        },
        isAtTop(stackOrder) {
            return stackOrder === Math.min(...this.product.bullets.map(item => item.stack_order));
        },
        isAtBottom(stackOrder) {
            return stackOrder === Math.max(...this.product.bullets.map(item => item.stack_order));
        }
    },
    computed: {
        productId() {
            return this.$route.params.productId;
        },
        lastStackOrder() {
            const bullets = this.product && this.product.bullets;

            if(bullets.length > 0) {
                return bullets.map ( (b) => b.stack_order )[bullets.length - 1] + 1;
            }
            return 1;
        }
    },
    watch: {
        productId: {
            handler(value) {
                if(value) {
                    this.getProduct(value)
                }
            },
            immediate: true
        }
    }
}
</script>
