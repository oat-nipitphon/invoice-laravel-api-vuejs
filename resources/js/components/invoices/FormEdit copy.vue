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
    getProduct()
    getCustomers()
    removeitem()
    SubTotal()
    Total()
    onUpdate()
})


const getInvoiceFormEdit = async () => {
    let response = await axios.get(`/api/show_get_invoice/${props.id}`);
    console.log('Get Data Form Edit Invoice :: ', response.data.invoice.date);
    form.value = response.data.invoice

}

const getCustomers = async () => {
    try {
        let response = await axios.get('/api/get_all_customers');
        console.log('Customer respones', response);
        customers.value = response.data.customers
    } catch (error) {
        console.error('Error getCustomers :', error);
    }
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

const removeitem = (i) => {
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
    console.log('Button Update Invoice' +id);
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
                            <button class="button selectBtnFlat " @click="onDelete(form.id)">
                                <i class=" fas fa-pencil-alt"></i>
                                Delete
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="table invoice">
                <!-- <div class="logo">..</div> -->
                <div class="invoice__header--title">
                    <p style="margin-top:15px;margin-left:15px;">
                        <b style="font-size: 16px;">
                            <label style="font-size: 20px;" @click="onBack()">INDEX</label>
                            <label style="font-size: 16px;"> / </label>
                            <label style="font-size: 20px;" @click="onReload()">From Edit</label>
                        </b>
                    </p>
                    <p class="invoice__header--title-1">
                        <img :src="logoImage" alt="Logo" style="width: 200px;">
                    </p>
                    <p></p>
                </div>
                <div class="invoice__header--item">
                    <div>
                        <div class="font-header-title">FirstName</div>
                        <div class="font-header-content">
                            <select name="" id="" class="input" v-model="form.customer_id">
                                <option :value="form.customer_id">
                                    <p v-if="form.customer">{{ form.customer.firstname }}</p>
                                </option>
                            </select>
                        </div>
                        <div class="font-header-title">Email</div>
                        <div class="font-header-content" v-if="form.customer">
                            <input class="input" type="text" name="email" id="email" v-model="form.customer.email">
                        </div>
                        <div class="font-header-title">Address</div>
                        <div class="font-header-content" v-if="form.customer">
                            <textarea cols="50" rows="3" class="input" name="address" id="address"
                                v-model="form.customer.address">
                            </textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="font-header-title">Invoice</p>
                                <span style="font-size: 16px;;" class="font-content">{{ form.number }}</span>
                            </div>
                            <div class="col-md-6">
                                <p class="font-header-title">Date</p>
                                <span class="font-header-data">
                                    <input class="input" type="date" id="date" v-modal="form.date">
                                </span>
                            </div>
                            <div class="col-md-6">
                                <p class="font-header-title">Due Date</p>
                                <span class="font-header-data">
                                    <input class="input" type="date" id="due_date" v-model="form.due_date"></span>
                            </div>
                            <div class="col-md-6">
                                <p class="font-header-title">Reference</p>
                                <span class="font-header-data">
                                    <textarea cols="50" rows="3" class="input" type="text" id="reference"
                                        v-model="form.reference">
                                    </textarea>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding: 10px 30px !important;">
                    <button class="btn btn-sm btn__open--modal" @click="openModal()">
                        Add New Line
                    </button>
                </div>
                <div class="table">
                    <div class="table--heading3">
                        <p>#</p>
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                    </div>
                    <div class="table--items3" v-for="(item, i) in form.invoice_item" :key="item.id">
                        <p style="color:red; font-size: 20px; font-weight: 900;" @click="onDeleteItem(item.id, i)"> -
                        </p>
                        <p> {{ i + 1 }} : {{ item.product.description }}</p>
                        <p><input style="size: 2px;" type="text" class="input" id="unit_price" name="unit_price"
                                v-model="item.unit_price"></p>
                        <p><input style="size: 2px;" type="text" name="quantity" id="quantity" class="input"
                                v-model="item.quantity"></p>
                        <p>$ {{ item.unit_price * item.quantity }}</p>
                    </div>
                </div>
                <div class="invoice__subtotal">
                    <div>
                        <h4>Thank you for your business</h4>
                    </div>
                    <div>
                        <div class="invoice__subtotal--item1">
                            <label>Sub Total</label>
                            <span>{{ form.sub_total }}</span>
                        </div>
                        <div class="invoice__subtotal--item2">
                            <label>Discount</label>
                            <span><input size="5" type="text" id="discount" name="discount" class="input"
                                    v-model="form.discount"></span>
                        </div>
                    </div>
                </div>
                <div class="invoice__total">
                    <div>
                        <h2>Terms and Conditions</h2>
                        <p><textarea class="input" cols="50" rows="7" name="terms_and_conditions"
                                id="terms_and_conditions" v-model="form.terms_and_conditions">
                        </textarea></p>
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
                <div class="card__header" style="margin-top: 40px;">
                    <div>
                        <button class="button-save" @click="onUpdate(form.id)">
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
.font-header-title {
    font-size: 14px;
    font-weight: bold;
}

.font-header-data {
    margin-top: 10px;
    margin-bottom: 10px;
    font-size: 14px;
    font-weight: 500;
}

.button {
    display: inline-block;
    border-radius: 4px;
    background-color: #f4511e;
    border: none;
    color: #FFFFFF;
    text-align: center;
    font-size: 16px;
    padding: 3px;
    width: 70px;
    transition: all 0.5s;
    cursor: pointer;
    margin: 5px;
}
</style>
