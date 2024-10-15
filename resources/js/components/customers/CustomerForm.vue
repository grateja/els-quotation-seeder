<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} customer {{ openSRCreator }}</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.name" label="Name" variant="outlined" :error-messages="errors.get('save-customer.name')"/>
                    <v-text-field class="my-4" v-model="formData.address" label="Address" variant="outlined" :error-messages="errors.get('save-customer.address')"/>
                    <v-text-field class="my-4" v-model="formData.contact_number" label="Contact number" variant="outlined" :error-messages="errors.get('save-customer.contact_number')"/>
                    <p>Sales representative</p>
                    <v-btn @click="openSRSelector = true" :color="salesRep != null ? 'primary': ''" :loading="loadingSalesRep">{{ salesRepName }}
                        <v-icon right>{{ salesRep ? 'mdi-pencil' : 'mdi-magnify' }}</v-icon>
                    </v-btn>
                    <v-divider class="my-4"></v-divider>
                    <p>RBP</p>
                    <v-btn @click="openSubdealerSelector = true" :color="subdealer != null ? 'primary': ''" :loading="loadingSubdealer">{{ subdealerName }}
                        <v-icon right>{{ subdealer ? 'mdi-pencil' : 'mdi-magnify' }}</v-icon>
                    </v-btn>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text="Close" @click="$emit('close')"/>
                    <v-btn type="submit" color="primary" :disabled="loadingSalesRep || loadingSubdealer" :loading="saving">Save</v-btn>
                </v-card-actions>
            </v-card>
        </form>

        <v-dialog v-model="openSRSelector" max-width="600" persistent>
            <SalesRepSelector @close="openSRSelector = false" @select="browseSalesRep" :model="salesRep" />
        </v-dialog>
        <v-dialog v-model="openSRCreator" max-width="600" persistent>
            <SalesRepresentativeForm @close="openSRCreator = false" :salesRepresentative="salesRep" @save="(data) => selectSalesRep(data.salesRepresentative)" />
        </v-dialog>

        <v-dialog v-model="openSubdealerSelector" max-width="600" persistent>
            <SubdealerSelector @close="openSubdealerSelector = false" @select="browseSubdealer" :model="subdealer" />
        </v-dialog>
        <v-dialog v-model="openSubdealerCreator" max-width="600" persistent>
            <SubdealerForm @close="openSubdealerCreator = false" :subdealer="subdealer" @save="(data) => selectSubdealer(data.subdealer)" />
        </v-dialog>
    </div>
</template>

<script setup>
import SalesRepSelector from '../sales-representatives/SalesRepSelector.vue';
import SalesRepresentativeForm from '../sales-representatives/SalesRepresentativeForm.vue';
import SubdealerSelector from '../subdealer/SubdealerSelector.vue';
import SubdealerForm from '../subdealer/SubdealerForm.vue';

import { ref, watch, computed, defineEmits, defineProps } from 'vue'
import { useRequestStore } from '@/store/requestStore.js'

const props = defineProps(['customer'])
const emit = defineEmits(['close', 'save'])
const errors = useRequestStore()
errors.clear()

const subdealer = ref(null)
const salesRep = ref(null)

let action = 'create'

const openSRSelector = ref(false)
const openSRCreator = ref(false)

const openSubdealerSelector = ref(false)
const openSubdealerCreator = ref(false)

const saving = ref(false)

const loadingSubdealer = ref(false)
const loadingSalesRep = ref(false)

const formData = ref({
    name: null,
    address: null,
    contact_number: null,
    sales_representative_id: null
})

const submit = async() => {
    let url = props.customer ? `/api/customers/${props.customer.id}/${action}` : `/api/customers/${action}`

    errors.clear()
    saving.value = true

    if(salesRep.value) {
        formData.value.sales_representative_id = salesRep.value.id
    }

    if(subdealer.value) {
        formData.value.subdealer_id = subdealer.value.id
    }

    axios.post(url + '?tag=save-customer', formData.value).then(res => {
        emit('save', {
            action,
            customer: res.data
        })
        close()
    }).finally(() => {
        saving.value = false
    })
}

const close = () => {
    emit('close')
}

const selectSalesRep = (item) => {
    salesRep.value = item
}

const selectSubdealer = (item) => {
    subdealer.value = item
}

const browseSalesRep = (item) => {
    selectSalesRep(item)
    openSRCreator.value = true
}

const browseSubdealer = (item) => {
    selectSubdealer(item)
    openSubdealerCreator.value = true
}

const getSubdealer = (subdealerId) => {
    loadingSubdealer.value = true
    axios.get(`/api/subdealers/${subdealerId}`).then(res => {
        subdealer.value = res.data
    }).catch(err => subdealer.value = null).finally(() => {
        loadingSubdealer.value = false
    })
}

const getSalesRep = (salesRepId) => {
    loadingSalesRep.value = true
    axios.get(`/api/sales-representatives/${salesRepId}`).then(res => {
        salesRep.value = res.data
    }).catch(err => salesRep.value = res.data).finally(() => {
        loadingSalesRep.value = false
    })
}

watch(() => props.customer, (newVal) => {
    if(newVal) {
        formData.value.name = newVal.name;
        formData.value.address = newVal.address;
        formData.value.contact_number = newVal.contact_number;
        salesRep.value = newVal.sales_representative
        action = 'update'

        if(newVal.salesRep != null) {
            salesRep.value = newVal.salesRep
        } else if(newVal.sales_representative_id != null) {
            getSalesRep(newVal.sales_representative_id);
        }

        if(newVal.subdealer != null) {
            subdealer.value = newVal.subdealer
        } else if(newVal.subdealer_id != null) {
            getSubdealer(newVal.subdealer_id);
        }
    } else {
        formData.value.name = null;
        formData.value.address = null;
        formData.value.contact_number = null;
        salesRep.value = null
        subdealer.value = null
        action = 'create'
    }
}, {immediate: true})

const salesRepName = computed(() => {
    return salesRep.value ? salesRep.value.alias : 'Select sales rep.'
})

const subdealerName = computed(() => {
    return subdealer.value ? subdealer.value.alias : 'Select subdealer.'
})
</script>
