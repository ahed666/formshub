<template>
    <div  class="mt-4 col-span-12 flex justify-center items-center">
      <span v-html="error" class="text-secondary_red"> 
        
      </span>
    </div>
</template>

<script>


    export default {
        props:{
            error:{
                type:String,
                Required:true,
            },
        },
        data(){
            return{
                translations: window.translations,

            }
        },
    }
</script>

<style lang="scss" scoped>

</style>