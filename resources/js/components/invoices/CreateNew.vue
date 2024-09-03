<script setup>

import { onMounted, ref } from 'vue'
import axios from 'axios'

let form = ref([])
let customers = ref([])
let customer_id = ref([])
let item = ref([])
let listCart = ref([])


onMounted(async () => {
    getCustomers()
    indexForm()
})

const getCustomers = async () => {
    try {
        let response = await axios.get('/api/get_all_customer');
        console.log('Customer respones', response);
        customers.value = response.data.customers
    } catch (error) {
        console.error('Error getCustomers :', error);
    }
}

const indexForm = async () => {
    try {
        let formData = await axios.get('/api/create_invoice');
        console.log('Form Create Invoice :', formData.data);
        form.value = formData.data
    } catch (error) {
        console.error('Error indexForm :', error);
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

}

addCart(
    {
        id: 1,
        item_code: 'A001',
        description: 'Product A',
        unit_price: 10000,
        quantity: 1
    }
    // {
    //     id: 2,
    //     item_code: 'A002',
    //     description: 'Product B',
    //     unit_price: 20000,
    //     quantity: 2
    // },
    // {
    //     id: 3,
    //     item_code: 'A003',
    //     description: 'Product ',
    //     unit_price: 30000,
    //     quantity: 3
    // },
)

</script>
<template>

    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div class="col-md-6">
                    <h2 class="invoice__title">
                        Insert Data Invoice New
                    </h2>
                </div>
                <div class="col-md-6">
                    <h2 class="invoice__title">
                        <router-link to="/">Back Index</router-link>
                    </h2>
                </div>
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
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" value="form.date" v-model="form.date">
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
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>

                    <!-- item 1 -->
                    <div class="row">
                        <h3>item 1</h3>
                    </div>
                    <div class="table--items2" v-for="(itemcart) in listCart" :key="itemcart.id">
                        <p> # {{ itemcart.item_code }} {{ itemcart.description }}</p>
                        <p>
                            <input type="text" class="input">
                        </p>
                        <p>
                            <input type="text" class="input">
                        </p>
                        <p>
                            $ 10000
                        </p>
                        <p style="color: red; font-size: 24px;cursor: pointer;">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal">Add New Line</button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer">
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ 1000</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ 1200</span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>

                </div>
                <div>
                    <a class="btn btn-secondary">
                        Save
                    </a>
                </div>
            </div>

        </div>

    </div>
</template>
