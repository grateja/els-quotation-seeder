<template>
    <div>
        <v-card>
            <v-card-title>Select sales representative</v-card-title>
            <v-card-text>
                <v-btn @click="select(null)" color="primary" class="my-4">Create new</v-btn>
                <v-text-field :loading="loading" variant="outlined" v-model="keyword" @input="onInput" append-inner-icon="mdi-magnify" placeholder="Type sales rep. name" />
                <v-list>
                    <v-list-item :class="{ 'highlighted': model && salesRep.id == model.id }" v-for="(salesRep, index) in items" :key="salesRep.id" @click="select(salesRep)">
                        <v-list-item-title>
                            {{ salesRep.alias }}
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
<script setup>
import { ref, watch, defineEmits, defineProps } from 'vue'
import { debounce } from 'lodash'

const props = defineProps(['model'])
const emit = defineEmits(['select', 'close'])

const loading = ref(false)
const keyword = ref('')
const items = ref([])

const onInput = debounce(async () => {
    loading.value = true

    axios.get('/api/sales-representatives', {
        params: { keyword: keyword.value }
    }).then(res => {
        items.value = res.data.data
    }).finally(() => {
        loading.value = false
    })
}, 500)

const select = (item) => {
    emit('select', item)
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
