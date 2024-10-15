<template>
	<v-container>
        <form @submit.prevent="login">
            <v-card width="420px" class="card-rounded">
                <v-card-title>
                    <span class="title">Login</span>
                </v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="formData.email"
                        :error-messages="errors.get('auth.email')"
                        type="email"
                        name="email"
                        variant="outlined"
                        label="Email"
                        class="ma-2" />

                    <v-text-field
                        v-model="formData.password"
                        :error-messages="errors.get('auth.password')"
                        type="password"
                        name="password"
                        variant="outlined"
                        label="Password"
                        class="ma-2" />
                    <div class="d-flex align-center justify-space-between">
                        <v-btn round
                            :loading="loading"
                            type="submit"
                            color="primary"
                            class="ma-2">Log in
                        </v-btn>
                        <router-link :to="{name: 'register'}">Register</router-link>
                    </div>
                </v-card-text>
            </v-card>
        </form>
	</v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRequestStore } from '@/store/requestStore.js'
import { useRouter } from 'vue-router'

const router = useRouter()
const errors = useRequestStore()
errors.clear()

const loading = ref(false)

const formData = ref({
    email: null,
    password: null,
    tag: 'auth'
})

const login = async () => {
    loading.value = true
    errors.clear()
    axios.post(`/api/auth/login?tag=auth`, formData.value).then(res => {
        router.push('/');
    }).finally(() => {
        loading.value = false
        errors.clear()
    })
}

</script>
