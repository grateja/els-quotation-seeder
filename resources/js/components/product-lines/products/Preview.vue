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
            <ProductBulletForm :stack-order="lastStackOrder()" :productId="product.id" :bullet="bullet" @close="openBullet = false" @save="updateItems" />
        </v-dialog>
    </v-container>
</template>

<script setup>
import ProductBulletForm from './bullets/ProductBulletForm.vue';
import { ref, computed, watch } from 'vue';
import { useRoute } from 'vue-router'

const router = useRoute()

const loading = ref(false)
const product = ref(null)
const bullet = ref(null)
const openBullet = ref(false)
const header = [
    { title: 'Quantity', key: 'quantity_unit', sortable: false },
    { title: 'Description', key: 'description', sortable: false },
    { title: 'Actions', key: 'actions', sortable: false },
]

const getProduct = async (productId) => {
    loading.value = true;
    axios.get(`/api/products/${productId}/details`).then((res, rej) => {
        product.value = res.data;
    }).finally(() => {
        loading.value = false
    })
}

const openAddEdit = (item) => {
    bullet.value = item
    openBullet.value = true
}

const openDelete = (item) => {
    if(confirm("Delete this bullet?")) {
        axios.delete(`/api/product-bullets/${item.id}`).then(() => {
            product.value.bullets = product.value.bullets.filter (b => b.id != item.id )
        })
    }
}

const moveUp = async (item, index) => {
    const bullets = product.value.bullets;
    axios.post(`/api/product-bullets/${item.id}/move-up`).then((res, rej) => {
        bullets[index] = res.data.moveDown;
        bullets[index - 1] = res.data.moveUp;
    });
}

const moveDown = async(item, index) => {
    const bullets = product.value.bullets;
    axios.post(`/api/product-bullets/${item.id}/move-down`).then((res, rej) => {
        bullets[index] = res.data.moveUp;
        bullets[index + 1] = res.data.moveDown;
    });
}

const updateItems = (data) => {
    const bullets = product.value.bullets;

    if(data.action == 'create') {
        bullets.push(data.bullet);
    } else {
        let index = bullets.findIndex(item => item.id === data.bullet.id);

        if (index !== -1) {
            bullets[index] = { ...bullets[index], ...data.bullet };
        }
    }
}

const isAtTop = (stackOrder) => {
    return stackOrder === Math.min(...product.value.bullets.map(item => item.stack_order));
}

const isAtBottom = (stackOrder) => {
    return stackOrder === Math.max(...product.value.bullets.map(item => item.stack_order));
}

const lastStackOrder = () => {
    const bullets = product.value && product.value.bullets;

    if(bullets.length > 0) {
        return bullets.map ( (b) => b.stack_order )[bullets.length - 1] + 1;
    }
    return 1;
}


const productId = computed(() => router.params.productId)

watch(productId, (newProductId) => {
    if(newProductId) {
        getProduct(newProductId)
    }
}, { immediate: true })
</script>
