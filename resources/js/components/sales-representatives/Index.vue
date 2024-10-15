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
<script setup>
import { ref } from 'vue'
import { debounce } from 'lodash'

import SalesRepresentativeForm from './SalesRepresentativeForm.vue';

let page = 1

const salesRep = ref(null)
const openSRCreator = ref(false)
const keyword = ref('')
const loading = ref(false)
const items = ref([])
const header = [
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
];

const loadData = async () => {
    loading.value = true;
    axios.get('/api/sales-representatives', {
        params: {
            keyword: keyword.value,
            page
        }
    }).then((res, rej) => {
        items.value = res.data.data;
    }).finally(() => {
        loading.value = false;
    })
}
loadData()

const onInput = debounce(() => {
    loadData()
}, 500)

const openAddEdit = (item) => {
    salesRep.value = item
    openSRCreator.value = true
}

const openDelete = (item) => {
    if(confirm("Delete this sales representative")) {
        axios.delete(`/api/sales-representatives/${item.id}`).then((res, rej) => {
            items.value = items.value.filter(sr => sr.id !== item.id);
        });
    }
}

const updateItems = (data) => {
    if(data.action == 'create') {
        items.value.push(data.salesRepresentative);
        salesRep.value = data.salesRepresentative;
    } else {
        let index = items.value.findIndex(item => item.id === data.salesRepresentative.id);

        if (index !== -1) {
            items.value[index] = { ...items.value[index], ...data.salesRepresentative };
        }
    }
}
</script>
