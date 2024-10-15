import { defineStore } from "pinia";
import { ref } from 'vue'

export const useMainStore = defineStore('useMainStore', () => {
    const navigating = ref(false)

    const updateNavigationState = (value) => {
        navigating.value = value
    }

    return { navigating, updateNavigationState }
})
