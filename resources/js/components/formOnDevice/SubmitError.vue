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


        mounted(){
            setTimeout(() => {
            window.location.reload(); // Refresh the page
        }, 30000); // 30 Seconds
        }
        
    }
</script>

<style lang="scss" scoped>

</style>