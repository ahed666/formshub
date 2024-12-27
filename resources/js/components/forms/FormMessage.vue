<template>
    <div  class="list-none mr-1 ml-1 bg-gray-50 p-4 my-2 hover:bg-slate-100 hover:border-2 hover:border-blue-300  rounded-lg  ">
        <div class="flex justify-between">
            <div class="w-1/3 max-w-1/3">
                <span class="mx-[1px] rounded bg-[#faebd7] text-black p-[2px] text-sm xs:text-xs">{{ type }}</span>
            </div>
            <div class="flex justify-between">
                <Edit @edit="editMessage"></Edit>
            </div>
        </div>
        <div class="flex justify-center">
            <h1 class="text-center"> {{ formHeader }}</h1>
        </div>
        <div class="mt-8 xs:mt-2 flex justify-center">
            <h1 class="text-center">{{ formMessage }}</h1>
        </div>


                               
    </div>
  </template>

<style scoped>

</style>
<script>
import Edit from '../actions/Edit.vue';
export default {
    components: {
       Edit,
    },
    props: {
        type:{
            type:String,
            required:true,
        },
       
        formMessage: {
            type: String,
            required: true,
        },
        formHeader:{
               type:String,
               required:true,

        }

    },
    data() {
        return {
            translations: window.translations,


        };
    },
   
    computed: {

        
    },
    methods: {
        
        //emit to open edit message modal
          editMessage(){
            this.$emit('editMessage');
          }


     

       
       




    },




};
</script>
