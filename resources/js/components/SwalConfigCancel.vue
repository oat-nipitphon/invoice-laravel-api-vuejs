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
  <h2>Style Social Media Buttons</h2>

    <!-- Add font awesome icons -->
    <a href="#" class="fa fa-facebook"></a>
    <a href="#" class="fa fa-twitter"></a>
    <a href="#" class="fa fa-google"></a>
    <a href="#" class="fa fa-linkedin"></a>
    <a href="#" class="fa fa-youtube"></a>
    <a href="#" class="fa fa-instagram"></a>
    <a href="#" class="fa fa-pinterest"></a>
    <a href="#" class="fa fa-snapchat-ghost"></a>
    <a href="#" class="fa fa-skype"></a>
    <a href="#" class="fa fa-android"></a>
    <a href="#" class="fa fa-dribbble"></a>
    <a href="#" class="fa fa-vimeo"></a>
    <a href="#" class="fa fa-tumblr"></a>
    <a href="#" class="fa fa-vine"></a>
    <a href="#" class="fa fa-foursquare"></a>
    <a href="#" class="fa fa-stumbleupon"></a>
    <a href="#" class="fa fa-flickr"></a>
    <a href="#" class="fa fa-yahoo"></a>
    <a href="#" class="fa fa-reddit"></a>
    <a href="#" class="fa fa-rss"></a>
</template>
<link rel="stylesheet" href=""></link>
<style>


.fa {
  padding: 20px;
  font-size: 30px;
  width: 50px;
  text-align: center;
  text-decoration: none;
  margin: 5px 2px;
}

.fa:hover {
    opacity: 0.7;
}

.fa-facebook {
  background: #3B5998;
  color: white;
}

.fa-twitter {
  background: #55ACEE;
  color: white;
}

.fa-google {
  background: #dd4b39;
  color: white;
}

.fa-linkedin {
  background: #007bb5;
  color: white;
}

.fa-youtube {
  background: #bb0000;
  color: white;
}

.fa-instagram {
  background: #125688;
  color: white;
}

.fa-pinterest {
  background: #cb2027;
  color: white;
}

.fa-snapchat-ghost {
  background: #fffc00;
  color: white;
  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
}

.fa-skype {
  background: #00aff0;
  color: white;
}

.fa-android {
  background: #a4c639;
  color: white;
}

.fa-dribbble {
  background: #ea4c89;
  color: white;
}

.fa-vimeo {
  background: #45bbff;
  color: white;
}

.fa-tumblr {
  background: #2c4762;
  color: white;
}

.fa-vine {
  background: #00b489;
  color: white;
}

.fa-foursquare {
  background: #45bbff;
  color: white;
}

.fa-stumbleupon {
  background: #eb4924;
  color: white;
}

.fa-flickr {
  background: #f40083;
  color: white;
}

.fa-yahoo {
  background: #430297;
  color: white;
}

.fa-soundcloud {
  background: #ff5500;
  color: white;
}

.fa-reddit {
  background: #ff5700;
  color: white;
}

.fa-rss {
  background: #ff6600;
  color: white;
}
</style>
