import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useRequestStore = defineStore('requestStore', () => {
    const errorKeys = ref([]);
    const loadingKeys = ref([]);

    const setErrors = (taggedErrors) => {
        if(!taggedErrors) {
            errorKeys.value = [];
            return
        }
        errorKeys.value = Object.keys(taggedErrors.errors).map(key => {
            return {
                key: `${taggedErrors.tag}.${key}`,
                messages: taggedErrors.errors[key]
            };
        });
    }

    const get = (key) => {
        const error = errorKeys.value.find(error => error.key === key)
        if(error) return error.messages[0]
        return null
    }

    const clear = (key) => {
        key ?
            errorKeys.value = errorKeys.value.filter(item => item.key != key)
                : setErrors(null)
    }

    const has = (key) => {
        return errorKeys.value.some(error => error.key === key)
    }

    return { setErrors, get, clear, has}
});
