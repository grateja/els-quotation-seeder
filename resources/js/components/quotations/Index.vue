<template>
    <v-container>
        <template v-if="$route.params.id">
            <router-view></router-view>
        </template>
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
            <quotation-form @close="openAddEditDialog = false" @save="updateItems" :quotation="currentQuotation" />
        </v-dialog>
    </v-container>
</template>
<script>
import QuotationForm from './QuotationForm.vue';
export default {
    components: {
        QuotationForm
    },
    data: () => {
        return {
            currentQuotation: null,
            openAddEditDialog: false,
            page: 1,
            keyword: '',
            loading: false,
            items: [],
            header: [
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
        }
    },
    methods: {
        onInput() {
            this.debouncedLoadData()
        },
        loadData() {
            this.loading = true;
            axios.get('/api/quotations', {
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
        openAddEdit(quotation) {
            this.currentQuotation = quotation;
            this.openAddEditDialog = true;
        },
        openDelete(quotation) {
            if(confirm("Delete this quotation")) {
                axios.delete(`/api/customers/${quotation.id}`).then((res, rej) => {
                    this.items = this.items.filter(item => item.id !== quotation.id);
                });
            }
        },
        openInNew(quotation) {
            this.$router.push({
                name: 'editQuotation',
                params: {
                    id: quotation.id
                }
            });
        },
        updateItems(data) {
            if(data.action == 'create') {
                this.openInNew(data.quotation);
            } else {
                let index = this.items.findIndex(item => item.id === data.quotation.id);

                if (index !== -1) {
                    this.items[index] = { ...this.items[index], ...data.quotation };
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
