import { useRequestStore } from './store/requestStore.js';

axios.interceptors.response.use(res => res, err => {
    if(err.response && err.response.status == 422 && err.response.data.errors) {
        useRequestStore().setErrors({
            tag: err.response.data.tag,
            errors: err.response.data.errors
        });
        return Promise.reject(err);
    }
});
