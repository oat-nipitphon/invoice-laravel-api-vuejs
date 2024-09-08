<script setup>

import logoImage from '@/../assets/img/logo.png'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const onBack = () => {
    router.push('/');
}
const onReload = () => {
    location.reload();
}
const onPrint = () => {
    window.print()
    // router.push('/').catch(() => {})
}

let form = ref([])
let listProducts = ref([])

const props = defineProps({
    id: {
        type: String,
        default: ''
    }
})

onMounted(async () => {
    showGetInvoice()
    getProduct()
})

const getProduct = async () => {
    try {
        let responese = await axios.get('/api/getProduct');
        // console.log('Get Products :: ', responese);
        listProducts.value = responese.data.products
    } catch (error) {
        console.error('Error Get Products :: ', error);
    }
}

const showGetInvoice = async () => {
    try {
        let response = await axios.get(`/api/show_get_invoice/${props.id}`);
        // console.log('Get Invoice :: ', response.data.invoice);
        form.value = response.data.invoice
    } catch (error) {
        console.log('error get product all :', from);
    }
}

</script>
<template>
    <div class="container">
        <!--==================== SHOW INVOICE ====================-->
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
                            <a class="btn btn-sm button" @click="onPrint()">
                                <span>
                                    Print
                                </span>
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-sm button " @click="onBack()">
                                <span>
                                    Back
                                </span>
                            </a>
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
                            <label style="font-size: 20px;" @click="onReload()">Show Detail</label>
                        </b>
                    </p>
                    <p class="invoice__header--title-1">
                        <img :src="logoImage" alt="Logo" style="width: 200px;">
                    </p>
                    <p></p>
                </div>
                <div class="invoice__header--item">
                    <div>
                        <p style="margin-top:10px;" v-if="form.customer">
                            <b>Name :: </b>{{ form.customer.firstname }}
                        </p>
                        <p style="margin-top:10px;" v-if="form.customer">
                            <b>Email :: </b>{{ form.customer.email }}
                        </p>
                        <p style="margin-top:10px;" v-if="form.customer">
                            <b>Address :: </b>{{ form.customer.address }}
                        </p>
                    </div>
                    <div>
                        <div class="invoice__header--item1">
                            <p style="font-size: 16px; font-weight: bold;" class="font-title">Invoice</p>
                            <span style="font-size: 16px;;" class="font-content">{{ form.number }}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p style="font-size: 16px; font-weight: bold;" class="font-title">Date</p>
                            <span style="font-size: 16px;;" class="font-content">{{ form.date }}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p style="font-size: 16px; font-weight: bold;" class="font-title">Due Date</p>
                            <span style="font-size: 16px;;" class="font-content">{{ form.due_date }}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p style="font-size: 16px; font-weight: bold;" class="font-title">Reference</p>
                            <span style="font-size: 16px;;" class="font-content">{{ form.reference }}</span>
                        </div>
                    </div>
                </div>
                <div class="table py1">
                    <div class="table--heading3">
                        <p>#</p>
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                    </div>
                    <div class="table--items3" v-for="(item, i) in form.invoice_item" :key="item.id">
                        <p>{{ i + 1 }}</p>
                        <p>{{ item.product.description }}</p>
                        <p>$ {{ item.unit_price }}</p>
                        <p>{{ item.quantity }}</p>
                        <p>$ {{ item.unit_price * item.quantity }}</p>
                    </div>
                </div>
                <div class="invoice__subtotal">
                    <div>
                        <h2>Thank you for your business</h2>
                    </div>
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
                </div>
                <div class="invoice__total">
                    <div>
                        <h2>Terms and Conditions</h2>
                        <p>{{ form.terms_and_conditions }}</p>
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
            </div>
        </div>
    </div>
</template>
