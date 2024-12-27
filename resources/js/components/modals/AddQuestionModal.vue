<!-- Modal.vue -->
<template>
    <modal-template :step="currentStep" :disableButtons="checkDisableSaveButton()"
     :showButtons="true" :isSaving="isSaving" @next="nextStep" @submit="saveQuestion" 
     @close="closeModal" :extraInfo="true"
      :info="{'questionOptional':questionOptional,'questionOptionalComp':true,questionViewAnswersModeValue:questionViewAnswersMode,'questionViewAnswersMode':multiAnswersQuestion()&&currentStep==2}" 
     @toggleQuestionStatus="toggleQuestionStatus" @view-mode-changed="onSelectionViewModeChange"
        :allowSelect="allowSelect" :errorMessage="errorMessage"  :title="translations.forms.add_question_modal_title">
        <div class="col-span-12 p-2 max-h-[60vh] overflow-y-auto ">

            <!-- Show loading spinner when question is being fetched -->
            <Loader v-if="isLoading"></Loader>

          
            <!-- first step - select question type  -->
            <div v-if="currentStep==1" class="w-full col-span-12"  v-for="category in questionCategories" :key="category.id">
                <div class="mb-1 mt-2 px-2 font-bold text-black  ">
                    <span>{{ category.category_name }}</span>
                </div>
                <div class=" grid grid-cols-12 gap-2 my-2">
                    <div  class="2xl:col-span-3 xl:col-span-3 lg:col-span-6 md:col-span-6 xs:col-span-6 border-[1px]  rounded-lg border-gray-200  max-h-64 m-1"
                    v-for="type in category.types" :key="type.id">
                    <!-- question item -->
                    
                    <QuestionType  :questionType="type" 
                    :selectedQuestion="selectedQuestionType ? selectedQuestionType.id === type.id : false"
                    @update:selectedQuestionType="setSelectedQuestion" ></QuestionType>
                    </div>
               </div>
            </div>


            <!-- step 2  -->
             <div v-else-if="currentStep==2">
                 <QuestionTemplate @updateQuestionText="updateQuestionText" @updateAnswers="updateAnswers" :selectedQuestionType="selectedQuestionType"></QuestionTemplate>
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
    name: 'AddQuestionModal',
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
        currentLang:{
            type:String,
            required:false,
            default:"lang1",
        },

    },
    data() {
        return {
            currentStep:1,
            questionCategories: [],
            selectedQuestionType: null,
            allowSelect: true,
            isLoading: true,
            isSaving: false,
            errorMessage:null,
            currentAnswers:[],
            questionText:null,
            questionOptional:true,
            questionViewAnswersMode:null,
            translations:window.translations,

        };

    },
    mounted() {
        this.selectedQuestionType=null;
        this.fetchQuestionCategories();
    },
    methods: {

        // check if question type is pre answers question
        multiAnswersQuestion(){
          return  this.selectedQuestionType!=null&&this.selectedQuestionType.category_id==2;
        },

        // on any update on answers
        updateAnswers(answers){
             this.currentAnswers=answers;
        },

        // on any update on question text
        updateQuestionText(question_text){
            this.questionText=question_text;
        },

        

        // check if question and answers is not empty
        questionAndAnswersFilled() 
        {
            console.log(this.currentAnswers);
            const isEmpty = str => !str || str.trim() === '';

            // Check if question text is empty
            if (isEmpty(this.questionText)) {
                this.errorMessage = translations.forms.fill_questiontext_warning;
                
                return false;
            }

            if( [4, 5, 6].includes(this.selectedQuestionType.category_id)){
                    return true;
            }

           

            // Check if answers are missing or empty
            if (!this.currentAnswers.length || this.currentAnswers.some(item => isEmpty(item.text[this.currentLang]))) {
                this.errorMessage = !this.currentAnswers.length ? this.translations.forms.add_answers_warning : this.translations.forms.fill_answertext_warning;
                console.log(this.errorMessage,this.currentAnswers.length,this.currentAnswers.some(item => isEmpty(item.text[this.currentLang])),this.currentLang);
                return false;
            }

            // All checks passed
            
            this.errorMessage = null;
            return true;
        },

        // control the moving to next step 
        nextStep(){
            this.selectedQuestionType==null?this.currentStep=1:this.currentStep=2;
        },
        setSelectedQuestion(questionType) {
          this.selectedQuestionType = questionType;
    },


        // show error
        showError(errorMessage) {
            this.showAlert('error', errorMessage);
        },
      

        // while loading
        handleLoading(value) {
            this.isLoading = value;
        },

        
        // fetch all questions types
        async fetchQuestionCategories() {
            try {
                const response = await axios.get('/questioncategories');
                this.questionCategories = response.data;
                console.log(response.data);
                this.isLoading = false;
            } catch (error) {
                console.error('Error fetching question:', error);
                this.isLoading = false;
            }
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
             return (this.currentStep==1&&this.selectedQuestionType==null)||(this.currentStep==2&&this.questionAndAnswersFilled()==false);

        },
        // save question
        async saveQuestion() {

            //check if answers and question is filled
            if(!this.questionAndAnswersFilled)return false;

            this.isSaving = true;
           console.log('saving');


             this.$emit('saved', this.questionText,this.questionOptional,this.questionViewAnswersMode,this.currentAnswers,this.selectedQuestionType);
            this.$emit('close');
            this.currentStep=1;
            this.selectedQuestionType=null;
            this.isSaving = false;
            


        },

        // update the status of question if optional or not
        toggleQuestionStatus(val){
             this.questionOptional=val;
        },
        onSelectionViewModeChange(val){
            this.questionViewAnswersMode=val;
        }




    }
};
</script>

<style scoped>
/* Add any scoped styles here */
</style>
