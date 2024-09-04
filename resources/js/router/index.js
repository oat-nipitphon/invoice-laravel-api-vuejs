import { createRouter,  createWebHistory } from 'vue-router'
import notFound from '../components/NotFound.vue'
import invoiceIndex from '../components/invoices/index.vue'
import CreateNew from '../components/invoices/CreateNew.vue'
import ShowInvoice from '../components/invoices/ShowInvoice.vue'

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
    {
        path:'/invoice/show/:id',
        component: ShowInvoice,
        props: true
    },

]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
