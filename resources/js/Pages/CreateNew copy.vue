<script setup>
import { onMounted, ref } from 'vue'
import logoImage from '@/../assets/img/logo.png'
import router from './resources/js/router/index.js'

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
    getProduct()
    removeitem()
    SubTotal()
    Total()
    onSave()
})

const indexForm = async () => {
    try {
        let formData = await axios.get('/api/create_invoice');
        // console.log('Form Create Invoice :', formData.data);
        form.value = formData.data
    } catch (error) {
        console.error('Error indexForm :', error);
    }
}

const getCustomers = async () => {
    try {
        let response = await axios.get('/api/get_all_customer');
        console.log('Customer respones', response);
        customers.value = response.data.customers
    } catch (error) {
        console.error('Error getCustomers :', error);
    }
}

const addCart = (item) => {
    try{
        const itemcarts = {
        id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price: item.unit_price,
        quantity: item.quantity,
    }
    listCart.value.push(itemcart)
    closeModal()
    } catch(error){
        console.log('error listCart :', $listCart);
    }
}

const removeitem = (i) => {
    listCart.value.splice(i, 1)
}

const getProduct = async () => {
    try {
        let responese = await axios.get('/api/getProduct');
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

const SubTotal = () => {
    let total = 0;
    listCart.value.map((data) => {
        total += data.quantity * data.unit_price;
    });
    return total;
};

const Discount = () => {
    if (!form.value.discount) return 0; // Check if discount exists
    return SubTotal() * form.value.discount / 100;
}

const Total = () => {
    return SubTotal() - Discount()
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

        axios.post("/api/createInvoiceConfig", formData)
        listCart.value = []
        router.push('/')
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
            <div class="card__header">
                <div class="col-md-6">
                    <h2 class="invoice__title">
                        <p style="font-size: 24px;">Invoice #{{ form.id }}</p>
                        <p style="font-size: 16px; ">{{ form.created_at }}</p>
                    </h2>
                </div>
                <div class="col-md-6">
                    <ul class="card__header-list" style="margin-top:15px;">
                        <li>
                            <button class="button selectBtnFlat" @click="onPrint()">
                                <i class="fas fa-print"></i>
                                Print
                            </button>
                        </li>
                        <li>
                            <button class="button selectBtnFlat " @click="onBack()">
                                <i class=" fas fa-pencil-alt"></i>
                                Back
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="invoice__header--title">
                <p style="margin-top:15px;margin-left:15px;">
                    <b style="font-size: 24px;">Create New</b>
                </p>
                <p class="invoice__header--title-1">
                    <img :src="logoImage" alt="Logo" style="width: 200px;">
                </p>
                <p></p>
            </div>
            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="customer_id">
                            <option disabled>Select Customer</option>
                            <option :value="customer.id" v-for="customer in customers" :key="customer.id">
                                {{ customer.firstname }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date">
                        <p class="my-1">Due Date</p>
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Number</p>
                        <input type="text" class="input" v-model="form.number">
                        <p class="my-1">Reference(Optional)</p>
                        <input type="text" class="input" v-model="form.reference">
                    </div>
                </div>
                <div class="table">
                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>
                    <div class="table--items2" v-for="(itemcart) in listCart" :key="itemcart.id">
                        <p> # {{ itemcart.item_code }} {{ itemcart.description }}</p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.unit_price">
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
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="openModal()">
                            Add New Line
                        </button>
                    </div>
                </div>
                <div class="table__footer">
                    <div class="document-footer">
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ {{ SubTotal() }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="form.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{ Total() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>
                    <button class="button-save" @click="onSave()">
                        Save
                    </button>
                </div>
                <div>
                    <button class="button-back" @click="onReset()">
                        Reset
                    </button>
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

<style>
.font-footer-data {
    margin-left: 30px;
    font-size: 16px;
    font-weight: bold;
}
</style>
