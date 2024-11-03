<script setup>
import DataTable from "datatables.net-vue3";
import Swal from "sweetalert2";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

let invoices = ref([]);
let searchInvoice = ref("");
let timeout = ref(null);

onMounted(async () => {
    getInvoices();
});

const getInvoices = async () => {
    try {
        let response = await axios.get("/api/get_invoices");
        console.log("response", response);
        invoices.value = response.data.invoices;
    } catch (error) {
        console.error("Error fetching invoices:", error);
    }
};

const router = useRoute();

const onShow = (id) => {
    router.push("/invoice/show/" + id);
};

const onEdit = (id) => {
    router.push(`/invoice/edit/${id}`);
};

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
                axios.delete(`/api/delete_invoice/${id}`);
                location.reload();
            });
        }
    });
};

function javaScript(){
    console.log('Hello Java Script');
}
javaScript()



const search = async () => {
    if (searchInvoice.value.trim() !== "") {
        try {
            let response = await axios.get(
                "/api/search_invoices?id=" + searchInvoice.value
            );
            console.log("response", response.data.invoices);
            invoices.value = response.data.invoices;
        } catch (error) {
            console.error("Error fetching search invoices", error);
        }
    } else {
        getInvoices();
    }
};

const debouncedSearch = () => {
    if (timeout.value)
        timeout.value = setTimeout(() => {
            search();
        }, 300);
};

</script>
<template>
    <h1>Hello World!</h1>
</template>
