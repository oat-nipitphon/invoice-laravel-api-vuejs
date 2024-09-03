import { createRouter,  createWebHistory } from 'vue-router'
import notFound from '../components/NotFound.vue'
import invoiceIndex from '../components/invoices/index.vue'
import CreateNew from '../components/invoices/CreateNew.vue'

const routes = [
    {
        path:'/:pathMatch(.*)*',
        component: notFound
    },
    {
        path:'/',
        component: invoiceIndex
    },
    {
        path:'/invoice/new',
        component: CreateNew
    },

]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
