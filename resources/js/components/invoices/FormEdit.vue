<script setup>
import { onMounted, ref } from 'vue'
import logoImage from '@/../assets/img/logo.png'
import router from '../../router/index.js';

let form = ref([])
let customers = ref([])
let customer_id = ref([])
let item = ref([])
let listCart = ref([])
let listProducts = ref([])

const props = defineProps({
    id: {
        type: String,
        default: ''
    }
})

onMounted(async () => {
    getInvoiceFormEdit()
    getProducts()
    getCustomers()
    removeitemCart()
    SubTotal()
    Total()
    onUpdate()
})


const getInvoiceFormEdit = async () => {
    try {
        let response = await axios.get(`/api/show_get_invoice/${props.id}`);
        form.value = response.data.invoice
    } catch (error) {
        console.log('Error props id get invoice :: ', response);
    }
}

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

const addCart = (item) => {
    const itemcart = {
        id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price: item.unit_price,
        quantity: item.quantity,
    }
    listCart.value.push(itemcart)
    closeModal()
}

const removeitemCart = (i) => {
    listCart.value.splice(i, 1)
}

const showModal = ref(false)
const hideModal = ref(true)

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

const onDeleteItem = (id, i) => {
    form.value.invoice_item.splice(i, 1)
    if (id != undefined) {
        axios.get(`/api/delete_invoice_item/${id}`);
    }
}

const onUpdate = async (id) => {
    console.log('Button Update Invoice' + id);
}

const onPrint = () => {
    window.print()
    // router.push('/').catch(() => {})
}

const onBack = () => {
    router.push('/');
}

const onReset = () => {
    location.reload();
}


</script>
<template>
    <!--==================== Form EDIT INVOICE ====================-->
    <div class="container">
        <div class="invoices">
            <div class="table invoice">
                <!-- <div class="logo">..</div> -->
                <div class="invoice__header--title">
                    <p style="margin-top:15px;margin-left:15px;">
                        <b style="font-size: 16px;">
                            <label style="font-size: 20px;" @click="onBack()">INDEX</label>
                            <label style="font-size: 16px;"> / </label>
                            <label style="font-size: 20px;" @click="onReload()">Edit</label>
                        </b>
                    </p>
                    <p class="invoice__header--title-1">
                        <img :src="logoImage" alt="Logo" style="width: 200px;">
                    </p>
                    <p></p>
                </div>
                <div class="invoice__header--item">
                    <div>
                        <p style="margin-top: 5px;">
                            <b>Invoice :: </b>
                            <input type="text" class="input" name="number" id="number" v-model="form.number" readonly />
                        </p>
                        <p style="margin-top:5px;" >
                            <b>Name :: </b>
                            <select name="firstname" id="firstname" class="input" v-model="form.customer_id">
                                <option disabled></option>
                                <option :value="customer.id" v-for="customer in customers" :key="customer.id">
                                    {{form.customer.firstname}}
                                </option>
                            </select>
                        </p>
                        <p style="margin-top:5px;" v-if="form.customer">
                            <b>Email :: </b>
                            <input type="text" class="input" name="email" id="email" v-model="form.customer.email">
                        </p>
                        <p style="margin-top:5px;" v-if="form.customer">
                            <b>Address :: </b>
                            <textarea name="address" id="address" cols="50" rows="3"
                                v-model="form.customer.address"></textarea>
                        </p>
                    </div>
                    <div>
                        <p style="margin-top: 5px;">
                            <b>Date</b>
                            <input type="date" class="input" name="date" id="date" v-model="form.date">
                        </p>
                        <p style="margin-top: 5px;">
                            <b>Due Date</b>
                            <input type="date" class="input" name="due_date" id="due_date" v-model="form.due_date">
                        </p>
                        <p style="margin-top: 5px;">
                            <b>Reference</b>
                            <textarea name="reference" id="reference" cols="50" rows="6"
                                v-model="form.reference"></textarea>
                        </p>
                    </div>
                </div>
                <div style="margin-left: 35px; padding: 0px 0px !important;">
                    <a class="button" @click="openModal()">
                        <span>
                            add product
                        </span>
                    </a>
                </div>
                <div class="table py1">
                    <div class="table--heading2">
                        <p>ID</p>
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p style="text-align: center;">Total</p>
                        <p style="text-align: center;">#</p>
                    </div>
                    <div class="table--items2" v-for="(itemcart, i) in form.invoice_item" :key="itemcart.id" :value="i+1">
                        <p v-if="itemcart.product">{{ itemcart.product.item_code }}</p>
                        <p v-if="itemcart.product">{{ itemcart.product.description }}</p>
                        <p v-else> {{ itemcart.item_code }} {{ itemcart.description }}</p>
                        <p><input type="text" class="input" v-model="itemcart.unit_price" readonly /></p>
                        <p><input type="text" class="input" v-model="itemcart.quantity"></p>
                        <p style="text-align: center; font-weight: bold;">$
                            {{ itemcart.unit_price * itemcart.quantity }}
                        </p>
                        <p style="text-align: center; font-weight: bold; color: red; font-size: 18px;cursor: pointer;"
                        @click="removeitemCart()">
                            &times;
                        </p>
                    </div>
                </div>
                <div class="invoice__total">
                    <div>
                        <div class="invoice__subtotal--item1">
                            <p>Sub Total</p>
                            <span>{{ form.sub_total }}</span>
                        </div>
                        <div class="invoice__subtotal--item2">
                            <p>Discount</p>
                            <span>{{ form.discount }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="grand__total">
                            <div class="grand__total--items">
                                <p>Grand Total</p>
                                <span>{{ form.total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cart-footer-btn-action">
                    <div style="float: right;">
                        <button class="button" @click="onUpdate()">
                            <span>Save</span>
                        </button>
                        <button class="button" @click="onReset()">
                            <span>Reset</span>
                        </button>
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
                            <a>{{ item.item_code }} {{ item.description }}</a>
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
