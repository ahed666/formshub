<template>
    <div class="mx-16   ">
        <div class="mt-2 w-full pr-2 flex-grow flex justify-center items-center ">
            
                <LogicAnswers 
                    v-if="checkLogicQuestion()&&currentQuestion!=null" 
                    @choose_answer="chooseAnswer"
                    :answers="answers" 
                    :currentTranslation="currentTranslation" 
                    :questionId="currentQuestion.id" 
                     :questionsWithAnswers="questionsWithAnswers"
                />

                <!-- multi answers -->
                <MultiAnswers 
                    v-if="checkMultiQuestion()&&currentQuestion!=null" 
                    @choose_answer="chooseAnswer"
                    :answers="answers" 
                    :currentTranslation="currentTranslation" 
                    :questionId="currentQuestion.id" 
                     :questionsWithAnswers="questionsWithAnswers"
                     :questionType="currentQuestion.type"
                     :answersViewMode="answersViewMode"
                />
                <SatisfactionAndRatingAnswers 
                    v-if="checkSatisfactionAndRatingAnswersQuestion()&&currentQuestion!=null" 
                    @choose_answer="chooseAnswer"
                    :answers="answers" 
                    :currentTranslation="currentTranslation" 
                    :questionId="currentQuestion.id" 
                     :questionsWithAnswers="questionsWithAnswers"
                     :questionType="currentQuestion.type"
                     :answersViewMode="answersViewMode"
                     
                />
                
                <TextAnswer
               ref="TextAnswer"
                    v-if="checkTextAnswersQuestion()&&currentQuestion!=null" 
                    @onErrorTyping="onErrorTyping"
                    @typingAnswer="typingAnswer"
                    :questionId="currentQuestion.id" 
                     :questionsWithAnswers="questionsWithAnswers"
                     :questionType="currentQuestion.type"
                     
                />
                <DateAnswer 
                ref="TextAnswer"
                    v-if="checkDateAnswersQuestion()&&currentQuestion!=null" 
                   
                    @typingAnswer="typingAnswer"
                    :questionId="currentQuestion.id" 
                     :questionsWithAnswers="questionsWithAnswers"
                     :questionType="currentQuestion.type"
                     
                />

                <DrawingAnswer 
                @saveDrawing="saveDrawing"
                v-if="checkDrawingAnswersQuestion() && currentQuestion"
        :questionId="currentQuestion.id"
        :questionsWithAnswers="questionsWithAnswers"
                />

           
        </div>
    </div>
</template>

<script>
import LogicAnswers from './LogicAnswers.vue';
import MultiAnswers from './MultiAnswers.vue';
import SatisfactionAndRatingAnswers from './SatisfactionAndRatingAnswers.vue';
import { AnswerMixin } from '../../../mixins/AnswerMixin';
import TextAnswer from './TextAnswer.vue';
import DateAnswer from './DateAnswer.vue';
import DrawingAnswer from './DrawingAnswer.vue';
    export default {
        mixins: [AnswerMixin], // Use the mixin in the parent component

        components:{
            LogicAnswers,
            MultiAnswers,
            SatisfactionAndRatingAnswers,
            TextAnswer,
            DateAnswer,
            DrawingAnswer,
        },
        props:{
            answers:{
                type:Object,
                Required:false,
            },
            currentTranslation:{
                type:Object,
                Required:false,
            },
            questionType:{
                type:Object,
                Required:false,
            },
            currentQuestion:{
                type:Object,
            },
            questionsWithAnswers: { 
            type: Object,
            required: true,
           },
           answersViewMode:{
            type:String,
            default:null,
            required:false,
        }
        },
        methods:{
            checkDrawingAnswersQuestion(){
              
                return [6].includes(this.questionType.category_id);

            },
            checkDateAnswersQuestion(){
                                                console.log('checkDateAnswersQuestion',this.questionType);

                return [5].includes(this.questionType.category_id);

            },
            checkTextAnswersQuestion(){
                                console.log('checkTextAnswersQuestion',this.questionType);
                return [4].includes(this.questionType.category_id);

            },
            checkSatisfactionAndRatingAnswersQuestion(){
                console.log('checkSatisfactionAndRatingAnswersQuestion',this.questionType);
                return [3].includes(this.questionType.category_id);

            },
            checkMultiQuestion(){
                                console.log('checkMultiQuestion',this.questionType);

                return [2].includes(this.questionType.category_id);
            },
            checkLogicQuestion(){
                                                console.log('checkLogicQuestion',this.questionType);

                return [1].includes(this.questionType.category_id);

            },
            chooseAnswer(answer,isSelected,selectionType){
                console.log('template:',answer,isSelected);

                this.$emit('choose_answer',answer,isSelected,selectionType)
            },
            typingAnswer(text){
                this.$emit('type_answer',text)
            },

            // when user make a drawing and finish then can this function to pass blob to save it 
            saveDrawing(blob){
                 this.$emit('saveDrawing',blob);
            },

            onErrorTyping(){
            this.$emit('onErrorTyping');
        },
        },
        
    }
</script>

<style lang="scss" scoped>

</style>