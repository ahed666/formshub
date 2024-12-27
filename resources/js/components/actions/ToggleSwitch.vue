<template>
    <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" :checked="value" @change="toggleSwitch"
            :value="value" class="sr-only peer">
        <div
            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none  
            rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full 
            rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white 
            after:content-[''] after:absolute after:top-[2px] after:start-[2px] 
            after:bg-white after:border-gray-300 after:border after:rounded-full 
            after:h-5 after:w-5 after:transition-all dark:border-gray-600
             peer-checked:bg-blue-600">
        </div>
       
    </label>
  




</template>
<script>



export default {
    components: {

    },
    props: {

     value:{
        type:Boolean,
        default:false,
     },


},
    mounted() {

    },
    beforeDestroy() {

    },
    data() {
        return {
            translations: window.translations,

        };
    },



    methods: {

        toggleSwitch(){

           this.$emit('toggle');
        }






    },




};
</script>
