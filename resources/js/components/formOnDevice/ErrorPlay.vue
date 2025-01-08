<template>
    <div class="grid justify-center items-center max-h-screen h-screen ">
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