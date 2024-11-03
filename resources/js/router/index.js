import { createRouter,  createWebHistory } from 'vue-router'
import NotFound from '../components/NotFound.vue'
import SwalConfigCancel from '../components/SwalConfigCancel.vue'
import Index from '../Pages/Index.vue'
import New from '../Pages/New.vue'
import Show from '../Pages/Show.vue'
import Edit from '../Pages/Edit.vue'
import CartItems from '../Pages/CartItems.vue'

const routes = [
    {
        path:'/:pathMatch(.*)*',
        component: NotFound
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
    },

    {
        path:'/sweetalert/test',
        component:SwalConfigCancel
    },



]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
