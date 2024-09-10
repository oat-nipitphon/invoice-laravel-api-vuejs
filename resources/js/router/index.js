import { createRouter,  createWebHistory } from 'vue-router'
import notFound from '../components/NotFound.vue'
import invoiceIndex from '../components/invoices/index.vue'
import New from '../components/invoices/New.vue'
import ShowInvoice from '../components/invoices/ShowInvoice.vue'
import FormEditInvoice from '../components/invoices/FormEdit.vue'
import CartItems from '../components/CartItems.vue'
import SwalConfigCancel from '../components/sweetalert2/SwalConfigCancel.vue'

const routes = [
    {
        path:'/:pathMatch(.*)*',
        component: notFound
    },
    {
        path:'/sweetalert/test',
        component:SwalConfigCancel
    },
    {
        path:'/',
        component: invoiceIndex
    },
    {
        path:'/invoice/new',
        component: New
    },
    {
        path:'/invoice/show/:id',
        component: ShowInvoice,
        props: true
    },
    {
        path:'/invoice/edit/:id',
        component: FormEditInvoice,
        props: true
    },
    {
        path:'/cart_item',
        component: CartItems
    }



]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
