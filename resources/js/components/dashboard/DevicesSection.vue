<template>
    <!-- <div class="flex flex-wrap space-x-2 p-2 w-full">
    <div v-for="(device,index) in devices" class="
      xs:w-full sm:w-1/2 md:w-1/3 
      border-secondary_blue border-[1px] p-3 rounded-[0.5rem] bg-white">
        <DeviceCard :viewOptions="false" :device="device"  />
    </div>
</div> -->
<swiper :watchSlidesProgress="true" 
    :slidesPerView="1"
   :pagination="pagination"
      :spaceBetween="10"
      :modules="modules"
      :breakpoints="{
        500: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }"
      class="mySwiper px-4">
    <swiper-slide class="h-auto border-secondary_blue border-[1px] p-3 rounded-[0.5rem] bg-white" 
    v-for="(device,index) in devices" :key="index">
        <DeviceCard  :viewOptions="false" :device="device" class="h-auto" /></swiper-slide>
    
  </swiper>
    

</template>

<script>
import DeviceCard from '../devices/DeviceCard.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Navigation } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

    export default {
        components:{DeviceCard,Swiper,SwiperSlide,},
        props:{
            devices:{
                type:Array,
            },
        },
        data(){
          return {
            translations: window.translations,

          }
        },
        setup() {
            return {
        pagination: {
          clickable: true,
          
        },
        modules: [Pagination],
      };
        },
        mounted() {
  },
        
    }
</script>

<style  scoped>
.mySwiper {
  width: 100%;

  padding-bottom: 40px; /* Space for pagination */
}

/* Ensure pagination is properly positioned */
.mySwiper .swiper-pagination {
  bottom: 10px; /* Adjust as needed */
}



/* Optional: Additional styling for Swiper container */
.swiper-container {
  padding-bottom: 40px; /* Ensure space for pagination */
}


</style>