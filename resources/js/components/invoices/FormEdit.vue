<script setup>
    import { onMounted, ref } from 'vue'

    let form = ref([])

    const props = defineProps({
        id_invoice: {
            type: String,
            default: ''
        }
    })

    onMounted(async () => {
        getDataFormEditInvoice()

    })

    const getDataFormEditInvoice = async () => {
        let response = await axios.get(`/api/form_edit_invoice_data/${props.id_invoice}`);
        console.log('Get Data Form Edit Invoice :: ', response.data.invoice.date);
        form.value = response.data.invoice

    }

</script>
<template>
    <div class="container">
        <!--==================== EDIT INVOICE ====================-->
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Edit Invoice</h2>
                </div>
                <div>

                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <!-- <option disabled>Select Customer</option>
                            <option :value="customer.id" v-for="customer in customers" :key="customer.id">
                                {{ customer.firstname }}
                            </option> -->
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="form.customer_id">
                            <option :value="form.customer_id">
                                <p v-if="form.customer">{{ form.customer.firstname }}</p>
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
                        <p class="my-1">Numero</p>
                        <input type="text" class="input">
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
                    <div class="table--items2">
                        <p>#093654 vjxhchkvhxc vkxckvjkxc jkvjxckvjkx </p>
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
                            <span>$ {{ form.sub_total }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="form.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{ form.total }}</span>
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
