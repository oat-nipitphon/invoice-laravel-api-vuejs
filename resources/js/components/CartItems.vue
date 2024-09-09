<script setup>
import { onMounted, ref } from 'vue'
import logoImage from '@/../assets/img/logo.png'
import { useRouter } from 'vue-router';

const router = useRouter()

const showModal = ref(false)
const hideModal = ref(true)

let form = ref([])
let customers = ref([])
let customer_id = ref([])
let item = ref([])
let listCart = ref([])
let listProducts = ref([])

onMounted(async () => {
    indexForm()
    getCustomers()
    getProducts()
    removeitem()
    SubTotal()
    Total()
    onSave()
})



const getCustomers = async () => {
    try {
        let response = await axios.get('/api/get_customers');
        console.log('Customer respones', response);
        customers.value = response.data.customers
    } catch (error) {
        console.error('Error getCustomers :', error);
    }
}


const getProducts = async () => {
    try {
        let responese = await axios.get('/api/get_products');
        console.log('Get Products :: ', responese);
        listProducts.value = responese.data.products
    } catch (error) {
        console.error('Error Get Products :: ', error);
    }
}

const openModal = () => {
    showModal.value = !showModal.value
}

const closeModal = () => {
    showModal.value = !hideModal.value
}


const onSave = () => {

    if (listCart.value.length >= 1) {

        let subtotal = 0
        subtotal = SubTotal()

        let total = 0
        total = Total()

        const formData = new FormData()
        formData.append('invoice_item', JSON.stringify(listCart.value))
        formData.append('customer_id', customer_id.value)
        formData.append('date', form.value.date)
        formData.append('due_date', form.value.due_date)
        formData.append('number', form.value.number)
        formData.append('reference', form.value.reference)
        formData.append('discount', form.value.discount)
        formData.append('subtotal', subtotal)
        formData.append('total', total)
        formData.append('terms_and_conditions', form.value.terms_and_conditions)

        axios.post("/api/CartItems/Insert", formData)
        listCart.value = []
        // location.reload();
    }
}

const onPrint = () => {
    window.print()
    // router.push('/').catch(() => {})
}

const onBack = () => {
    router.push('/');
}

const onReset = () => {
    location.reload()
}

</script>
<template>

    <div class="container">
        <!--==================== Create Invoice New ====================-->
        <div class="invoices">
            <div class="card__content">
                <div class="table">
                    <div class="table--heading2">
                        <div style="padding: 0px 0px !important;">
                            <a class="button" @click="openModal()">
                                <span>
                                    add product
                                </span>
                            </a>
                        </div>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p> # </p>
                    </div>

                    <!-- Start Items List -->
                    <div class="table--items2" v-for="(itemcart) in listCart" :key="itemcart.id">
                        <p> # {{ itemcart.item_code }} {{ itemcart.description }}</p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.unit_price" readonly />
                        </p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.quantity">
                        </p>
                        <p v-if="itemcart.quantity">
                            $ {{ (itemcart.quantity) * (itemcart.unit_price) }}
                        </p>
                        <p v-else></p>
                        <p style="color: red; font-size: 18px;cursor: pointer;" @click="removeitem()">
                            &times;
                        </p>
                    </div>
                    <!-- <div class="table--items2" v-for="(itemcart, i) in form.invoice_item" :key="itemcart.id" >
                        <p v-if="itemcart.product">{{ itemcart.product.item_code }}{{ itemcart.product.description }}</p>
                        <p v-else> {{ itemcart.item_code }}{{ itemcart.description }}</p>
                        <p><input type="text" class="input" v-model="itemcart.unit_price" readonly /></p>
                        <p><input type="text" class="input" v-model="itemcart.quantity"></p>
                        <p style="text-align: center; font-weight: bold;">$
                            {{ itemcart.unit_price * itemcart.quantity }}
                        </p>
                        <p style="text-align: center; font-weight: bold; color: red; font-size: 18px;cursor: pointer;" :value="i+1"
                        @click="removeItemCart(itemcart.id, i)">
                            &times;
                        </p>
                    </div> -->

                    <!-- End Items List -->

                </div>
                <div class="table__footer">
                    <div class="document-footer">

                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>{{ SubTotal() }}</span>
                            <p class="text-center">$</p>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="form.discount">
                            <p> % </p>
                        </div>
                        <div class="table__footer--total grand__total">
                            <p>Total</p>
                            <span> {{ Total() }}</span>
                            <p> $ </p>
                        </div>
                    </div>
                </div>
                <div class="card__footer text-center" style="margin-top: 20px;">
                    <div>

                    </div>
                    <div>
                        <a class="btn btn-sm button" @click="onSave()">
                            <span>
                                Save
                            </span>
                        </a>
                        <a class="btn btn-sm button" @click="onReset()">
                            <span>
                                Reset
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- =============  Start Modal Items Products ============= -->
        <div class="modal main__modal " :class="{ show: showModal }">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
                <h3 class="modal__title">Add Item</h3>
                <hr><br>
                <div class="modal__items">
                    <ul style="list-style:none">
                        <li v-for="(item, i) in listProducts" :key="item.id"
                            style="display:grid;grid-template-columns: 30px 350px 15px; align-items: center; padding-bottom: 5px;">
                            <p>{{ i + 1 }}</p>
                            <a href="#">{{ item.item_code }} {{ item.description }}</a>
                            <button @click="addCart(item)"
                                style="border:1px solid; width: 35px; height: 35; cursor: pointer;">
                                +
                            </button>
                        </li>
                    </ul>
                </div>
                <br>
                <hr>
                <div class="model__footer">
                    <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>
        <!-- =============  Stop Modal Items Products ============= -->

    </div>
</template>
