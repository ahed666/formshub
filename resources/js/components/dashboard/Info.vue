<template>
    <div   class="p-2 grid grid-cols-12 gap-2 h-full">
       
         
         <InfoBox v-for="(infoItem,index) in info" class="md:col-span-3 xs:col-span-6 h-full inset-0"  :key="index" :infoItem="infoItem" />

      



    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';

    export default {
        components:{InfoBox,},
        props:{
            info:{
                type:Array
            }
        },
        data(){
            return {
                
                loading:false,
            }
        },
        methods:{
            
        },
        

    }
</script>

<style lang="scss" scoped>

</style>