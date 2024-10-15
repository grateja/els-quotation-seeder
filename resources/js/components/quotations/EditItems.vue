<template>
    <div>
        <pre>{{quotation}}</pre>
        <v-card class="mx-auto pa-4 cambria" elevation="10" v-if="quotation">
            <v-row justify="end" row-gutters>
                <v-col cols="2" class="text-center py-2">
                    {{ $moment(quotation.created_at).format('DD-MMM-YYYY') }}
                </v-col>
                <v-col cols="2" class="text-center py-2">
                    {{ quotation.customer.crn }}
                </v-col>
                <v-col cols="3" class="text-center py-2">
                    {{ quotation.quotation_number }}
                </v-col>
            </v-row>
            <v-divider class="border-opacity-75"></v-divider>
            <v-divider></v-divider>
            <v-divider class="border-opacity-75"></v-divider>
            <v-row justify="end" class="label">
                <v-col cols="2" class="text-center py-2">
                    Date
                </v-col>
                <v-col cols="2" class="text-center py-2">
                    Customer#
                </v-col>
                <v-col cols="3" class="text-center py-2">
                    Quotation#
                </v-col>
            </v-row>
            <dl>
                <dt class="label">Client:</dt>
                <dd> <span v-if="quotation.subdealer">FAO:{{ quotation.subdealer.abbr }} - </span> {{ quotation.customer.name }}</dd>
                <dt class="label">Address:</dt>
                <dd>{{ quotation.customer.address }}</dd>
            </dl>

            <h3 class="mt-8">A. Equipment Cost:</h3>
            <v-row>
                <v-col cols="12" sm="1">
                    Item
                </v-col>
                <v-col cols="12" sm="6">
                    Model/Brand/Description
                </v-col>
                <v-col cols="12" sm="1">
                    Qty.
                </v-col>
                <v-col cols="12" sm="2" align-right>
                    Unit Price
                </v-col>
                <v-col cols="12" sm="2" align-right>
                    Total Price
                </v-col>
            </v-row>
            <v-divider></v-divider>
            <div v-if="quotation.quotation_products.length == 0" class="text-center font-italic font-weight-bold text-gray">
                (No items)
            </div>
            <template v-for="(product, index) in quotation.quotation_products" :key="product.id">
                <v-row>
                    <v-col cols="12" sm="1">
                        {{ index + 1 }}
                    </v-col>
                    <v-col cols="12" sm="6">
                        <h4>{{ product.name }}</h4>
                        <ul class="ml-8 list-none">
                            <li v-for="(bullet, index) in product.quotation_product_bullets" :key="bullet.id">
                                <span :class="`list-style-${bullet.bullet_type}`">{{ bullet.description }}</span>
                            </li>
                        </ul>
                    </v-col>
                    <v-col cols="12" sm="1">
                        <h5>{{ product.quantity }}</h5>
                    </v-col>
                    <v-col cols="12" sm="2" align-right>
                        <h5 class="text-right">
                            {{ product.unit_price }}
                        </h5>
                    </v-col>
                    <v-col cols="12" sm="2" align-right>
                        <h5 class="text-right">
                            {{ product.price }}
                        </h5>
                        <h5 v-if="product.discount" class="text-right text-success">
                            {{ product.discount_str }}
                        </h5>
                    </v-col>
                </v-row>
                <v-divider class="my-3"></v-divider>
            </template>
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
                    :loading="searching"
                    @input="search"
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
        <v-dialog v-model="openProductPreviewDialog" width="720">
            <ProductPreview v-if="quotation" :quotationId="quotation.id" :product="currentProduct" :quotationProduct="quotationProduct" @ok="confirmSelect" />
        </v-dialog>
    </div>
</template>
<script setup>
import ProductPreview from './ProductPreview.vue'

import { ref, computed, watch } from 'vue'
import { debounce } from 'lodash'
import { useRoute } from 'vue-router'

const route = useRoute()

const quotation = ref(null)
const quotationProduct = ref(null)
const keyword = ref('')

const openSearchbar = ref(false)
const searchResult = ref([])
const currentProduct = ref(null)
const openProductPreviewDialog = ref(false)

const searching = ref(false)

const header = [
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

const getQuotation = async (quotationId) => {
    axios.get(`/api/quotations/${quotationId}/edit`).then((res) => {
        quotation.value = res.data;
    })
}

const search = debounce(async () => {
    searching.value = true
    axios.get('/api/products', {
        params: {
            keyword: keyword.value,
        }
    }).then(res => {
        searchResult.value = res.data.data
    }).finally(() => {
        searching.value = false
    })
}, 500)

const prepareSearch = (event) => {
    event.target.scrollIntoView({behavior: 'smooth'})
    search()
}

const select = (e, row) => {
    currentProduct.value = row.item
    openProductPreviewDialog.value = true
}

const confirmSelect = (data) => {

}

watch(() => route.params.quotationId, (newVal) => {
    if(newVal) {
        getQuotation(newVal)
    }
}, {immediate: true})

</script>

<style>
.label {
    color: rgb(56, 119, 219);
    font-weight: 900;
    font-size: 11pt;
}

</style>
