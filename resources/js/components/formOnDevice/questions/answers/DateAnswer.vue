<template>

    <div v-if="questionType != null" class="p-2 w-full">
     
       
        <input ref="dateInput" id="dateInput" name="dateInput" type="date" v-model="inputDate"
               @input="handleDateChange" placeholder="Select a date..."
               class="border border-gray-300 p-2 w-full" />
      
    </div>
</template>

<script>
import { AnswerMixin } from '../../../mixins/AnswerMixin';


export default {
    mixins: [AnswerMixin], // Use the mixin in the parent component

    components: {
        
    },
    props: {

        questionType: {
            type: Object,
            Required: true,
        },
        questionId: {
            type: String,

        },
        questionsWithAnswers: { // Receive questionsWithAnswers as a prop
            type: Object,
            required: true,
        },


    },
    data() {
        return {
            inputText: '',
            
            
            
        }
    },
    methods: {

        handleDateChange() {
      if (this.inputDate) {
        this.$emit('typingAnswer', this.inputDate);
      }
    },
    clearInput() {
      this.inputDate = '';
      this.$emit('typingAnswer', this.inputDate);
    },
    },
}
</script>

<style lang="scss" scoped></style>