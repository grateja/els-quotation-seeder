<template>
    <v-container>
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
        </v-data-table>
        <pre>{{items}}</pre>
        <v-dialog v-model="openAddEditDialog" max-width="500" persistent>
            <customer-form @close="openAddEditDialog = false" @save="updateItems" :customer="currentCustomer" />
        </v-dialog>
    </v-container>
</template>
<script>
import CustomerForm from './CustomerForm.vue';

import { ref } from 'vue'
import { debounce } from 'lodash'

let page = 1

const customer = ref(null)
const openAddEditDialog = ref(false)
const keyword = ref('')
const loading = ref(false)
const items = ref([])
const header = [
    {
        sortable: false,
        title: 'CRN',
        key: 'crn'
    },
    {
        sortable: false,
        title: 'Name',
        key: 'name'
    },
    {
        sortable: false,
        title: 'Address',
        key: 'address'
    },
    {
        sortable: false,
        title: 'Contact number',
        key: 'contact_number'
    },
    {
        sortable: false,
        title: 'Sales Rep.',
        key: 'sales_representative_name'
    },
    {
        title: "Actions",
        key: 'actions'
    }
]

const onInput = debounce(() => {
    loading.value = true
    axios.get('/api/customers', {
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
    customer.value = item
    openAddEditDialog.value = true
}

const openDelete = (item) => {
    if(confirm("Delete this customer")) {
        axios.delete(`/api/customers/${item.id}`).then((res, rej) => {
            items.value = items.value.filter(c => c.id !== item.id);
        });
    }
}

const updateItems = (data) => {
    if(data.action == 'create') {
        items.value.push(data.customer);
        customer.value = data.customer;
    } else {
        let index = items.value.findIndex(item => item.id === data.customer.id);

        if (index !== -1) {
            items.value[index] = { ...items.value[index], ...data.customer };
        }
    }
}

onInput()

export default {
    components: {
        CustomerForm
    },
    data: () => {
        return {
            currentCustomer: null,
            openAddEditDialog: false,
            page: 1,
            keyword: '',
            loading: false,
            items: [],
            header: [
                {
                    sortable: false,
                    title: 'CRN',
                    key: 'crn'
                },
                {
                    sortable: false,
                    title: 'Name',
                    key: 'name'
                },
                {
                    sortable: false,
                    title: 'Address',
                    key: 'address'
                },
                {
                    sortable: false,
                    title: 'Contact number',
                    key: 'contact_number'
                },
                {
                    sortable: false,
                    title: 'Sales Rep.',
                    key: 'sales_representative_name'
                },
                {
                    title: "Actions",
                    key: 'actions'
                }
            ]
        }
    },
    methods: {
        onInput() {
            this.debouncedLoadData()
        },
        loadData() {
            this.loading = true;
            axios.get('/api/customers', {
                params: {
                    keyword: this.keyword,
                    page: this.page
                }
            }).then((res, rej) => {
                this.items = res.data.data;
            }).finally(() => {
                this.loading = false;
            })
        },
        openAddEdit(customer) {
            this.currentCustomer = customer;
            this.openAddEditDialog = true;
        },
        openDelete(customer) {
            if(confirm("Delete this customer")) {
                axios.delete(`/api/customers/${customer.id}`).then((res, rej) => {
                    this.items = this.items.filter(item => item.id !== customer.id);
                });
            }
        },
        updateItems(data) {
            if(data.action == 'create') {
                this.items.push(data.customer);
                this.currentCustomer = data.customer;
            } else {
                let index = this.items.findIndex(item => item.id === data.customer.id);

                if (index !== -1) {
                    this.items[index] = { ...this.items[index], ...data.customer };
                }
            }
        }
    },
    created() {
        this.debouncedLoadData = this.$debounce(this.loadData, 500);
        this.loadData();
    }
}
</script>
