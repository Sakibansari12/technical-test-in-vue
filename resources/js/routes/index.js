import { createWebHistory, createRouter } from "vue-router"
import store from '../store'

import 'nprogress/nprogress.css'
import NProgress from "nprogress"

const routes = [
    {
        path: '/pms',
        children:[
            {
                path: '',
                name: 'login',
                component: () => import('../pms/login.vue'),
                meta: {
                    isHeader: false
                }
            },
            {
                path: 'register',
                name: 'register',
                component: () => import('../pms/register.vue'),
                meta: {
                    isHeader: false
                }
            },
            
            {
                path: 'add-task/:id?',
                name: 'add-task',
                component: () => import('../pms/task/add.vue'),
                meta: {
                    isHeader: true,
                    requiresAuth: true,
                    permission: ['Admin']
                }
            },
            {
                path: 'list-task',
                name: 'list-task',
                component: () => import('../pms/task/list.vue'),
                meta: {
                    isHeader: true,
                    requiresAuth: true,
                    permission: ['Admin']
                }
            },
            {
                path: 'change-password',
                name: 'change-password',
                component: () => import('../pms/change-password.vue'),
                meta: {
                    isHeader: true,
                    requiresAuth: true,
                    permission: ['Admin']
                }
            },
            {
                path: '/:pathMatch(.*)*',
                redirect: '/pms/dashboard',
                meta: {
                    isHeader: true
                }
            }
        ]
    },


]

const router = createRouter({
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 }
    },
    routes,
})


router.beforeEach((to, from) => {
    if(to.matched.some(route => route.meta.requiresAuth)){
        if(store.getters.user){
            if(to.meta.permission?.length && !to.meta.permission?.includes(store.getters.user.role)){
                return {
                    name : 'dashboard'
                }
            }
            else{
                return
            }
        }
        else{
            return {
                name : 'login'
            }
        }
    }
})

router.beforeResolve((to, from, next) => {
    if (to.name) {
      NProgress.start()
    }
    next()
})

router.afterEach((to, from) => {
    NProgress.done()
})


export default router
