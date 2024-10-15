<template>
    <div>
        <v-card>
            <v-card-title>Select subdealer</v-card-title>
            <v-card-text>
                <v-btn @click="select(null)" color="primary" class="my-4">Create new</v-btn>
                <v-text-field :loading="loading" variant="outlined" v-model="keyword" @input="onInput" append-inner-icon="mdi-magnify" placeholder="Type name" />
                <v-list>
                    <v-list-item :class="{ 'highlighted': selected(subdealer) }" v-for="(subdealer, index) in items" :key="subdealer.id" @click="select(subdealer)">
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
<script setup>
import { ref, defineProps, defineEmits } from 'vue'
import { debounce } from 'lodash'

const props = defineProps(['model'])
const emit = defineEmits(['select', 'close'])

const keyword = ref('')
const items = ref([])
const loading = ref(false)

const onInput = debounce(async () => {
    loading.value = true
    axios.get('/api/subdealers', {
        params: {
            keyword: keyword.value
        }
    }).then(res => {
        items.value = res.data.data
    }).finally(() => {
        loading.value = false
    })
}, 500)

const close = () => {
    emit('close');
}

const select = (data) => {
    emit('select', data);
    close()
}

const selected = (item) => {
    return props.model != null && item.id == props.model.id
}

onInput()
</script>

<style scoped>
.highlighted {
    background-color: #c4e8f5;
    font-weight: bold;
}
</style>
