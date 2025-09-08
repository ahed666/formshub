<!-- Modal.vue -->
<template>
    <modal-template  :disableButtons="checkDisableSaveButton()"
     :showButtons="true" :isSaving="isSaving"  @submit="saveQuestion" 
     @close="closeModal" :extraInfo="true" 
     :info="{'questionOptional':currentQuestion?currentQuestion.optional:true,'questionOptionalComp':true,
     questionViewAnswersModeValue:currentQuestion.answers_view_mode,'questionViewAnswersMode':multiAnswersQuestion()}" 
     @toggleQuestionStatus="toggleQuestionStatus" @view-mode-changed="onSelectionViewModeChange"
        :allowSelect="allowSelect" :errorMessage="errorMessage" 
         :title="translations.forms.edit_question_modal_title">
        <div class="col-span-12 p-2 max-h-[60vh] overflow-y-auto ">

            <!-- Show loading spinner when question is being fetched -->
            <Loader v-if="isLoading"></Loader>

          



            <!-- step 2  -->
             
             <div >
               
    <QuestionTemplate @updateQuestionText="updateQuestionText" 
                 @updateAnswers="updateAnswers" :question="currentQuestion" :language="currentLang"
                 :selectedQuestionType="currentQuestion.QuestionType"></QuestionTemplate>
             </div>
            





        </div>






    </modal-template>

</template>

<script>
import ModalTemplate from '../ModalTemplate.vue';
import Loader from '../svgs/Loader.vue';
import QuestionType from '../forms/QuestionType.vue';
import QuestionTemplate from '../questions/QuestionTemplate.vue';
export default {
    name: 'EditQuestionModal',
    components: {
        ModalTemplate,
        Loader,
        QuestionType,
        QuestionTemplate,
    },
    props: {
        
        form: {
            type: Object,
            required: true,
        },
        question:{
            type:Object,
            required:true,

        },
        currentLang:{
            type:String,
            required:false,
        },


    },
    mounted(){
    },
    data() {
        return {
           
           
           
            allowSelect: true,
            isLoading: false,
            isSaving: false,
            errorMessage:null,
          
         
            currentQuestion:null,
            translations:window.translations,
          

        };

    },
    watch: {
        question: {
            immediate: true, // Trigger immediately
            handler(newVal) {
                this.currentQuestion = newVal; // Set currentQuestion when question prop changes
            },
        },
       
    },
    
    mounted() {
        
    },
    methods: {

        // on any update on answers
        updateAnswers(answers){
             this.currentQuestion.answers=answers;
        },

        // on any update on question text
        updateQuestionText(question_text){
            this.currentQuestion.questionText[this.currentLang]=question_text;
        },

        

        // check if question and answers is not empty
        questionAndAnswersFilled() 
        {
            
            const isEmpty = str => !str || str.trim() === '';

            // Check if question text is empty
            if (isEmpty(this.currentQuestion.questionText[this.currentLang])) {
                this.errorMessage = translations.forms.fill_questiontext_warning;
               
                return false;
            }

            
            if( [4, 5, 6].includes(this.currentQuestion.questionType.category_id)){
                    return true;
            }

            // Check if answers are missing or empty
            if (!this.currentQuestion.answers.length || this.currentQuestion.answers.some(item => isEmpty(item.text[this.currentLang]))) {
                this.errorMessage = !this.currentQuestion.answers.length ? this.translations.forms.add_answers_warning : this.translations.forms.fill_answertext_warning;
            

                return false;
            }

            // All checks passed
            
            this.errorMessage = null;
            return true;
        },
       


        showError(errorMessage) {
            this.showAlert('error', errorMessage);
        },
      

        // while loading
        handleLoading(value) {
            this.isLoading = value;
        },

       
       

        // close modal
        closeModal() {
            this.selectedQuestionType = null;
            this.currentStep=1;

                this.$emit('close');
        },
        // show alert warning or success
        showAlert(type, title) {
            this.$swal.fire({
                position: 'top-end',
                icon: type,
                title: title,
                showConfirmButton: false,
                timer: 1500
            });

        },
        // check if disable of button is true or false;
        checkDisableSaveButton(){ 
            
             return this.questionAndAnswersFilled()==false;

        },
        // save question
        async saveQuestion() {

            //check if answers and question is filled
            if(!this.questionAndAnswersFilled)return false;

            this.isSaving = true;
           


             this.$emit('saved', this.currentQuestion);
            this.$emit('close');
            this.currentStep=1;
            this.selectedQuestionType=null;
            this.isSaving = false;
            


        },

        // update the status of question if optional or not
        toggleQuestionStatus(val){
             this.currentQuestion.optional=val;
        },

        // check if this question is multi answers question
        multiAnswersQuestion(){
        return this.currentQuestion.questionType.category_id==2;
        },
        // on change the answers view mode 
        onSelectionViewModeChange(val){
          this.currentQuestion.answers_view_mode=val;
        }





    }
};
</script>

<style scoped>
/* Add any scoped styles here */
</style>
