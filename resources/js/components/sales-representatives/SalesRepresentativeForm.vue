<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} sales representative</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.name" label="Name" variant="outlined" :error-messages="errors.get('name')"/>
                    <v-text-field class="my-4" v-model="formData.initials" label="Initials" variant="outlined" :error-messages="errors.get('initials')"/>
                    <v-text-field class="my-4" v-model="formData.department" label="Department" variant="outlined" :error-messages="errors.get('department')"/>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text="Close" @click="$emit('close')"/>
                    <v-btn type="submit" color="primary">Save</v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </div>
</template>

<script>
export default {
    props: ['salesRepresentative'],
    data() {
        return {
            action: 'create',
            formData: {
                name: null,
                initials: null,
                department: null,
            }
        }
    },
    methods: {
        submit() {
            let url = this.salesRepresentative ? `/api/sales-representatives/${this.salesRepresentative.id}/${this.action}` : `/api/sales-representatives/${this.action}`

            this.$store.dispatch('post', {
                tag: 'save-sales-representatives',
                url,
                formData: this.formData
            }).then((res, rej) => {
                this.$emit('close');
                this.$emit('save', {
                    action: this.action,
                    salesRepresentative: res.data
                });
            });
        }
    },
    computed: {
        errors() {
            return this.$store.getters.getErrors;
        }
    },
    watch: {
        salesRepresentative: {
            handler(newVal, oldVal) {
                if(newVal) {
                    this.formData.name = newVal.name;
                    this.formData.initials = newVal.initials;
                    this.formData.department = newVal.department;
                    this.action = 'update'
                } else {
                    this.formData.name = null;
                    this.formData.initials = null;
                    this.formData.department = null;
                    this.action = 'create'
                }
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
