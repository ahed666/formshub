<template>
       
        <ModalTemplate :step="2" :disableButtons="checkDisableSaveButton()"
     :showButtons="true" :isSaving="isSaving"  @submit="saveFormTranslation" 
     @close="closeModal"  title="Edit Form Messages">
       
            <div class="col-span-12">
                
                <div class="w-full ">
                    <div class="flex justify-between items-center">
                        <span class="text-black text-sm xs:ml-2 xs:mr-2 w-20 " for="header_message">
                            <Tooltip :title="'Here you may add the header of the welcome page, for a better looking it should not be long, you can always use the Preview option to see how does it look in the actual form.'"></Tooltip>
                            {{             translations.titles.header_message_title }}
                        </span>
                        
                    </div>
                    <input @change="editingHeader($event)" :value="header" 
                     maxlength="100" type="text" id="header_message" name="header_message" class=" w-full  mr-2  h-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                    focus:border-blue-500 block
                    text-center px-2" required="">
                    
                </div>
                
                <div class="w-full mt-2 min-h-">
                    <div class="flex justify-between items-center">
                        <span class="text-black text-sm  xs:ml-2 xs:mr-2 w-20 whitespace-nowrap " for="text_message">
                            <Tooltip :title="'Here you can add more text to view on the welcome page, such as instructions for this form, It is advisable to keep the message concise and coherent.'"></Tooltip>
                            {{translations.titles.message_title }}
                        </span>
                        
                    </div>
                    <textarea @change="editingMessage($event)" :value="message" maxlength="200" cols="100" rows="2" type="text" id="text_message" name="text_message" class=" w-full mr-2  h-20 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                    focus:border-blue-500 block    text-center px-2" required=""></textarea>
                    
                </div>

            </div>


       
        </ModalTemplate>

</template>

<style scoped>

</style>
<script>
import ModalTemplate from '../ModalTemplate.vue';
import Tooltip from '../alerts/Tooltip.vue';
export default {
    components: {
        ModalTemplate,
        Tooltip,
    },
    props: {
        formTranslation:{
            type:Object,
            required:true,
        },
        type:{
            type:String,
            required:true,
        },
        
       
       
    },
    data() {
        return {
            isSaving:false,
            header:null,
            message:null,
            translations:window.translations,

        };
    },
    mounted(){
          this.initialData();
    },
   
    computed: {

        
    },
    methods: {

        initialData(){
         
         this.header=this.type=='editformmessagestart'?this.formTranslation.start_header:this.formTranslation.end_header;
          this.message=this.type=='editformmessagestart'?this.formTranslation.start_message:this.formTranslation.end_message;
        },

        // check if the button is disable or not dependent on if inputs or message and header is empty or not
        checkDisableSaveButton(){
             return !this.header||!this.message ;
        },

        editingHeader(event){
           this.header=event.target.value;
        },

        editingMessage(event){
           this.message=event.target.value;
        },

        

        // when click on save button to save the form message translation
        saveFormTranslation(){
            this.isSaving=true;
            this.type=='editformmessagestart'?this.formTranslation.start_header=this.header:this.formTranslation.end_header=this.header;
           this.type=='editformmessagestart'?this.formTranslation.start_message=this.message:this.formTranslation.end_message=this.message;

            this.$emit('saved',this.formTranslation);
            this.$emit('close');
            this.isSaving=false;
            this.header=this.message=null;
           
        },
        
        // close modal
        closeModal() {
             

                    this.$emit('close');
            },

     

       
       




    },




};
</script>
