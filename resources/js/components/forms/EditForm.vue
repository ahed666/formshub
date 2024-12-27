<template>
    <app-template>
        <Loader v-if="isLoading" />
        <div class="grid grid-cols-12 gap-2">
            <!-- settings -->
            <FormSettings
                :currentForm="currentForm"
                @editFormName="editFormName"
                @onImageSelected="onImageSelected"
            />
            

             <!-- questions List -->
              
            <QuestionsList 
                v-if="!isLoading && formTranslations" 
                :formQuestions="formQuestions"
                :formTranslations="formTranslations"
                :currentLang="currentLang"
                :isLoading="isLoading"
                :maxItems="maxItems"
                :defaultLang="defaultLang"
                @changeLanguage="updateCurrentLanguage"
                @disableLanguage="toggleLanguageDisable" 
                @changeLanguageName="onChangeLanguageName"
                @showModal="showModal"
                @setSelectedEditQuestion="setSelectedEditQuestion"
                @deleteQuestion="deleteQuestion"
                @updateQuestionsOrder="updateQuestionsOrder"
            />
            
        </div>
        
        <ModalManager 
            v-if="isModalVisible" 
            :typeModal="typeModal" 
            :form="form" 
            :formTranslations="formTranslations"
            :selectedQuestionToEdit="selectedQuestion"
            :currentLang="currentLang"
            @close="hideModal" 
            @saved="handleModalSave"
        />



        <ChangesFooter :ifEdit="ifEdit">

            <div class="flex justify-end space-x-4 rtl:space-x-reverse items-center">
                <ButtonComponent :btnClass="'bg-gray-400  w-14'" :handleClick="cancelChange">
                    <span> {{translations.buttons.cancel}}</span>
                </ButtonComponent>
                <ButtonComponent :btnClass="'bg-secondary_blue w-28'" :handleClick="saveChanges">
                    <span>{{ translations.buttons.save_changes }}</span>
                </ButtonComponent>

            </div>
        </ChangesFooter>

    </app-template>

</template>

<script>

import Loader from '../svgs/Loader.vue';
import ChangesFooter from '../ChangesFooter.vue';
import ButtonComponent from '../actions/ButtonComponent.vue';
import FormSettings from './FormSettings.vue';
import QuestionsList from './QuestionsList.vue';
import ModalManager from '../modals/ModalManager.vue';
import { MessageMixin } from '../mixins/MessageMixin';
export default {
    mixins: [MessageMixin], 

    components: {
      
        ChangesFooter,
        ButtonComponent,
        Loader,

        FormSettings,
        QuestionsList,
        ModalManager
    },
    props: {
        maxItems: {
            type: Number,
            required: true,
        },
        form: {
            type: Object,
            required: true,
        },

        question: {
            type: Array,
            default: () => [],
            required: true,
        }

    },

    data() {
        return {
            
            isModalVisible: false,
            typeModal: null,
            formQuestions: [],
            


            newAnswersCount: 0,
            isLoading: true,
            ifEdit: false,
            whatEdited: [],
            bgAudioMusicFormData: null,
            currentForm: null,
            errorScheduleFill: false,
            errorbgMusicFill: false,
            currentLang: 'lang1',
            defaultLang: 'lang1',
            formTranslations: {}, // Initialize as an empty object
            languages: ['lang1', 'lang2'],
            selectedQuestion: null,
            translations: window.translations,



        }
    },
    mounted() {

        this.currentForm = this.form;

        this.fetchData();
    },



    methods: {
        // handle modal save function
        
        handleModalSave(props) {
         
            if(this.typeModal=="addquestion")
            this.addQuestionToQuestionList(props);
            else if(this.typeModal=="editquestion")
            this.editQuestion(props);
            else if(this.typeModal=="editformmessagestart"||this.typeModal=="editformmessageend")
            this.editFormTranslationMessage(props);
        },

        // set which question will be edit
        setSelectedEditQuestion(question) {
            console.log('selected question to edit', question);

            this.selectedQuestion = JSON.parse(JSON.stringify(question));
            // 1 meaning show modal as edit question
            this.showModal('editquestion');
        },

        //edit form name
        editFormName(name) {
            console.log(this.form, this.currentForm);
            if (name != this.currentForm.name)
                this.ifEdit = true; // Set edit flag
            this.currentForm.name = name;


        },
        // Handle image selection
        onImageSelected(imageFile) {
            this.currentForm.logo = imageFile;  // Store the selected image file
        },

        //  formTranslations methods
        // update current language
        updateCurrentLanguage(lang) {
            this.currentLang = lang;
        },

        // toggle langauge disable
        toggleLanguageDisable(langCode) {
            console.log(this.formTranslations[langCode].enable);
            this.formTranslations[langCode].enable = !this.formTranslations[langCode].enable;
            console.log(this.formTranslations[langCode].enable, this.formTranslations[langCode]);
            this.ifEdit = true; // Set edit flag


        },
        // change language name
        onChangeLanguageName(index, value) {
            this.formTranslations[index].language_name = value;
            this.ifEdit = true; // Set edit flag


        },
        // set edit on form message 
        editFormTranslationMessage(props) {
            const {formTranslation}=props;
            this.formTranslations[this.currentLang] = formTranslation;
            this.ifEdit = true;

        },


        // delete item
        deleteQuestion(deletedQuestion) {

            this.formQuestions = this.formQuestions.filter(questionItem => questionItem.id !== deletedQuestion.id);
            this.formQuestions.forEach((question, index) => {
                question.order = index + 1; // Assuming order starts from 1
            });

            this.ifEdit = true; // Set edit flag
            console.log('after:', this.formQuestions);
        },
        // Update order of formQuestions items
        updateQuestionsOrder(localFormQuestions) {
              this.formQuestions=localFormQuestions;
            this.formQuestions.forEach((question, index) => {
                question.order = index + 1; // Assuming order starts from 1
            });
            console.log(this.formQuestions);
            this.ifEdit = true; // Set edit flag
        },





        // fetch all question
        async fetchData() {
            try {
                this.isLoading = true;
                const response = await axios.get(`/formInfo/${this.form.slug}`);
                console.log(response.data);
                this.formQuestions = response.data.formQuestions;
                console.log(this.formQuestions);
             
                this.formTranslations = response.data.formTranslations;

                
            } catch (error) {
                console.error('Error fetching question:', error);
                
            }
            finally {
                this.isLoading = false; // Reset loading state
            }
        },


        // hide and show the modals
        showModal(type) {
              
            this.isModalVisible = true; this.typeModal = type; 
            console.log(type,'-',this.typeModal);

        },

        hideModal() {
            this.isModalVisible = false;
        },






        // show alert warning or success
        showAlert(type, title) {
            this.$swal.fire({
                position: 'top-end',
                icon: type,
                title: title,
                showConfirmButton: false,
                timer: 3500
            });
        },

        // after add question to forms
        addQuestionToQuestionList(props) {
            const { newQuestionText, questionOptional, questionViewAnswersMode, answers, type } = props;
            console.log('answers:',answers);
            // Check if the maximum number of items has been reached
            if (this.maxItems < this.formQuestions.length + 1) {
                this.showAlert('error', 'Cannot add more items! You have reached the maximum number of items.');
                return; // Exit early to avoid further processing
            }
            // Create a new question object
            const newQuestion = {
                id: `new-${this.newAnswersCount}`,
                order: this.formQuestions.length + 1,
                optional: questionOptional,
                questionType: type,
                answers_view_mode: questionViewAnswersMode,
                questionText: this.languages.reduce((acc, lang) => {
                    acc[lang] = newQuestionText; // Set the question text for each language
                    return acc;
                }, {}),
                answers: [] // Initialize answers for different languages
            };
            

            // Add answers and their translations
            answers.forEach((answer, index) => {
                const newAnswer = this.createAnswerObject(answer, index, newQuestion.id);
                newQuestion.answers.push(newAnswer); // Push the new answer to the question's answers array
            });

            console.log(newQuestion);
            console.log('before',this.formQuestions.length);
            // this.formQuestions.push(newQuestion);
            this.formQuestions=[...this.formQuestions,newQuestion]
            this.ifEdit = true;
            console.log('after',this.formQuestions.length);
            // this.showAlert('success', 'question added successfully');
            // this.fetchData();
            

        },
        // Helper function to create a new answer object
        createAnswerObject(answer, index, questionId) {
            const newAnswer = {
                id: `new-answer-${index}`,
                question_id: questionId,
                image: answer.image, // Assuming answer has an image
                hide: answer.hide || false,
                text: this.createAnswerTextObject(answer.text) // Create the text object for translations
            };
            return newAnswer;
        },
        // Helper function to create text object for translations
        createAnswerTextObject(answerText) {
            return this.languages.reduce((acc, lang) => {
                if (!answerText[lang]) {
                    // Find a fallback language
                    const fallbackLanguage = this.languages.find(l => answerText[l]);
                    acc[lang] = answerText[fallbackLanguage] || ''; // Default to empty string if no fallback
                } else {
                    acc[lang] = answerText[lang];
                }
                return acc;
            }, {});
        },

        // after edit question 
        editQuestion(props) {
            const {question}=props;
            console.log('edit the question:', question);
            this.formQuestions = this.formQuestions.map(q =>
                q.id === question.id ? question : q
            );

            this.ifEdit = true;
        },


        // cancel the change on form
        cancelChange() {
            this.fetchData();
            this.ifEdit = false;
        },





        /* save changing on form
        1.. add items
        2.. edit on items
      
        */
        async saveChanges() {


            console.log('items on save:', this.formQuestions, this.currentForm);





            const response = axios.post('/savechangesonform', {
                formQuestions: this.formQuestions,
                form: this.currentForm,
                formTranslations: this.formTranslations,

            }, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
            ).then(response => {
                // if add successfuly
                if (response.data.status == true) {
                    this.ifEdit = false;
                    this.fetchData();
                    this.showAlert('success', 'The Changes Saved Successfully');
                    // Redirect to a new route after successfully editing the form
                    // window.location.href = response.data.redirect;

                }
                // if not success
                else {

                    this.showAlert('error', 'Error while save changes,'.response.data.message);
                }
            })
                .catch(error => {

                    this.showAlert('error', 'Error While save changes'.response.data.message);
                });

        },



    },



};
</script>
