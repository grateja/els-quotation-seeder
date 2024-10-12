<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} quotation</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <p>Customer</p>
                    <p class="font-weight-bold text-red" v-if="errors.has('customer_id')">{{errors.get('customer_id')}}</p>
                    <v-btn @click="customerSelectorOpen = true" :color="customer != null ? 'primary': ''" :loading="customerLoading">{{ customerName }}
                        <v-icon right>{{ customer ? 'mdi-pencil' : 'mdi-magnify' }}</v-icon>
                    </v-btn>
                    <v-btn icon size="small" @click="customer = null" v-if="customer">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>

                    <v-divider class="my-4"></v-divider>

                    <p>Sales representative</p>
                    <p class="font-weight-bold text-red" v-if="errors.has('sales_representative_id')">{{errors.get('sales_representative_id')}}</p>
                    <v-btn @click="salesRepSelectorOpen = true" :color="salesRep != null ? 'primary': ''" :loading="salesRepLoading">{{ salesRepName }}
                        <v-icon right>{{ salesRep ? 'mdi-pencil' : 'mdi-magnify' }}</v-icon>
                    </v-btn>
                    <v-btn icon size="small" @click="salesRep = null" v-if="salesRep">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>

                    <v-divider class="my-4"></v-divider>

                    <p>RBP</p>
                    <p class="font-weight-bold text-red" v-if="errors.has('subdealer_id')">{{errors.get('subdealer_id')}}</p>
                    <v-btn @click="subdealerSelectorOpen = true" :color="subdealer != null ? 'primary': ''" :loading="subdealerLoading">{{ rbpName }}
                        <v-icon right>{{ subdealer ? 'mdi-pencil' : 'mdi-magnify' }}</v-icon>
                    </v-btn>
                    <v-btn icon size="small" @click="subdealer = null" v-if="subdealer">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text="Close" @click="$emit('close')"/>
                    <v-btn type="submit" color="primary" :loading="loadingKeys.hasAny('save-quotation')">Save</v-btn>
                </v-card-actions>
            </v-card>
        </form>

        <v-dialog v-model="customerSelectorOpen" max-width="600" persistent>
            <CustomerSelector @close="customerSelectorOpen = false" @select="openCustomerCreator" @openAddEdit="customerCreatorOpen = true" :model="customer" />
        </v-dialog>
        <v-dialog v-model="customerCreatorOpen" max-width="600" persistent>
            <CustomerForm @close="customerCreatorOpen = false" :customer="customer" @save="(data) => selectCustomer(data.customer)" />
        </v-dialog>

        <v-dialog v-model="salesRepSelectorOpen" max-width="600" persistent>
            <SalesRepSelector @close="salesRepSelectorOpen = false" @select="openSRCreatorDialog" @openAddEdit="salesRepCreatorOpen = true" :model="salesRep" />
        </v-dialog>
        <v-dialog v-model="salesRepCreatorOpen" max-width="600" persistent>
            <SalesRepresentativeForm @close="salesRepCreatorOpen = false" :salesRepresentative="salesRep" @save="(data) => selectSalesRep(data.salesRepresentative)" />
        </v-dialog>

        <v-dialog v-model="subdealerSelectorOpen" max-width="600" persistent>
            <SubdealerSelector @close="subdealerSelectorOpen = false" @select="openSubdealerCreatorDialog" @openAddEdit="subdealerCreatorOpen = true" :model="subdealer" />
        </v-dialog>
        <v-dialog v-model="subdealerCreatorOpen" max-width="600" persistent>
            <SubdealerForm @close="subdealerCreatorOpen = false" :subdealer="subdealer" @save="(data) => selectSubdealer(data.subdealer)" />
        </v-dialog>
    </div>
</template>

<script>
import CustomerSelector from '../customers/CustomerSelector.vue';
import CustomerForm from '../customers/CustomerForm.vue';
import SalesRepSelector from '../sales-representatives/SalesRepSelector.vue';
import SalesRepresentativeForm from '../sales-representatives/SalesRepresentativeForm.vue';
import SubdealerSelector from '../subdealer/SubdealerSelector.vue';
import SubdealerForm from '../subdealer/SubdealerForm.vue';

export default {
    components: {
        CustomerSelector,
        CustomerForm,
        SalesRepSelector,
        SalesRepresentativeForm,
        SubdealerForm,
        SubdealerSelector
    },
    props: ['quotation'],
    data() {
        return {
            subdealer: null,
            subdealerLoading: false,
            subdealerSelectorOpen: false,
            subdealerCreatorOpen: false,
            salesRep: null,
            salesRepLoading: false,
            salesRepSelectorOpen: false,
            salesRepCreatorOpen: false,
            customer: null,
            customerLoading: null,
            customerSelectorOpen: false,
            customerCreatorOpen: false,
            action: 'create',
            formData: {
                subdealer_id: null,
                sales_representative_id: null,
                customer_id: null,
            }
        }
    },
    methods: {
        submit() {
            let url = this.quotation ? `/api/quotations/${this.quotation.id}/${this.action}` : `/api/quotations/${this.action}`

            this.formData.sales_representative_id = this.salesRep ? this.salesRep.id : null
            this.formData.customer_id = this.customer ? this.customer.id : null
            this.formData.subdealer_id = this.subdealer ? this.subdealer.id : null

            this.$store.dispatch('post', {
                tag: 'save-quotation',
                url,
                formData: this.formData
            }).then((res, rej) => {
                this.$emit('close');
                this.$emit('save', {
                    action: this.action,
                    quotation: res.data
                });
            });
        },
        selectSalesRep(salesRep) {
            this.salesRep = salesRep;
        },
        openSRCreatorDialog(salesRep) {
            this.salesRep = salesRep;
            this.salesRepCreatorOpen = true;
        },
        selectSubdealer(subdealer) {
            this.subdealer = subdealer;
        },
        openSubdealerCreatorDialog(subdealer) {
            this.subdealer = subdealer;
            this.subdealerCreatorOpen = true;
        },
        openCustomerCreator(customer) {
            this.customer = customer;
            this.customerCreatorOpen = true
        },
        selectCustomer(customer) {
            this.customer = customer
        },
        loadSalesRep(salesRepId) {
            this.salesRepLoading = true;
            axios.get(`/api/sales-representatives/${salesRepId}`).then((res, rej) => {
                this.salesRep = res.data
            }).catch(err => {
                this.salesRep = null
            }).finally(() => {
                this.salesRepLoading = false;
            });
        },
        loadSubdealer(subdealerId) {
            this.subdealerLoading = true;
            axios.get(`/api/subdealers/${subdealerId}`).then((res, rej) => {
                this.subdealer = res.data
            }).catch(err => {
                this.subdealer = null
            }).finally(() => {
                this.subdealerLoading = false;
            });
        },
        loadCustomer(customerId) {
            this.customerLoading = true;
            axios.get(`/api/customers/${customerId}`).then((res, rej) => {
                this.customer = res.data
            }).catch(err => {
                this.customer = null
            }).finally(() => {
                this.customerLoading = false;
            });
        }
    },
    computed: {
        errors() {
            return this.$store.getters.getErrors;
        },
        salesRepName() {
            return this.salesRep ? this.salesRep.alias : 'Select sales rep.';
        },
        rbpName() {
            return this.subdealer ? this.subdealer.alias : 'Select RBP';
        },
        customerName() {
            return this.customer ? this.customer.name : 'Select customer'
        },
        loadingKeys() {
            return this.$store.getters.loadingKeys;
        }
    },
    watch: {
        quotation: {
            handler(newVal, oldVal) {
                if(newVal) {
                    this.formData.customer_id = newVal.customer_id;
                    this.formData.subdealer_id = newVal.subdealer_id;
                    this.formData.sales_representative_id = newVal.sales_representative_id;
                    this.action = 'update'

                    if(newVal.salesRep != null) {
                        this.salesRep = newVal.salesRep
                    } else if(newVal.sales_representative_id != null) {
                        this.loadSalesRep(newVal.sales_representative_id);
                    }
                    if(newVal.subdealer != null) {
                        this.subdealer = newVal.subdealer
                    } else if(newVal.subdealer_id != null) {
                        this.loadSubdealer(newVal.subdealer_id);
                    }

                    if(newVal.customer != null) {
                        this.customer = newVal.customer
                    } else if(newVal.customer_id != null) {
                        this.loadCustomer(newVal.customer_id);
                    }
                } else {
                    this.formData.customer_id = null;
                    this.formData.subdealer_id = null;
                    this.formData.sales_representative_id = null;
                    this.customer = null;
                    this.subdealer = null;
                    this.salesRep = null;
                    this.action = 'create'
                }
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
