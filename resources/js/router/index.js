import { createRouter,  createWebHistory } from 'vue-router'
import notFound from '../components/NotFound.vue'
import Index from '../components/invoices/index.vue'
import New from '../components/invoices/New.vue'
import Show from '../components/invoices/Show.vue'
import Edit from '../components/invoices/Edit.vue'
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
        component: Index
    },
    {
        path:'/invoice/new',
        component: New
    },
    {
        path:'/invoice/show/:id',
        component: Show,
        props: true
    },
    {
        path:'/invoice/edit/:id',
        component: Edit,
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
