<template>
    <div>
        <pre>{{quotation}}</pre>
        <v-card elevation="20" v-if="quotation && quotation.quotation_products.length">
            <v-card-text>
                <tamplate v-for="(product, index) in quotation.quotation_products" :key="product.id">
                    <v-row>
                        <v-col cols="12" sm="1">
                            {{ index + 1 }}
                        </v-col>
                        <v-col cols="12" sm="7">
                            <h3>{{ product.name }}</h3>
                            <ul class="ml-8 list-none">
                                <li v-for="(bullet, index) in product.quotation_product_bullets" :key="bullet.id">
                                    <span :class="`list-style-${bullet.bullet_type}`">{{ bullet.description }}</span>
                                </li>
                            </ul>
                        </v-col>
                        <v-col cols="12" sm="1">
                            <h3>{{ product.quantity }}</h3>
                        </v-col>
                        <v-col cols="12" sm="3" align-right>
                            <h3 class="text-right">
                                {{ product.price }}
                            </h3>
                            <h3 v-if="product.discount" class="text-right text-success">
                                {{ product.discount_str }}
                            </h3>
                        </v-col>
                    </v-row>
                    <v-divider class="my-3"></v-divider>
                </tamplate>
            </v-card-text>
        </v-card>

        <v-btn block @click="openSearchbar = true" color="primary" v-if="!openSearchbar">add item</v-btn>
        <v-expand-transition>
            <v-card id="keme"
                min-height="95vh" v-if="openSearchbar">
                <v-text-field
                    v-model="keyword"
                    variant="outlined"
                    placeholder="Type keyword here"
                    append-inner-icon="mdi-magnify"
                    append-icon="mdi-close"
                    @input="onInput"
                    @click:append="openSearchbar = false"
                    @focus="prepareSearch" />
                <v-data-table-virtual
                    :headers="header"
                    :items="searchResult"
                    hide-default-footer
                    @click:row="select">
                    <template v-slot:item.name="{item}">
                        <div class="my-3">
                            <h3>{{ item.name }}</h3>
                            <ul class="ml-8 list-none">
                                <li v-for="(bullet, index) in item.bullets" :key="bullet.id">
                                    <span :class="`list-style-${bullet.bullet_type}`">{{ bullet.label }}</span>
                                </li>
                            </ul>
                        </div>
                    </template>
                </v-data-table-virtual>
            </v-card>
        </v-expand-transition>
        <v-dialog v-model="productPreviewDialogOpen" width="720">
            <ProductPreview v-if="quotation" :quotationId="quotation.id" :product="currentProduct" :quotationProduct="quotationProduct" @ok="confirmSelect" />
        </v-dialog>
    </div>
</template>
<script>
import ProductPreview from './ProductPreview.vue'
export default {
    components: { ProductPreview },
    data: () => {
        return {
            quotation: null,
            quotationProduct: null,
            keyword: '',
            openSearchbar: false,
            searchResult: [],
            productPreviewDialogOpen: false,
            currentProduct: null,
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
                }
            ]
        }
    },
    methods: {
        getQuotation(quotationId) {
            axios.get(`/api/quotations/${quotationId}/edit`).then((res) => {
                this.quotation = res.data;
            })
        },
        search() {
            this.$store.dispatch('get', {
                url: `/api/products`,
                formData: {
                    keyword: this.keyword,
                }
            }).then((res) => {
                this.searchResult = res.data.data;
            })
        },
        prepareSearch(event) {
            event.target.scrollIntoView({behavior: 'smooth'})
            this.debouncedLoadData = this.$debounce(this.search, 500);
        },
        onInput() {
            this.debouncedLoadData()
        },
        select(e, row) {
            this.currentProduct = row.item
            this.productPreviewDialogOpen = true
        },
        confirmSelect(data) {
            // this.$store.dispatch('post', {
            //     tag: 'save-quotation-product',
            //     url: `/api/quotation-products/${this.quotation.id}/products`,
            //     formData: data
            // });
        }
    },
    watch: {
        quotationId: {
            handler(newVal) {
                if(newVal) {
                    this.getQuotation(newVal)
                }
            },
            immediate: true
        }
    },
    computed: {
        quotationId() {
            return this.$route.params.id
        }
    }
}
</script>
