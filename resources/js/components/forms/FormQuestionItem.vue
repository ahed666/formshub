<template>
    <div>
       
        <!-- question item -->

            <div class="flex justify-between ">
                <div class="w-1/3 max-w-1/3">
                    <span class="mx-[1px] rounded bg-[#faebd7] text-black p-[2px] text-sm xs:text-xs">🆀 {{ question.questionType.question_type_details }}</span>
                    <span class="mx-[1px] rounded text-sm xs:text-xs bg-green-100 p-[2px] ">{{ getQuestionStatus() }}</span>
                 
                </div>
                <div class="w-1/3 max-w-1/3 justify-center flex items-center">
                    <span class="text-blue-900 text-bold  text-lg font-bold ">{{ question.order }}</span>
                </div>
                <div class="max-w-1/3 w-1/3 space-x-1 rtl:space-x-reverse flex justify-end items-center">
                 
                    <Delete :deleteAction="deleteQuestion" :confirmText="'Are you sure you want to delete this question?'"></Delete>

                    <Edit @edit="editQuestion"></Edit>

                 
                </div>
            </div>

            <div class="mt-2 mb-2 flex justify-center items-center">
                <span class="text-center">{{ question.questionText[currentLang] }}</span>
            </div>
                                                                
    



    </div>

   
</template>
<script>


import Delete from '../actions/Delete.vue';
import ShowQuestion from '../actions/ShowQuestion.vue';
import Edit from '../actions/Edit.vue';
export default {
    components: {
      
        ShowQuestion,
        Edit,
        Delete,
    },
    props: {
        question: {
            type: Object,
            required: true,
        },
        currentLang:{
            type:String,
            required:true,
        }



    },
    data() {
        return {
            editedDurationQuestionId: null,
            editedDurationQuestionValue: null,
            showTooltip: false,
        };
    },
    mounted() {

    },


    methods: {
         
        editQuestion(){
            console.log('form question item - edit question');
            
         this.$emit('setSelectedEditQuestion',this.question);
        },
        // return if question optional or not
        getQuestionStatus(){
         return    this.question.optional==true?"Optional":"Mandatory";
        },

        // view edit question duration box
        showEditQuestionDurationBox(question) {

            this.editedDurationQuestionValue = question.duration;
            this.editedDurationQuestionId = question.id;
            this.$nextTick(() => {
                this.$refs.durationInput.focus();
            });
        },
        // delete item
        deleteQuestion() {
            this.$emit('deleteQuestion', this.question);
        },
        saveDuration(question) {
            if (this.editedDurationQuestionValue < 0.5) {
                this.editedDurationQuestionValue = 0.5;
            } else if (this.editedDurationQuestionValue > 86400) {
                this.editedDurationQuestionValue = 86400;
            }
            this.$emit('saveduration', question, this.editedDurationQuestionValue);
            this.editedDurationQuestionId = null;
            this.editedDurationQuestionValue = null;
        },
        formatDuration(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        },
       







    },

    watch:{
        // watch for duration change
        editedDurationQuestionValue:{
            handler(newVal) {
                this.editedDurationQuestionValue= newVal>86400?86400:(newVal<1?1:newVal);

            }
        }

    }



};
</script>

<style>
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  input[type="number"]::-webkit-inner-spin-button,
  input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
}
</style>
