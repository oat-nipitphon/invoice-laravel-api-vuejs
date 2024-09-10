<script setup>
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const router = useRouter();

function onSweetalert() {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "Cancel"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Deleted!",
        text: "Your file has been deleted.",
        icon: "success"
      }).then(() => {
        console.log('router push');
        // router.push('/'); // นำทางไปยังหน้าแรก
      });
      console.log('api delete');
    }
    console.log('else');
  });
}
</script>

<template>
  <a class="button btn-danger" @click="onSweetalert">
    <span>ยกเลิก</span>
  </a>
</template>
