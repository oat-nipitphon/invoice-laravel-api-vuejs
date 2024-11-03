<script setup>
import { useRouter } from "vue-router";
import TableReportInvoice from "../components/TableReportInvoice.vue";
import TableDataReport from "../components/TableDataReport.vue";

const router = useRouter();

const onNew = () => {
    router.push("/invoice/new");
};
</script>
<template>
    <div class="container">
        <div class="invoices">
            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Invoices</h2>
                </div>
            </div>
            <div class="row">
                <div class="table--filter">
                    <div class="card__header">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <ul class="card__header-list list-unstyled">
                                    <li>
                                        <a
                                            class="btn btn-primary btn_animation"
                                            @click="onNew()"
                                        >
                                            <span> New </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <TableReportInvoice />
            </div>
        </div>
    </div>
</template>
