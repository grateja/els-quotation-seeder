import store from './store/store.js'
import router from './router.js'

store.dispatch('get', {
    tag: 'check',
    url: '/api/auth/check',
}).then((res, rej) => {
    console.log(res.data);
}).catch(err => {
    if(err.response && err.response.status == 401) {
        console.log(err)
        router.push({ name: 'login'})
    }
});
