<template>
    <div>
        <v-card>
            <v-card-title>Select customer</v-card-title>
            <v-card-text>
                <v-btn @click="select(null)" color="primary" class="my-4">Create new</v-btn>
                <v-text-field
                    v-model="keyword"
                    append-inner-icon="mdi-magnify"
                    placeholder="Type customer name"
                    variant="outlined"
                    :loading="loadingKeys.hasAny('get-customers')"
                    @input="onInput"
                />
                <v-list>
                    <v-list-item
                        v-for="(customer, index) in items"
                        :title="customer.name"
                        :subtitle="customer.address"
                        :class="{ 'highlighted': model && customer.id == model.id }"
                        :key="customer.id"
                        @click="select(customer)"
                    >
                    </v-list-item>
                </v-list>
            </v-card-text>
            <v-card-actions>
                <v-btn @click="close">close</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script>
export default {
    props: [
        'model'
    ],
    data: () => {
        return {
            keyword: '',
            items: []
        }
    },
    methods: {
        onInput() {
            this.debouncedLoadData();
        },
        loadData() {
            this.$store.dispatch('get', {
                url: '/api/customers',
                tag: 'get-customers',
                formData: {
                    keyword: this.keyword
                }
            })
            // axios.get('/api/customers', {
            //     params: {
            //         keyword: this.keyword
            //     }
            // })
            .then((res, rej) => {
                this.items = res.data.data;
            });
        },
        select(data) {
            this.$emit('select', data);
            this.$emit('close');
        },
        close() {
            this.$emit('close');
        }
    },
    computed: {
        loadingKeys() {
            return this.$store.getters.loadingKeys;
        }
    },
    created() {
        this.debouncedLoadData = this.$debounce(this.loadData, 100);
        this.loadData();
    }
}
</script>

<style scoped>
.highlighted {
    background-color: #c4e8f5;
    font-weight: bold;
}
</style>
