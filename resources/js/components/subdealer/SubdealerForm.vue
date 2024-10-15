<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} RBP</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.abbr" label="Name" variant="outlined" :error-messages="errors.get('save-subdealer.abbr')"/>
                    <v-text-field class="my-4" v-model="formData.name" label="Initials" variant="outlined" :error-messages="errors.get('save-subdealer.name')"/>
                    <v-text-field class="my-4" v-model="formData.area" label="Area" variant="outlined" :error-messages="errors.get('save-subdealer.area')"/>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn text="Close" @click="$emit('close')"/>
                    <v-btn type="submit" color="primary" :loading="saving">Save</v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </div>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue'
import { useRequestStore } from '@/store/requestStore.js'

const props = defineProps(['subdealer'])
const emit = defineEmits(['close', 'save'])
let action = 'create'
const saving = ref(false)
const formData = ref({
    abbr: '',
    name: '',
    area: ''
})

const errors = useRequestStore()
errors.clear()

const submit = async () => {
    let url = (props.subdealer ? `/api/subdealers/${props.subdealer.id}/${action}` : `/api/subdealers/${action}`) + '?tag=save-subdealer'
    saving.value = true

    axios.post(url, formData.value).then(res => {
        close()
        emit('save', {
            action,
            subdealer: res.data
        })
    }).finally(() => {
        saving.value = false
    })
}

const close = () => {
    emit('close')
}

watch(() => props.subdealer, (newVal) => {
    if(newVal) {
        formData.value.abbr = newVal.abbr;
        formData.value.name = newVal.name;
        formData.value.area = newVal.area;
        action = 'update'
    } else {
        formData.value.abbr = null;
        formData.value.name = null;
        formData.value.area = null;
        action = 'create'
    }
}, {immediate : true})
</script>
