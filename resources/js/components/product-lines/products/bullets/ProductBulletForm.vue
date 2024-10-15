<template>
    <div>
        <form @submit.prevent="submit">
            <v-card>
                <v-card-title>{{action}} product bullet</v-card-title>
                <v-card-text>
                    <v-select label="Bullet type" :items="bulletTypes" v-model="formData.bullet_type" variant="outlined" :error-messages="errors.get('save-bullet.bullet_type')"></v-select>
                    <v-text-field class="my-4" v-model="formData.description" label="Description" variant="outlined" :error-messages="errors.get('save-bullet.description')"/>
                    <v-expand-transition>
                        <div v-if="formData.bullet_type == 'item'">
                            <v-row>
                                <v-col cols="4">
                                    <v-select
                                        class="my-4"
                                        v-model="formData.unit"
                                        label="Unit"
                                        variant="outlined"
                                        :items="units"
                                        :error-messages="errors.get('save-bullet.unit')"
                                    ></v-select>
                                </v-col>
                                <v-col cols="8">
                                    <v-text-field class="my-4" v-model="formData.quantity" type="number" label="Quantity" variant="outlined" :error-messages="errors.get('save-bullet.quantity')"/>
                                </v-col>
                            </v-row>
                        </div>
                    </v-expand-transition>
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

<script setup>
import { ref, computed, watch, defineProps, defineEmits } from 'vue'
import { useRequestStore } from '@/store/requestStore.js'
import { useMscStore } from '@/store/mscStore'

const props = defineProps({
    bullet: {
        type: [Object, null],
        required: true
    },
    productId: {
        type: String,
        required: true
    },
    stackOrder: {
        type: Number,
        required: true
    }
})

const errors = useRequestStore()
const emit = defineEmits(['close', 'save'])
const mscStore = useMscStore()
let action = 'create'
const saving = ref(false)
const formData = ref({
    description: null,
    quantity: null,
    unit: null,
    bullet_type: 'simple',
})

const submit = () => {
    errors.clear()
    saving.value = true

    let url = (props.bullet ? `/api/product-bullets/${props.bullet.id}/update` : `/api/product-bullets/${props.productId}/create`) + '?tag=save-bullet'

    if(action == 'create') {
        formData.value.stack_order = props.stackOrder;
    }

    axios.post(url, formData.value).then(res => {
        emit('close');
        emit('save', {
            action,
            bullet: res.data
        });
    }).finally(() => {
        saving.value = false
    })
}

const units = computed(() => mscStore.units)
const bulletTypes = computed(() => mscStore.bulletTypes)

watch(() => props.bullet, (newVal) => {
    if(newVal) {
        formData.value.description = newVal.description;
        formData.value.quantity = newVal.quantity;
        formData.value.unit = newVal.unit;
        formData.value.bullet_type = newVal.bullet_type;
        formData.value.stack_order = newVal.stack_order;
        action = 'update'
    } else {
        formData.value.description = null;
        formData.value.unit = null;
        formData.value.unit_price = null;
        formData.value.bullet_type = 'simple';
        action = 'create'
    }
}, {immediate: true})

errors.clear()
</script>
