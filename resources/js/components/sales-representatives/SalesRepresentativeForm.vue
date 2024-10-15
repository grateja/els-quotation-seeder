<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} sales representative</v-card-title>
                <v-card-text>
                    <v-text-field class="my-4" v-model="formData.name" label="Name" variant="outlined" :error-messages="errors.get('save-sales-rep.name')"/>
                    <v-text-field class="my-4" v-model="formData.initials" label="Initials" variant="outlined" :error-messages="errors.get('save-sales-rep.initials')"/>
                    <v-text-field class="my-4" v-model="formData.department" label="Department" variant="outlined" :error-messages="errors.get('save-sales-rep.department')"/>
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

const errors = useRequestStore()

let action = 'create'
const props = defineProps(['salesRepresentative'])
const emit = defineEmits(['close', 'save'])
const saving = ref(false)
const formData = ref({
    name: null,
    initials: null,
    department: null,
})

const close = () => {
    emit('close')
}

const submit = async () => {
    errors.clear()
    saving.value = true

    let url = props.salesRepresentative ? `/api/sales-representatives/${props.salesRepresentative.id}/${action}` : `/api/sales-representatives/${action}`

    axios.post(url + '?tag=save-sales-rep', formData.value).then(res => {
        emit('save', {
            action,
            salesRepresentative: res.data
        })
        close();
    }).finally(() => {
        saving.value = false
    })
}

watch(() => props.salesRepresentative, (newVal) => {
    if(newVal) {
        formData.value.name = newVal.name;
        formData.value.initials = newVal.initials;
        formData.value.department = newVal.department;
        action = 'update'
    } else {
        formData.value.name = null;
        formData.value.initials = null;
        formData.value.department = null;
        action = 'create'
    }
}, {immediate: true})
</script>
