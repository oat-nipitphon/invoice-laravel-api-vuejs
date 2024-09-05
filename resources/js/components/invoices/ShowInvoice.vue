<script setup>

    import { onMounted, ref } from 'vue'
    import logoImage from '@/../assets/img/logo.png'
    import router from '../../router/index.js'


    let form = ref({
        id: ''

    })

    const props = defineProps({
        id: {
            type: String,
            default: ''
        }
    })

    onMounted(async () => {
        showGetInvoiceData()
    })

    const showGetInvoiceData = async () => {
        let response = await axios.get(`/api/show_get_invoice_data/${props.id}`);
        console.log('Get Invoice Data :: ', response.data.invoice);
        form.value = response.data.invoice
    }

    const onDelete = async (id) => {
        await axios.delete(`/api/delete_invoice/${id}`);
        router.push('/');
    }

    const onFormEditInvoice = (id_invoice) => {
        router.push('/invoice/formedit/' + id_invoice);
    }




</script>
<template>
    <div class="contriner">
        <!--==================== SHOW INVOICE ====================-->
        <div class="invoices">

            <div class="card__header" style="height: 80px;">
                <div style="margin-top: 50px;margin-left:30px;">
                    <label style="font-size: 34px;">Invoice </label>
                    <label style="font-size: 20px;">#{{ form.id }}</label>
                    <div class="card__header--title " style="margin-top:15px;">
                        <h1 class="mr-2">Date create</h1>
                        <p>{{ form.created_at }}</p>
                    </div>
                </div>
                <div style="margin-right: 30px; margin-top:10px;">
                    <ul class="card__header-list">
                        <li>
                            <button class="button selectBtnFlat btn btn-secondary">
                                <i class=" fas fa-save"></i>
                                Save
                            </button>
                        </li>
                        <li>
                            <!-- Select Btn Option -->
                            <button class="button selectBtnFlat" @click="onFormEditInvoice(form.id)">
                                <i class=" fas fa-reply"></i>
                                Edit
                            </button>
                            <!-- End Select Btn Option -->
                        </li>
                        <li>
                            <!-- Select Btn Option -->
                            <button class="button selectBtnFlat " @click="onDelete(form.id)">
                                <i class=" fas fa-pencil-alt"></i>
                                Delete
                            </button>
                            <!-- End Select Btn Option -->
                        </li>
                        <li>
                            <!-- Select Btn Option -->
                            <button class="button selectBtnFlat">
                                <i class="fas fa-print"></i>
                                Print
                            </button>
                            <!-- End Select Btn Option -->
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table invoice">
                <!-- <div class="logo">

                </div> -->
                <div class="invoice__header--title">
                    <p></p>
                    <p class="invoice__header--title-1">
                        <img :src="logoImage" alt="Logo" style="width: 200px;">
                    </p>
                    <p></p>
                </div>
                <div class="invoice__header--item">
                    <div>
                        <h1><b style="font-size:24px;">Invoice Detail</b></h1>
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
                        <div style="font-size:18px;" class="invoice__header--item1">
                            <p style="">Invoice</p>
                            <span>{{ form.number }}</span>
                        </div>
                        <div style="font-size:18px;" class="invoice__header--item2">
                            <p style="">Date</p>
                            <span>{{ form.date }}</span>
                        </div>
                        <div style="font-size:18px;" class="invoice__header--item2">
                            <p style="">Due Date</p>
                            <span>{{ form.due_date }}</span>
                        </div>
                        <div style="font-size:18px;" class="invoice__header--item2">
                            <p style="">Reference</p>
                            <span>{{ form.reference }}</span>
                        </div>
                    </div>
                </div>
                <!-- listCart items -->
                <div class="table py1">

                    <!-- item 1 itemCart-->
                    <div class="table--heading3">
                        <p>#</p>
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                    </div>

                    <!-- item 2 itemCart-->
                    <div class="table--items3">
                        <p>1</p>
                        <p>Lorem Ipsum is simply dummy text</p>
                        <p>$ 300</p>
                        <p>1</p>
                        <p>$ 300</p>
                    </div>
                    <div class="table--items3">
                        <p class="table--items--col2">
                            2
                        </p>
                        <p class="table--items--col1 table--items--transactionId3">
                            Lorem Ipsum is simply dummy text
                        </p>
                        <p class="table--items--col2">
                            $ 300
                        </p>
                        <p class="table--items--col3">
                            1
                        </p>
                        <p class="table--items--col5">
                            $ 300
                        </p>
                    </div>
                    <div class="table--items3">
                        <p class="table--items--col2">
                            3
                        </p>
                        <p class="table--items--col1 table--items--transactionId3">
                            Lorem Ipsum is simply dummy text
                        </p>
                        <p class="table--items--col2">
                            $ 300
                        </p>
                        <p class="table--items--col3">
                            1
                        </p>
                        <p class="table--items--col5">
                            $ 300
                        </p>
                    </div>
                    <div class="table--items3">
                        <p class="table--items--col2">
                            4
                        </p>
                        <p class="table--items--col1 table--items--transactionId3">
                            Lorem Ipsum is simply dummy text
                        </p>
                        <p class="table--items--col2">
                            $ 300
                        </p>
                        <p class="table--items--col3">
                            1
                        </p>
                        <p class="table--items--col5">
                            $ 300
                        </p>
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
<style>
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
