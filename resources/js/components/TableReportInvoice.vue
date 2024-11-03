<script setup>
import Swal from "sweetalert2"
import { onMounted, ref } from "vue"
import { useRoute } from 'vue-router'

const router = useRoute()

const onShow = (id) => {
    router.push("/invoice/show/" + id)
}

const onNew = () => {
    router.push("/invoice/new")
}

const onEdit = (id) => {
    router.push(`/invoice/edit/${id}`)
}

const onDelete = async (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Deleted!",
                text: "Your file has been deleted.",
                icon: "success",
            }).then(() => {
                axios.delete(`/api/delete_invoice/${id}`)
                location.reload()
            })
        }
    })
}

let invoices = ref([])
let searchInvoice = ref("")
let timeout = ref(null)

onMounted(async () => {
    getInvoices()
})

const getInvoices = async () => {
    try {
        let response = await axios.get("/api/get_invoices")
        console.log("response", response)
        invoices.value = response.data.invoices
    } catch (error) {
        console.error("Error fetching invoices:", error)
    }
}

const search = async () => {
    if (searchInvoice.value.trim() !== "") {
        try {
            let response = await axios.get(
                "/api/search_invoices?id=" + searchInvoice.value
            )
            console.log("response", response.data.invoices)
            invoices.value = response.data.invoices
        } catch (error) {
            console.error("Error fetching search invoices", error)
        }
    } else {
        getInvoices()
    }
}

const debouncedSearch = () => {
    if (timeout.value)
    timeout.value = setTimeout(() => {
        search()
    }, 300)
}
</script>
<template>
    <div class="container">
        <!-- Table Report Invoice Start -->
        <div class="table card__content">

            <div class="table--search">
                <div class="table--search--wrapper">
                    <select class="table--search--select" name="" id="">
                        <option value="">Filter</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="table--search--input--icon fas fa-search"></i>
                    <input
                        class="table--search--input"
                        type="text"
                        placeholder="Search invoice"
                        v-model="searchInvoice"
                        @keyup="debouncedSearch()"
                    />
                </div>
            </div>

            <div class="table--heading">
                <p>ID</p>
                <p>Date</p>
                <p>Number</p>
                <p>Customer</p>
                <p>Due Date</p>
                <p>Total</p>
                <p>#</p>
            </div>
            <div
                class="table--items"
                v-for="(item, i) in invoices"
                :key="item.id"
            >
                <p>{{ i + 1 }}</p>
                <p>{{ item.date }}</p>
                <p>{{ item.number }}</p>
                <p v-if="item.customer">
                    {{ item.customer.firstname }}
                </p>
                <p v-else class="text-center">
                    <b>-</b>
                </p>
                <p>{{ item.due_date }}</p>
                <p>$ {{ item.total }}</p>
                <div class="dropdown">
                    <button class="dropbtn">Action</button>
                    <div class="dropdown-content">
                        <a @click="onShow(item.id)" class="">
                            <span>Show</span>
                        </a>
                        <a @click="onEdit(item.id)" class="">
                            <span>Edit</span>
                        </a>
                        <a @click="onDelete(item.id)" class="">
                            <span>Delete</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table Report Invoice Stop -->
    </div>
</template>
