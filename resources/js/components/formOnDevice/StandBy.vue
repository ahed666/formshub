<template class="">
    <div class="grid justify-center items-center w-full h-full">
       <img :src="standByImagepath" width="100%" height="100%" alt="" srcset="">
    </div>
</template>

<script>
    export default {
        components:{},
        data(){
            return{
                standByImagepath:'/images/deafult/standBy1.png',
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>