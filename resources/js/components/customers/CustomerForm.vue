<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} customer</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.name" label="Name" variant="outlined" :error-messages="errors.get('name')"/>
                    <v-text-field class="my-4" v-model="formData.address" label="Address" variant="outlined" :error-messages="errors.get('address')"/>
                    <v-text-field class="my-4" v-model="formData.contact_number" label="Contact number" variant="outlined" :error-messages="errors.get('contact_number')"/>
                    <p>Sales representative</p>
                    <v-btn @click="openSRSelector = true" :color="salesRep != null ? 'primary': ''" :loading="salesRepLoading">{{ salesRepName }}
                        <v-icon right>{{ salesRep ? 'mdi-pencil' : 'mdi-magnify' }}</v-icon>
                    </v-btn>
                    <v-divider class="my-4"></v-divider>
                    <p>RBP</p>
                    <v-btn @click="openSubdealerSelector = true" :color="subdealer != null ? 'primary': ''" :loading="rbpLoading">{{ rbpName }}
                        <v-icon right>{{ subdealer ? 'mdi-pencil' : 'mdi-magnify' }}</v-icon>
                    </v-btn>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text="Close" @click="$emit('close')"/>
                    <v-btn type="submit" color="primary">Save</v-btn>
                </v-card-actions>
            </v-card>
        </form>

        <v-dialog v-model="openSRSelector" max-width="600" persistent>
            <SalesRepSelector @close="openSRSelector = false" @select="openSRCreatorDialog" @openAddEdit="openSRCreator = true" :model="salesRep" />
        </v-dialog>
        <v-dialog v-model="openSRCreator" max-width="600" persistent>
            <SalesRepresentativeForm @close="openSRCreator = false" :salesRepresentative="salesRep" @save="(data) => selectSalesRep(data.salesRepresentative)" />
        </v-dialog>

        <v-dialog v-model="openSubdealerSelector" max-width="600" persistent>
            <SubdealerSelector @close="openSubdealerSelector = false" @select="openSubdealerCreatorDialog" @openAddEdit="openSubdealerCreator = true" :model="subdealer" />
        </v-dialog>
        <v-dialog v-model="openSubdealerCreator" max-width="600" persistent>
            <SubdealerForm @close="openSubdealerCreator = false" :subdealer="subdealer" @save="(data) => selectSubdealer(data.subdealer)" />
        </v-dialog>
    </div>
</template>

<script>
import SalesRepSelector from '../sales-representatives/SalesRepSelector.vue';
import SalesRepresentativeForm from '../sales-representatives/SalesRepresentativeForm.vue';
import SubdealerSelector from '../subdealer/SubdealerSelector.vue';
import SubdealerForm from '../subdealer/SubdealerForm.vue';

export default {
    components: {
        SalesRepSelector,
        SalesRepresentativeForm,
        SubdealerForm,
        SubdealerSelector
    },
    props: ['customer'],
    data() {
        return {
            subdealer: null,
            salesRep: null,
            salesRepLoading: false,
            rbpLoading: false,
            openSRSelector: false,
            openSRCreator: false,
            openSubdealerSelector: false,
            openSubdealerCreator: false,
            action: 'create',
            formData: {
                name: null,
                address: null,
                contact_number: null,
                sales_representative_id: null
            }
        }
    },
    methods: {
        submit() {
            let url = this.customer ? `/api/customers/${this.customer.id}/${this.action}` : `/api/customers/${this.action}`

            if(this.salesRep) {
                this.formData.sales_representative_id = this.salesRep.id
            }

            if(this.subdealer) {
                this.formData.subdealer_id = this.subdealer.id
            }

            this.$store.dispatch('post', {
                tag: 'save-customer',
                url,
                formData: this.formData
            }).then((res, rej) => {
                this.$emit('close');
                this.$emit('save', {
                    action: this.action,
                    customer: res.data
                });
            });
        },
        selectSalesRep(salesRep) {
            this.salesRep = salesRep;
        },
        openSRCreatorDialog(salesRep) {
            this.salesRep = salesRep;
            this.openSRCreator = true;
        },
        selectSubdealer(subdealer) {
            this.subdealer = subdealer;
        },
        openSubdealerCreatorDialog(subdealer) {
            this.subdealer = subdealer;
            this.openSubdealerCreator = true;
        },
        loadSalesRep(salesRepId) {
            this.salesRepLoading = true;
            axios.get(`/api/sales-representatives/${salesRepId}`).then((res, rej) => {
                this.salesRep = res.data
            }).finally(() => {
                this.salesRepLoading = false;
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
        loadingSalesRep() {
            return this.$store.getters.loadingKeys;
        }
    },
    watch: {
        customer: {
            handler(newVal, oldVal) {
                if(newVal) {
                    this.formData.name = newVal.name;
                    this.formData.address = newVal.address;
                    this.formData.contact_number = newVal.contact_number;
                    this.salesRep = newVal.sales_representative
                    this.action = 'update'
                    if(newVal.salesRep == null && newVal.sales_representative_id != null) {
                        this.loadSalesRep(newVal.sales_representative_id);
                    }
                } else {
                    this.formData.name = null;
                    this.formData.address = null;
                    this.formData.contact_number = null;
                    this.salesRep = null
                    this.action = 'create'
                }
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
