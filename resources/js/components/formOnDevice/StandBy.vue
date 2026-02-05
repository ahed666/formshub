<template class="">
    <div class="grid justify-center items-center max-h-[100vh] max-w-[100vw] min-h-[100vh] rotate-0">
       <img :src="standByImagepath" class="h-48 w-54" alt="" srcset="">
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