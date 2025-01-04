<template>
    <div class="grid justify-center items-center">
       <img :src="standByImagepath" width="100%" height="100%" alt="" srcset="">
    </div>
</template>

<script>
    export default {
        components:{},
        data(){
            return{
                standByImagepath:'/images/deafult/standBy.gif',
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>