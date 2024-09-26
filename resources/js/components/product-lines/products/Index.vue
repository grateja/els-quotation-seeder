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
<script>
import ProductForm from './ProductForm.vue';

export default {
    components: { ProductForm },
    data: () => {
        return {
            openProduct: false,
            product: null,
            keyword: '',
            loading: false,
            productLine: null,
            products: [],
            header: [
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
            ]
        }
    },
    methods: {
        getProductLine(productLineId) {
            this.loading = true;
            axios.get(`/api/product-lines/${productLineId}/full`).then((res, rej) => {
                this.productLine = res.data.productLine;
            }).finally(() => {
                this.loading = false;
            });
        },
        onInput() {
            this.debouncedLoadData();
        },
        loadProducts() {
            this.loading = true;
            if(this.productLine) {
                axios.get(`/api/products/${this.productLine.id}`, {
                    params: {
                        keyword: this.keyword
                    }
                }).then((res, rej) => {
                    this.products = res.data.data;
                }).finally(() => {
                    this.loading = false;
                })
            }
        },
        updateItems(data) {
            if(data.action == 'create') {
                this.products.push(data.product);
                this.product = data.product;
            } else {
                let index = this.products.findIndex(item => item.id === data.product.id);

                if (index !== -1) {
                    this.products[index] = { ...this.products[index], ...data.product };
                }
            }
        },
        openAddEdit(product) {
            this.product = product;
            this.openProduct = true
        },
        openDelete(product) {
            if(confirm("Delete this product")) {
                axios.delete(`/api/products/${product.id}`).then((res, rej) => {
                    this.products = this.products.filter(item => item.id !== product.id);
                });
            }
        }
    },
    computed: {
        productLineId() {
            return this.$route.params.id
        }
    },
    created() {
        this.debouncedLoadData = this.$debounce(this.loadProducts, 500);
        this.loadProducts();
    },
    watch: {
        productLineId: {
            handler(value) {
                if(value) {
                    this.getProductLine(value)
                }
            },
            immediate: true
        },
        productLine: {
            handler(value) {
                if(value != null) {
                    this.$route.meta.displayName = value.name;
                    this.loadProducts();
                }
            },
            immediate: true
        }
    }
}
</script>
