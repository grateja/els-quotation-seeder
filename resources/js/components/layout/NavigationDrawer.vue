<template>
    <v-app>
        <teleport to="body">
            <v-progress-linear indeterminate absolute style="top: 0; z-index: 9999;" color="red" v-if="navigating" />
        </teleport>
        <v-navigation-drawer
            v-model="drawer"
            app
        >
            <v-list-item v-if="currentUser" :title="currentUser.name" :subtitle="currentUser.email"></v-list-item>
            <v-divider></v-divider>
            <v-list-item v-for="item in items" link :title="item.title" :to="item.to">

            </v-list-item>
        </v-navigation-drawer>

        <v-app-bar
        app
        color="primary"
        dark
        >
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>{{$route.meta.displayName}}</v-toolbar-title>
            <v-spacer/>
            <v-btn v-if="currentUser">{{ currentUser.name }}</v-btn>
            <v-btn @click="logout" :loading="loading">logout</v-btn>
        </v-app-bar>
        <v-main>
            <router-view/>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useAuthStore } from '@/store/authStore.js'
import { navigating } from '@/router.js'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()

const currentUser = computed(() => authStore.currentUser)
const router = useRouter()

const drawer = ref(false)
const loading = ref(false)
const items = [
    { title: 'Product lines', to: '/product-lines' },
    { title: 'Sales Representative', to: '/sales-representatives' },
    { title: 'Customers', to: '/customers' },
    { title: 'Quotations', to: '/quotations' },
];

const logout = async () => {
    loading.value = true
    axios.post('/api/auth/logout').then(res => {
        router.push({
            name: 'login'
        })
    }).finally(() => {
        loading.value = false
    })
}

axios.get('/api/auth/check').then(res => {
    authStore.setUser(res.data)
}).catch(err => {
    router.push({name: 'login'})
})

</script>

<style scoped>
/* Add any custom styles here */
</style>
