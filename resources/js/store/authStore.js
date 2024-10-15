import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('authStore', () => {
    const currentUser = ref(null)
    const setUser = (user) => {
        console.log('set user', user)
        currentUser.value = user
    }
    return { currentUser, setUser }
})
