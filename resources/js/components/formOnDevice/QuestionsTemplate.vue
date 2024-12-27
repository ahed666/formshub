<template>
    <div class="flex flex-col h-screen">
        <InactivityWarning />
        <!-- Render QuestionText component if currentQuestion and translation exist -->
        <!-- <transition name="fade" mode="out-in"> -->
        <QuestionText  :class="['h-2/6',{'animate-slide-out':back,'animate-slide-in':next}]"  if="currentQuestion && getCurrentQuestionText()"
            :questionText="getCurrentQuestionText()" />
        <AnswersTemplate
         ref="AnswersTemplate"
          @onErrorTyping="onErrorTyping" @saveDrawing="saveDrawing"
           @choose_answer="chooseAnswer" @type_answer="typeAnswer" 
           :class="['h-3/6',{'animate-slide-out':back,'animate-slide-in':next}]"
            v-if="currentQuestion" :currentQuestion="currentQuestion" :answers="currentQuestion.answers"
            :answersViewMode="currentQuestion.answers_view_mode" :currentTranslation="currentTranslation"
            :questionType="currentQuestion.type" />
        <!-- </transition> -->
        <ControlButtons :questionOptional="questionOptional" :allowNext="allowNext" @skip="skipQuestion"
            @next="nextQuestion" @back="backQuestion" class="h-1/6" />
    </div>
</template>

<script>
import ControlButtons from './ControlButtons.vue';
import InactivityWarning from './InactivityWarning.vue';
import AnswersTemplate from './questions/answers/AnswersTemplate.vue';
import QuestionText from './questions/QuestionText.vue';
import { reactive } from 'vue';

export default {
    components: {
        QuestionText, InactivityWarning, AnswersTemplate, ControlButtons
    },
    props: {
        questions: {
            type: Array,
            required: true,
        },
        currentTranslation: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            currentQuestionIndex: 0,
            currentQuestion: null, // Initialize as null
            questionsWithAnswers: reactive({}), // Make this reactive
            questionOptional: false,
            allowNext: false,
            next:false,
            back:false,
        };
    },
    mounted() {
        if (this.questions.length > 0) {
            this.currentQuestion = this.questions[this.currentQuestionIndex]; // Assign the first question
            this.questionOptional = this.currentQuestion.optional;
        }
        console.log(this.currentQuestion);
    },
    methods: {
        getCurrentQuestionText() {
            if (!this.currentQuestion || !this.currentQuestion.translations) {
                return null;
            }
            // Safely find the translation for the current language
            let translation = this.currentQuestion.translations.find(
                (translation) => translation.language === this.currentTranslation.language
            );
            // Return the question text if the translation is found, otherwise return a fallback or null
            return translation ? translation.question_text : 'Translation not available';
        },
        checkAndCreateNewQuestionWithAnswerItem( skipped){
            if (!this.questionsWithAnswers[this.currentQuestion.id]) {
                this.questionsWithAnswers[this.currentQuestion.id] = {
                    answers: {},
                    skipped: !!skipped,
                    withAnswers:this.currentQuestion.with_answers,
                    questionId:this.currentQuestion.id,
                    questionType:this.currentQuestion.type,
                };
            }
        },
        chooseAnswer(answer, isSelected, selectionType) {
            
            this.checkAndCreateNewQuestionWithAnswerItem(Boolean(false));

            // Check if the selection type is single
            if (selectionType === 'single') {
                // Clear previously selected answers if a new one is selected
                if (isSelected) {
                    this.questionsWithAnswers[this.currentQuestion.id].answers = {}; // Clear previous answers
                    this.questionsWithAnswers[this.currentQuestion.id].answers[answer.id] = answer; // Add the new answer
                    this.allowNext = true;
                }

            } 
            else if (selectionType === 'multiple') {
                // For multiple selections, just add or remove the answer
                if (isSelected) {
                    // Add answer object to the answers object
                    this.questionsWithAnswers[this.currentQuestion.id].answers[answer.id] = answer;
                } else {
                    // Remove the answer if deselected
                    delete this.questionsWithAnswers[this.currentQuestion.id].answers[answer.id];
                }
                if (Object.keys(this.questionsWithAnswers[this.currentQuestion.id].answers).length > 0) this.allowNext = true;
                else this.allowNext = false;
            }

            console.log(this.questionsWithAnswers[this.currentQuestion.id]);

        },
        // while typeing answer in questions of text input 
        typeAnswer(text) {

            this.checkAndCreateNewQuestionWithAnswerItem(Boolean(false));

            if (text === '') {
                this.allowNext = false;


            }
            else {

                this.questionsWithAnswers[this.currentQuestion.id].answers[0] = text;
                this.allowNext = true;
            }
            console.log(this.questionsWithAnswers);

        },
        // save blob into questionwithanswers for question of drawing type
        saveDrawing(blob){
           
            this.checkAndCreateNewQuestionWithAnswerItem(Boolean(false));
            if(blob==null)  this.allowNext = false;
            else

            {this.questionsWithAnswers[this.currentQuestion.id].answers[0] = blob;
            this.allowNext = true;}

        },

        // when click on next question button
        nextQuestion() {
            
            const answersTemplateRef = this.$refs.AnswersTemplate;
            if (answersTemplateRef && answersTemplateRef.$refs.TextAnswer) {
                answersTemplateRef.$refs.TextAnswer.clearInput(); // Call clearInput method
            }
            if (this.currentQuestionIndex + 1 < this.questions.length) {
                this.next = true;
                // Delay to allow animation to play before moving to the next question
                setTimeout(() => {
                this.next = false;

                }, 1000); // Match animation duration
                this.currentQuestion = this.questions[this.currentQuestionIndex + 1];
                this.currentQuestionIndex += 1;
                this.allowNext = false;
                this.questionOptional = this.currentQuestion.optional;
            }
            else {
                // emit to submit response
                this.$emit('finish', this.questionsWithAnswers);
            }
        },

        // back question
        backQuestion(){
            this.back = true;
                // Delay to allow animation to play before moving to the next question
                setTimeout(() => {
                this.back = false;

                }, 1000);
            // return to first step
            if (this.currentQuestionIndex==0) {
                this.questionsWithAnswers=reactive({});
                this.$emit('cancel');
            }
            else
            {
                if (this.questionsWithAnswers[this.currentQuestionIndex] !== undefined) {
                        delete this.questionsWithAnswers[this.currentQuestionIndex];
                }
                this.currentQuestionIndex -=1;
                
                this.currentQuestion = this.questions[this.currentQuestionIndex];
                this.allowNext = false;
                this.questionOptional = this.currentQuestion.optional;

            }
        },

        skipQuestion(){
            
            this.checkAndCreateNewQuestionWithAnswerItem(Boolean(true));

            
            this.questionsWithAnswers[this.currentQuestion.id].skipped=true;
            this.questionsWithAnswers[this.currentQuestion.id].answers={};
            
            this.nextQuestion();
           
        },

        onErrorTyping(){
           this.allowNext=false;
        },

    },
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  @apply transition-opacity duration-500 ease-in-out;
}
.fade-enter, .fade-leave-to {
  @apply opacity-0;
}
</style>