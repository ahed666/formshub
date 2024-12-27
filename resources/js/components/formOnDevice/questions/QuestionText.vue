<template>
    <div class="text-center  px-3 w-full  flex justify-center items-center  ">
        <span class="text-3xl font-bold pointer-events-none select-none ">
            {{ questionText }}
        </span> 
    </div>
</template>

<script>
    export default {
        props:{
            questionText:{
                type:String,

            },
        },
    }
</script>

<style lang="scss" scoped>

</style>