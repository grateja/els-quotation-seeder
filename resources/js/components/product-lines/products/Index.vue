<template>
    <div>
        <router-view />
        <v-card v-if="productLine">
            <v-card-title>{{ productLine.name }}</v-card-title>
            <v-card-text>{{ productLine.description }}</v-card-text>
        </v-card>

        <v-text-field
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
            v-model="keyword"
            @input="onInput"
        ></v-text-field>

        <v-btn @click="openAddEdit(null)" color="primary" class="my-4">Create new</v-btn>

        <v-data-table-virtual
            :headers="header"
            :items="products"
            :loading="loading"
        >
            <template v-slot:item.name="{item}">
                <div class="my-8">
                    <h3>{{ item.name }}</h3>
                    <ul class="ml-8 list-none">
                        <li v-for="(bullet, index) in item.bullets" :key="bullet.id">
                            <span :class="`list-style-${bullet.bullet_type}`">{{ bullet.label }}</span>
                        </li>
                    </ul>
                    <v-btn size="x-small" :to="{name: 'viewProduct', params: {productId: item.id}}">add | edit description</v-btn>
                </div>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    class="me-2"
                    size="small"
                    @click="openAddEdit(item)"
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
        <v-dialog v-model="openProduct" max-width="500" v-if="productLine">
            <ProductForm :product="product" :productLineId="productLine.id" @close="openProduct = false" @save="updateItems" />
        </v-dialog>
    </div>
</template>
<script setup>
import { ref, computed, watch } from 'vue'
import { debounce } from 'lodash'
import { useRoute } from 'vue-router'

import ProductForm from './ProductForm.vue';

const router = useRoute()

const openProduct = ref(false)
const product = ref(null)
const keyword = ref('')
const loading = ref(false)
const products = ref([])
const productLine = ref(null)
const header = ref([
    {
        sortable: false,
        title: 'Model',
        key: 'model'
    },
    {
        sortable: false,
        title: 'Brand',
        key: 'brand'
    },
    {
        sortable: false,
        title: 'Name',
        key: 'name'
    },
    {
        sortable: false,
        title: 'Price',
        key: 'price'
    },
    {
        title: "Actions",
        key: 'actions'
    }
])

const loadProducts = async (productLineId) => {
    axios.get(`/api/products/${productLineId}`, {
        params: {
            keyword: keyword.value
        }
    }).then((res, rej) => {
        products.value = res.data.data;
    }).finally(() => {
        loading.value = false;
    })
}

const getProductLine = async (productLineId) => {
    loading.value = true;
    axios.get(`/api/product-lines/${productLineId}/full`).then((res, rej) => {
        productLine.value = res.data.productLine;
        loadProducts(productLineId)
    }).finally(() => {
        loading.value = false;
    });
}

const onInput = debounce(() => {
    loading.value = true;
    if(productLine.value) {
        loadProducts(productLine.value.id)
    }
}, 500)

const updateItems = (data) => {
    console.log('update items')
    if(data.action == 'create') {
        products.value.push(data.product);
        product.value = data.product;
    } else {
        let index = products.value.findIndex(item => item.id === data.product.id);

        if (index !== -1) {
            products.value[index] = { ...products.value[index], ...data.product };
        }
    }
}

const openAddEdit = (item) => {
    product.value = item;
    openProduct.value = true
}

const openDelete = (product) => {
    if(confirm("Delete this product")) {
        axios.delete(`/api/products/${product.id}`).then((res, rej) => {
            products.value = products.value.filter(item => item.id !== product.id);
        });
    }
}

const productLineId = computed(() => router.params.id)

watch(productLineId, (newId) => {
    if(newId) {
        getProductLine(newId)
    }
}, { immediate: true })
</script>
