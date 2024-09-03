<script setup>
import { onMounted, ref } from 'vue'
const items = ref([
    {
        id: 1,
        message: 'Foo',
    },
    {
        id: 2,
        message: 'Bar',
    }
])


// Document v-for loop forEach
// const parentMessage = 'v-for loop forEach'
// const items = [
// {
//         id: 1,
//         item_code: 'A001',
//         description: 'Product A',
//         unit_price: 10000,
//         message: 'Loop 1'
//     },
//     {
//         id: 2,
//         item_code: 'A002',
//         description: 'Product B',
//         unit_price: 20000,
//         quantity: 2,
//         message: 'Loop 2'
//     },
//     {
//         id: 3,
//         item_code: 'A003',
//         description: 'Product ',
//         unit_price: 30000,
//         quantity: 3,
//         message: 'Loop 3'

//     },
// ]
// items.forEach((item, index) => {
//   // has access to outer scope `parentMessage`
//   // but `item` and `index` are only available in here
//   console.log('Loop ForEach ::',parentMessage, item.message, index)
// })


</script>
<template>
    <div class="container text-center">
        <div class="row">
            <h1>Vue v-for Directive</h1>
        </div>
        <div class="row">

            <li v-for="item in items" :key="item.id">
                {{ item.message }}
            </li>

            <span v-for="n in 10" :key="n">{{ n++ }}</span>

        </div>
    </div>
</template>
