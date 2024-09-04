<script setup>

    import { onMounted, ref } from 'vue'
    import router from '../../router/index.js';

    let invoices = ref([])
    let searchInvoice = ref("")
    let timeout = ref(null)

    onMounted(async () => {
        getInvoices()
    })


    const getInvoices = async () => {
        try {
            let response = await axios.get("/api/get_all_invoice")
            console.log('response', response)
            invoices.value = response.data.invoices
        } catch (error) {
            console.error('Error fetching invoices:', error);
        }
    }

    const search = async () => {
        if (searchInvoice.value.trim() !== "") {
            try {
                let response = await axios.get("/api/search_invoice?id=" + searchInvoice.value);
                console.log('response', response.data.invoices);
                invoices.value = response.data.invoices
            } catch (error) {
                console.error('Error fetching search invoices', error);
            }
        } else {
            getInvoices();
        }
    }

    const debouncedSearch = () => {
        if (timeout.value);
        timeout.value = setTimeout(() => {
            search();
        }, 300);
    }

    const onShow = (id) => {
        router.push('/invoice/show/'+id);
    }

</script>
<template>
    <div class="container">
        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Invoices</h2>
                </div>
                <div>
                    <a class="btn btn-secondary">
                        <!-- New Invoice -->
                        <router-link to="/invoice/new">New Invoice</router-link>
                    </a>
                </div>
            </div>

            <div class="table card__content">
                <div class="table--filter">
                    <span class="table--filter--collapseBtn ">
                        <i class="fas fa-ellipsis-h"></i>
                    </span>
                    <div class="table--filter--listWrapper">
                        <ul class="table--filter--list">
                            <li>
                                <p class="table--filter--link table--filter--link--active">
                                    All
                                </p>
                            </li>
                            <li>
                                <p class="table--filter--link ">
                                    Paid
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table--search">
                    <div class="table--search--wrapper">
                        <select class="table--search--select" name="" id="">
                            <option value="">Filter</option>
                        </select>
                    </div>
                    <div class="relative">
                        <i class="table--search--input--icon fas fa-search "></i>
                        <input class="table--search--input" type="text" placeholder="Search invoice"
                            v-model="searchInvoice" @keyup="debouncedSearch()">
                    </div>
                </div>

                <div class="table--heading">
                    <p>ID</p>
                    <p>Date</p>
                    <p>Number</p>
                    <p>Customer</p>
                    <p>Due Date</p>
                    <p>Total</p>
                </div>

                <!-- item 1 -->
                <!-- <div v-if="invoices.length > 0"> -->
                <div class="table--items" v-for="item in invoices" :key="item.id">
                    <!-- <a href="#" class="table--items--transactionId">#{{ item.id }}</a> -->
                    <a href="#" @click="onShow(item.id)">#{{ item.id }}</a>
                    <p>{{ item.date }}</p>
                    <p>{{ item.number }}</p>
                    <p v-if="item.customer">
                        {{ item.customer.firstname }}
                    </p>
                    <p v-else class="text-center">
                        <b>-</b>
                    </p>
                    <p>{{ item.due_date }}</p>
                    <p> $ {{ item.total }}</p>
                </div>
                <!-- </div> -->
                <!-- <div v-else>
                    <p>Invoice not found</p>
                </div> -->
            </div>

        </div>
    </div>
</template>
