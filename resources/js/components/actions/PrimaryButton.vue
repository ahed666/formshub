<template>
   <button @click="onClick" class="bg-secondary_blue w-auto h-auto p-1 text-white rounded-lg hover:text-gray-500 hover:border-[1px] hover:bg-gray-100 ">
            {{ title }}
   </button>
</template>

<script>
    export default {
        props:{
            title:{
                type:String
            },
        },
        data(){
            return{
                translations: window.translations,
            }
        },
        methods:{
            onClick(){
                this.$emit('onClick');
            }
        },
        
    }
</script>

<style lang="scss" scoped>

</style>