<template>
    <v-container>
        <router-view v-if="$route.params.quotationId"></router-view>
        <template v-else>
            <v-text-field
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
                v-model="keyword"
                @input="onInput"
            ></v-text-field>

            <v-btn @click="openAddEdit(null)" color="primary" class="my-4">Create new</v-btn>

            <v-data-table
                :headers="header"
                hide-default-footer
                :items="items"
                :loading="loading"
            >
                <template v-slot:item.status="{ item }">
                    <v-chip size="small">{{ item.status }}</v-chip>
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
                    <v-icon
                        class="me-2"
                        size="small"
                        @click="openInNew(item)"
                    >
                        mdi-open-in-new
                    </v-icon>
                  </template>
            </v-data-table>
        </template>
        <v-dialog v-model="openAddEditDialog" max-width="500" persistent>
            <quotation-form @close="openAddEditDialog = false" @save="updateItems" :quotation="quotation" />
        </v-dialog>
    </v-container>
</template>
<script setup>
import QuotationForm from './QuotationForm.vue';

import { ref } from 'vue'
import { debounce } from 'lodash'
import { useRouter } from 'vue-router'

const route = useRouter()

let page = 1
const quotation = ref(null)
const keyword = ref('')
const loading = ref(false)
const items = ref([])
const openAddEditDialog = ref(false)
const header = [
    {
        sortable: false,
        title: 'Quotation#',
        key: 'quotation_number'
    },
    {
        sortable: false,
        title: 'Customer name',
        key: 'customer_name'
    },
    {
        sortable: false,
        title: 'RBP/SalesRep.',
        key: 'fao'
    },
    {
        sortable: false,
        title: 'Status',
        key: 'status'
    },
    {
        sortable: false,
        title: "Actions",
        key: 'actions'
    }
]

const onInput = debounce(async () => {
    loading.value = true;
    axios.get('/api/quotations', {
        params: {
            keyword: keyword.value,
            page
        }
    }).then((res, rej) => {
        items.value = res.data.data;
    }).finally(() => {
        loading.value = false;
    })
}, 500)

const openAddEdit = (item) => {
    quotation.value = item;
    openAddEditDialog.value = true
}

const openDelete = (item) => {
    if(confirm("Delete this quotation")) {
        axios.delete(`/api/quotations/${item.id}`).then((res, rej) => {
            items.value = items.value.filter(q => q.id !== item.id);
        });
    }
}

const openInNew = (item) => {
    route.push({
        name: 'editQuotation',
        params: {
            quotationId: item.id
        }
    });
}

const updateItems = (data) => {
    if(data.action == 'create') {
        items.value.unshift(data.quotation);
        openInNew(data.quotation);
    } else {
        let index = items.value.findIndex(item => item.id === data.quotation.id);

        if (index !== -1) {
            items.value[index] = { ...items.value[index], ...data.quotation };
        }
    }
}

onInput()
</script>
