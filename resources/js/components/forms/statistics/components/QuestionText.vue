<template>
    <div class="flex justify-start items-center text-2xl font-bold">
      {{ questionText }}
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