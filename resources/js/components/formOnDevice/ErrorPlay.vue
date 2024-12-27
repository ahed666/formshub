<template>
    <div class="flex justify-center items-center ">
         {{ message }}
    </div>
</template>

<script>
    export default {

        props:{
            message:{
                type:String,
                Required:true,
            },
        },
        
    }
</script>

<style lang="scss" scoped>

</style>