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
                    :loading="loading"
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
<script setup>
import { ref, defineProps, defineEmits } from 'vue';
import { debounce } from 'lodash'

const props = defineProps(['model'])
const emit = defineEmits(['select', 'close'])

const keyword = ref('')
const items = ref([])
const loading = ref(false)
let page = 1

const onInput = debounce(() => {
    loading.value = true
    axios.get('/api/customers', {
        params: {
            keyword: keyword.value,
            page
        }
    }).then(res => items.value = res.data.data).finally(() => {
        loading.value = false
    })
}, 500)

const select = (data) => {
    emit('select', data)
    close()
}

const close = () => {
    emit('close')
}

onInput()
</script>

<style scoped>
.highlighted {
    background-color: #c4e8f5;
    font-weight: bold;
}
</style>
