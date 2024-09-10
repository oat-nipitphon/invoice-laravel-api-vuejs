<script setup>
import logoImage from '@/../assets/img/logo.png'
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import TextInput from '../TextInput.vue';

const router = useRouter()
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

const showModal = ref(false)
const hideModal = ref(true)
const openModal = () => {
    showModal.value = !showModal.value
}

const closeModal = () => {
    showModal.value = !hideModal.value
}

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
    removeItemCart()
    SubTotal()
    Discount()
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
        customers.value = response.data.customers
    } catch (error) {
        console.error('Error getCustomers :', error);
    }
}

const getProducts = async () => {
    try {
        let responese = await axios.get('/api/get_products');
        listProducts.value = responese.data.products
    } catch (error) {
        console.error('Error Get Products :: ', error);
    }
}


const SubTotal = () => {
    let total = 0
    if(form.value.invoice_item){
        form.value.invoice_item.map((data) => {
            total += data.quantity * data.unit_price;
        });
    }
    return total;
};

const Discount = () => {
    if (form.value.discount){
        return SubTotal() * form.value.discount / 100;
    }
}

const Total = () => {
    let total = 0
    if(form.value.invoice_item){
        form.value.invoice_item.map((data) => {
            return total =+ SubTotal() - Discount()
        })
    }
    return total;
}

const addCart = (item) => {
    const itemcart = {
        product_id: item.id,
        item_code: item.item_code,
        description: item.description,
        unit_price: item.unit_price,
        quantity: item.quantity,
    }
    form.value.invoice_item.push(itemcart)
    closeModal()
}

const removeItemCart = (id, i) => {

    if(Array.isArray(form.value.invoice_item) && i >= 0 && i < form.value.invoice_item.length){

        if (i != undefined || id != undefined) {
            Swal.fire({
                title: "ยืนยันการลบข้อมูล",
                text: "คุณต้องการที่จะลบข้อมูลนี้ ใช่หรือไม่ ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่ใช่"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "ลบข้อมูลสำเร็จ!",
                        text: "Delete Successfully.",
                        icon: "success"
                    }).then(() => {
                        form.value.invoice_item.splice(i, 1)
                        const respones = axios.get(`/api/delete_invoice_item/cart_item/${id}`);
                        if(respones.status == 200){
                            console.log('Delete Successfully.');
                        }else{
                            console.log('Error Delete : ', respones);
                        }
                    })
                }
            })
        }else{
            console.error(`Error Delete Cart item ID ::${id}:`, form.value.invoice_item);
        }
    }else{
        console.log('Error Form Invoice Item :: ', form.value.invoice_item);
    }

}

const onUpdate = async (id) => {

    if (form.value.invoice_item != null) {

        console.log(JSON.stringify(form.value.invoice_item));

        let subtotal = SubTotal();
        let total = Total();

        // Prepare the data as a JSON object
        const payload = {
            invoice_item: form.value.invoice_item,
            customer_id: form.value.customer_id,
            date: form.value.date,
            due_date: form.value.due_date,
            number: form.value.number,
            reference: form.value.reference,
            discount: form.value.discount,
            subtotal: subtotal,
            total: total
        };

        Swal.fire({
            title: "ยืนยันการบันทึกข้อมูล!",
            text: "คุณต้องการบันทึก ใช่หรือไม่ ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "บันทึกข้อมูลสำเร็จ",
                    text: "คุณต้องการบันทึกใช่หรือไม่.",
                    icon: "success"
                }).then(() => {
                    axios.post(`/api/update_sql_edit_invoice/${id}`, payload, {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    form.value.invoice_item = [];
                    const respones = axios.post("/api/createInvoiceConfig", formData)
                    if(respones.status == 200){
                        router.push('/')
                    }else{
                        console.log('Error Update : ', respones);
                    }
                });
            }
        })
    }else{
        console.log('form.value.invoice_item = Null :: ', form.value.invoice_item);
    }
}

</script>
<template>
    <!--==================== Form EDIT INVOICE ====================-->
    <div class="container">
        <div class="invoices">
            <div class="table invoice">
                <div class="invoice__header--title">
                    <p style="margin-top:20px;margin-left:20px;">
                        <b>
                            <label style="font-size: 20px;" @click="onBack()">INDEX</label>
                            <label style="font-size: 16px;"> / </label>
                            <label style="font-size: 20px;" @click="onReload()">Edit</label>
                            <label style="font-size: 16px;"> #</label>
                            <label style="font-size: 20px;" @click="onShow(form.id)">{{ form.id }}</label>
                        </b>
                    </p>
                    <p class="invoice__header--title-1">
                        <img :src="logoImage" alt="Logo" style="width: 200px;">
                    </p>
                    <p></p>
                </div>
                <div class="invoice__header--item">
                    <div>
                        <p style="margin-top: 10px;">
                            <b>Invoice :: </b>
                            <input type="text" class="input" name="number" id="number" v-model="form.number"  />
                        </p>
                        <p style="margin-top:5px;" >
                            <b>Name :: </b>
                            <select name="firstname" id="firstname" class="input" v-model="form.customer_id">
                                <option disabled></option>
                                <option :value="customer.id" v-for="customer in customers" :key="customer.id">
                                    {{form.customer.firstname}}  {{form.customer.lastname}}
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
                        <p style="margin-top: 10px;">
                            <b>Date</b>
                            <input type="date" class="input" name="date" id="date" v-model="form.date">
                        </p>
                        <p style="margin-top: 10px;">
                            <b>Due Date</b>
                            <input type="date" class="input" name="due_date" id="due_date" v-model="form.due_date">
                        </p>
                        <p style="margin-top: 10px;">
                            <b>Reference</b>
                            <textarea name="reference" id="reference" cols="50" rows="6"
                                v-model="form.reference"></textarea>
                        </p>
                    </div>
                </div>
                <div class="card__header">
                    <div class="col-md-6"></div>
                    <div class="col-md-6 card__end-list">
                        <ul class="card__end-list">
                            <li>
                                <a class="button_addproduct btn_animation" @click="openModal()">
                                    <span>
                                        add product
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="table py1">
                    <div class="table--heading2">
                        <p>ID Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p style="text-align: center;">Total</p>
                        <p style="text-align: center;">#</p>
                    </div>
                    <div class="table--items2" v-for="(itemcart, i) in form.invoice_item" :key="itemcart.id" >
                        <p v-if="itemcart.product">{{ itemcart.product.id }} {{ itemcart.product.description }}</p>
                        <p v-else> {{ itemcart.id }} {{ itemcart.description }}</p>
                        <TextInput
                            type="text"
                            v-model="itemcart.unit_price"
                        />
                        <!-- <p><input type="text" class="input" v-model="itemcart.unit_price"  /></p> -->
                        <p><input type="text" class="input" v-model="itemcart.quantity"></p>
                        <p style="text-align: center; font-weight: bold;">$
                            {{ itemcart.unit_price * itemcart.quantity }}
                        </p>
                        <p style="text-align: center; font-weight: bold; color: red; font-size: 18px;cursor: pointer;"
                        @click="removeItemCart(itemcart.id, i)">
                            &times;
                        </p>
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
                <div class="cart-footer-btn-action">
                    <div style="float: right;">
                        <a class="button_save btn_animation" @click="onUpdate(form.id)">
                            <span>อัพเดท</span>
                        </a>
                        <a class="button_back btn_animation" @click="onBack()">
                            <span>ยกเลิก</span>
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
                            <p>{{ item.id }} {{ i+1 }}</p>
                            <p> {{ item.item_code }} {{ item.description }}</p>
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
