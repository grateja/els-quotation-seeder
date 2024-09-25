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
        <v-dialog v-model="openSRCreator" max-width="600" persistent>
            <SalesRepresentativeForm @close="openSRCreator = false" :salesRepresentative="salesRep" @save="updateItems" />
        </v-dialog>
    </v-container>
</template>
<script>
import SalesRepresentativeForm from './SalesRepresentativeForm.vue';
export default {
    components: {
        SalesRepresentativeForm
    },
    data: () => {
        return {
            salesRep: null,
            openSRCreator: false,
            page: 1,
            keyword: '',
            loading: false,
            items: [],
            header: [
                {
                    sortable: false,
                    title: 'Initials',
                    key: 'initials'
                },
                {
                    sortable: false,
                    title: 'Name',
                    key: 'name'
                },
                {
                    sortable: false,
                    title: 'Department',
                    key: 'department'
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
            axios.get('/api/sales-representatives', {
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
        openAddEdit(salesRep) {
            this.salesRep = salesRep;
            this.openSRCreator = true;
        },
        openDelete(salesRep) {
            if(confirm("Delete this sales representative")) {
                axios.delete(`/api/sales-representatives/${salesRep.id}`).then((res, rej) => {
                    this.items = this.items.filter(item => item.id !== salesRep.id);
                });
            }
        },
        updateItems(data) {
            if(data.action == 'create') {
                this.items.push(data.salesRepresentative);
                this.salesRep = data.salesRepresentative;
            } else {
                let index = this.items.findIndex(item => item.id === data.salesRepresentative.id);

                if (index !== -1) {
                    this.items[index] = { ...this.items[index], ...data.salesRepresentative };
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
