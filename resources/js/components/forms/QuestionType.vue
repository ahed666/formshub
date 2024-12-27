<template>
    <div
      @click.stop="toggleSelection"
      @mouseenter="showOverlay = true"
      @mouseleave="showOverlay = false"
      :class="{
      'border-blue-600 border': isSelected, // Blue border when selected
      'border-gray-300 border': !isSelected   // Gray border when not selected
    }"
      class=" rounded p-2 transition-all duration-300"
    >
      <!-- Hidden radio button -->
      <label class="flex justify-start items-center relative  p-1">
        <input
          type="radio"
          :value="questionType.id"
          :checked="isSelected"
          
          class="hidden"
        />
      </label>
  
      <div class="relative">
        <img
          :src="questionType.image"
          class="h-auto w-full object-contain"
          :class="{ 'animate-pulse': isLoading, 'opacity-20': showOverlay }"
        />
        <div>
          <h1 class="text-sm">{{ questionType.question_type_details }}</h1>
        </div>
      </div>
    </div>
  </template>

<style scoped>
/* Styling for hover effect */
label:hover .bg-gray-200.opacity-20 {
    opacity: 0.8;
    /* Adjust opacity as needed */
}
</style>
<script>
import ButtonComponent from '../actions/ButtonComponent.vue';
import EditSvg from '../svgs/Edit.vue';
import ShowQuestion from '../actions/ShowQuestion.vue';
export default {
    components: {
        ButtonComponent,
        EditSvg,
        ShowQuestion,
    },
    props: {
       
        questionType: {
            type: Object,
            required: true,
        },
        selectedQuestion:{
               type:Boolean,
               required:false,
               default:true,

        }

    },
    data() {
        return {
            showOverlay: false, // Track if overlay should be shown on hover
            selectedQuestionId:null,
            editedName: '',
            editedQuestionId: null,

        };
    },
   
    computed: {

        isSelected() {
            return this.selectedQuestion;
        },
    },
    methods: {
        
        


        
        
        showControls(event) {
            event.target.controls = true;
        },
        hideControls(event) {
            event.target.controls = false;
        },

        // toggle select item
        toggleSelection() {
     
             console.log(this.selectedQuestion); 
           this.$emit('update:selectedQuestionType', this.questionType);
        },
       




    },




};
</script>
