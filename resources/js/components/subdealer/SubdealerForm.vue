<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} RBP</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.abbr" label="Name" variant="outlined" :error-messages="errors.get('abbr')"/>
                    <v-text-field class="my-4" v-model="formData.name" label="Initials" variant="outlined" :error-messages="errors.get('name')"/>
                    <v-text-field class="my-4" v-model="formData.area" label="Department" variant="outlined" :error-messages="errors.get('area')"/>
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
    props: ['subdealer'],
    data() {
        return {
            action: 'create',
            formData: {
                abbr: null,
                name: null,
                area: null,
            }
        }
    },
    methods: {
        submit() {
            let url = this.subdealer ? `/api/subdealers/${this.subdealer.id}/${this.action}` : `/api/subdealers/${this.action}`

            this.$store.dispatch('post', {
                tag: 'save-subdealer',
                url,
                formData: this.formData
            }).then((res, rej) => {
                this.$emit('close');
                this.$emit('save', {
                    action: this.action,
                    subdealer: res.data
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
        subdealer: {
            handler(newVal, oldVal) {
                if(newVal) {
                    this.formData.abbr = newVal.abbr;
                    this.formData.name = newVal.name;
                    this.formData.area = newVal.area;
                    this.action = 'update'
                } else {
                    this.formData.abbr = null;
                    this.formData.name = null;
                    this.formData.area = null;
                    this.action = 'create'
                }
            },
            deep: true,
            immediate: true
        }
    }
}
</script>
