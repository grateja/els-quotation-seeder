<template>
    <div>
        <v-card>
            <v-card-title>Select subdealer</v-card-title>
            <v-card-text>
                <v-btn @click="select(null)" color="primary" class="my-4">Create new</v-btn>
                <v-text-field variant="outlined" v-model="keyword" @input="onInput" append-inner-icon="mdi-magnify" placeholder="Type name" />
                <v-list>
                    <v-list-item :class="{ 'highlighted': model && subdealer.id == model.id }" v-for="(subdealer, index) in items" :key="subdealer.id" @click="select(subdealer)">
                        <v-list-item-title>
                            {{ subdealer.alias }}
                        </v-list-item-title>
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
            axios.get('/api/subdealers', {
                params: {
                    keyword: this.keyword
                }
            }).then((res, rej) => {
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
