import { createRouter, createWebHistory } from "vue-router";

import _404 from './components/_404.vue'
import MainBody from './components/MainBody.vue'
import Login from './components/auth/Login.vue'
import Register from './components/register/Index.vue'

const router = createRouter({
    history: createWebHistory(),
    routes: [
    	{
    		path: "/",
            name: 'home',
    		component: MainBody,
            children: [
                {
                    path: 'product-lines',
                    name: 'productLines',
                    component: () => import('./components/product-lines/Index.vue'),
                    meta: {displayName: 'Product lines'},
                    children: [
                        {
                            path: ':id',
                            name: 'productLine',
                            meta: {displayName: 'Products'},
                            component: () => import('./components/product-lines/products/Index.vue')
                        }
                    ]
                },
                {
                    path: 'products/:productId',
                    name: 'viewProduct',
                    meta: {displayName:'Product details'},
                    component: () => import('./components/product-lines/products/Preview.vue')
                },
                {
                    path: 'sales-representatives',
                    name: 'salesRepresentatives',
                    component: () => import('./components/sales-representatives/Index.vue'),
                    meta: {displayName: 'Sales representatives'},
                },
                {
                    path: 'customers',
                    name: 'customers',
                    component: () => import('./components/customers/Index.vue'),
                    meta: {displayName: 'Customers'},
                },
                {
                    path: 'quotations',
                    name: 'quotations',
                    component: () => import('./components/quotations/Index.vue'),
                    meta: {displayName: 'Quotations'},
                    children: [
                        {
                            path: ':id/edit',
                            name: 'editQuotation',
                            component: () => import('./components/quotations/EditItems.vue'),
                            meta: {displayName: 'Edit quotation'},
                        }
                    ]
                },
            ]
    	},
    	{
    		path: "/login",
            name: 'login',
    		component: Login
    	},
        {
            path: '/register',
            name: 'register',
            component: Register
        },
	    {
	    	path: "/:pathMatch(.*)",
	    	name: 'not found',
	    	component: _404
	    }
	]
})

export default router
