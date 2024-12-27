<template>
 
    <div v-if="userCanAdd">
       
      <AddFormModal 
      v-if="typeModal==='addform'"
        @close="closeModal" >
      </AddFormModal>

      <AddQuestionModal 
        v-else-if="typeModal==='addquestion'" 
        :form="form" 
        @close="closeModal" 
        @saved="addQuestionToQuestionList" 
      />
      <EditQuestionModal 
      v-else-if="typeModal==='editquestion'" 
        :form="form"
        :question="selectedQuestionToEdit"
        
        :currentLang="currentLang" 
        @close="closeModal" 
        @saved="editQuestion" 
      />
      <EditFormMessageModal 
      v-else-if="typeModal==='editformmessagestart'||typeModal=='editformmessageend'" 
      
        :formTranslation="formTranslations[currentLang]"
        :type="typeModal"
        @close="closeModal" 
        @saved="editFormTranslationMessage" 
      />

      <EditDeviceModal 
      v-else-if="typeModal==='editdevice'" 
      :device="device"
      @close="closeModal"
       @save="saveEditedDevice"
      
      />
      <AddDeviceModal
      v-else-if="typeModal==='adddevice'"
      @close="closeModal" 
     @saved="addDeviceToList"
      />
    </div>
    <div v-else>
       
      <ErrorModal :errorMessage="errorMessage" @close="closeModal" />

    </div>
  </template>
  
  <script>
  import AddFormModal from "../modals/AddFormModal.vue";

 import AddQuestionModal from './AddQuestionModal.vue';
 import EditQuestionModal from './EditQuestionModal.vue';
 import EditFormMessageModal from './EditFormMessageModal.vue';
 import EditDeviceModal from './EditDeviceModal.vue';
import AddDeviceModal from './AddDeviceModal.vue';
import ErrorModal from './ErrorModal.vue';
  
  export default {
    components: {
      AddQuestionModal,
      EditQuestionModal,
      EditFormMessageModal,
      EditDeviceModal,
      AddDeviceModal,
      AddFormModal,
      ErrorModal,
    },
    props:{
      typeModal:{
        type:String,
      },
      form:{
        type:Object,
      },
      formTranslations:{
        type:Object,
      },
      selectedQuestionToEdit:{
        type:Object,
      },
      currentLang:{
        type:String,
      },
      device:{
        type:Object,
      },
      userCanAdd:{
        type:Boolean,
        required:false,
        default:true,
      },
      errorMessage:{
        type:String,
        required:false,
      },


    },
    // props: ['typeModal','form','formTranslations','selectedQuestionToEdit','currentLang','device'],
    methods: {
      closeModal() {
        console.log('close modal:',this.typeModal);
        this.$emit('close');
      },
      addQuestionToQuestionList(newQuestionText, questionOptional, questionViewAnswersMode, answers, type) {
        this.$emit('saved',{  newQuestionText, questionOptional, questionViewAnswersMode, answers, type });
        this.closeModal();
      },
      editQuestion(updatedQuestion) {
        this.$emit('saved', {  question: updatedQuestion });
        this.closeModal();
      },
      editFormTranslationMessage(formTranslation) {
        this.$emit('saved',{formTranslation:formTranslation});
        this.closeModal();
      },

      saveEditedDevice(updatedDevice){
        this.$emit('saved',updatedDevice);
        this.closeModal();

      },
      addDeviceToList(newDevice){
        this.$emit('saved',newDevice);
        this.closeModal();
      }
    },
  };
  </script>
  