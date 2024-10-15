<template>
    <form method="post" @submit.prevent="submit">
        <v-card width="420px" class="mx-auto mt-4">
            <v-card-title>
                <span class="title">Register</span>
            </v-card-title>
            <v-card-text>

                <v-text-field class="my-3" v-model="formData.name" label="Owner name" variant="outlined" :error-messages="errors.get('register.name')" @keydown="clear('register.name')" />

                <v-text-field class="my-3" v-model="formData.email" label="Email" variant="outlined" :error-messages="errors.get('register.email')" @keydown="clear('register.email')" />

                <v-text-field class="my-3" v-model="formData.password" label="Password" variant="outlined"
                    :error-messages="errors.get('register.password')"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showPassword ? 'text' : 'password'"
                    @click:append="showPassword = !showPassword"
                    @keydown="clear('register.password')"  />

                <v-expand-transition>
                    <v-text-field class="my-3" v-if="!showPassword" v-model="formData.confirmPassword" label="Confirm Password" variant="outlined" />
                </v-expand-transition>

                <div class="d-flex align-center justify-space-between">
                    <v-btn type="submit" color="primary" :loading="loading">Register</v-btn>
                    <router-link :to="{name: 'login'}" class="text-end">Login</router-link>
                </div>
            </v-card-text>
        </v-card>
    </form>
</template>

<script setup>
import { ref } from 'vue'
import { useRequestStore } from '@/store/requestStore.js'

const errors = useRequestStore()
errors.clear()

const formData = ref({
    name: null,
    email: null,
    password: null
});

const showPassword = ref(false)
const loading = ref(false)

const submit = async () => {
    loading.value = true
    axios.post('/api/register?tag=register', formData.value).finally(() => {
        loading.value = false
    })
}

const clear = (key) => {
    errors.clear(key)
}
</script>
