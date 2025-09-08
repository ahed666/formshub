<!-- Modal.vue -->
<template>
    <modal-template  :disableButtons="isSaving" :showButtons="false"
      @close="closeModal" 
      :extraInfo="true" 
      :info="{'buttonMode':true,'buttonTitle':translations.buttons.print,'handleButtonClick':printResponse}"
       title="View Response">
  
      <div class="col-span-12 responseTable">

     
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100 sticky top-0 z-10"> <!-- Keep header fixed -->
        <tr>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/2">{{ translations.forms.view_response_question_title }}</th>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/2">{{ translations.forms.view_response_answer_title }}</th>

        </tr>
        </thead>
    </table>
    


    <div   class="overflow-y-auto" style="max-height: 600px;"> <!-- Set your max-height here -->
        <table class="min-w-full border-t border-gray-200 text-sm">
        <tbody>
            <tr v-for="(data, index) in responseData" :key="index" class="border-b hover:bg-gray-50 min-h-14">
            <td class="py-2 px-4  w-1/4 text-xs text-center">{{ getQuestionText(data.question_id) }}</td>
            <td class="py-2 px-4  w-1/4 text-center" v-html="getAnswers(data.question_id)"></td>
            
            </tr>
        </tbody>
        </table>
    </div>
  
</div>
  
  
  
    </modal-template>
  
  </template>
  
  <script>
import ModalTemplate from '../ModalTemplate.vue';
import { AnswerMixin } from '../mixins/AnswerMixin';
  export default {
    name: 'AddFormModal',
    mixins:[AnswerMixin],
    components: {    ModalTemplate,

     
  
    },
    props: {
     
      responseData: {
            type:Object,
      },
      formQuestions:{
        type:Object,
      },

    },
    data() {
      return {
        currentLang:'lang1',
        translations:window.translations,
      
  
      };
  
    },
    methods: {
  
      // close modal
      closeModal() {
        this.$emit('close');
      },
      getQuestionText(question_id){
       
        var question = this.formQuestions[question_id];
        if (!question) return null; // Handle case where question is not found

        var translation = question.translations.find(translation => translation.language === this.currentLang);
        return translation ? translation.question_text : null; 

      },
       getAnswers(question_id){
        var question = this.responseData[question_id];
        var formQuestion=this.formQuestions[question_id];
        if(question.text_response!=null||(Number(question.skip) === 1 ? 1 : 0))
        return  this.initialTextAnswer(formQuestion.type.type_text,question.text_response);
        else 
        return this.getPreDefinedAnswers(formQuestion,question.answers);

        },
        
      
        getPreDefinedAnswers(formQuestion,answersIds){
            var answersText=[];
           
            answersIds.forEach(answerId => {

                var answer=formQuestion.answers.find(answer => Number(answer.id)===Number(answerId));
              
                var translation=answer.translations.find(translation => translation.language === this.currentLang);
              
                answersText.push(translation.answer_text);

            });
            return answersText.join(",");
        },
        printResponse() {
          // Find the modal content
          const modalContent = this.$el.querySelector('.col-span-12');

          if (!modalContent) {
            console.error("Modal content not found");
            return;
          }

          // Clone the content
          const clonedContent = modalContent.cloneNode(true);

          // Create a new window for printing
          const printWindow = window.open('', '_blank', 'width=800,height=600');
          if (!printWindow) {
            console.error("Unable to open a new print window");
            return;
          }

          // Create a basic HTML structure for the print window
          printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
              <title>Print Response</title>
              <style>
                body {
                  font-family: Arial, sans-serif;
                  margin: 0;
                  padding: 20px;
                }
                table {
                  width: 100%;
                  border-collapse: collapse;
                }
                th, td {
                  border: 1px solid #ddd;
                  padding: 8px;
                  text-align: left;
                }
                th {
                  background-color: #f4f4f4;
                  font-weight: bold;
                }
                img {
                  max-width: 100%; /* Ensure images fit within table cells */
                  height: auto;
                }
              </style>
            </head>
            <body>
            </body>
            </html>
          `);

          // Append the cloned content to the new document's body
          printWindow.document.body.appendChild(clonedContent);

          // Wait for all images to load before printing
          const images = printWindow.document.body.querySelectorAll('img');
          let imagesLoaded = 0;

          if (images.length > 0) {
            images.forEach((img) => {
              img.onload = () => {
                imagesLoaded++;
                if (imagesLoaded === images.length) {
                  triggerPrint();
                }
              };

              img.onerror = () => {
                console.warn(`Image failed to load: ${img.src}`);
                imagesLoaded++;
                if (imagesLoaded === images.length) {
                  triggerPrint();
                }
              };
            });
          } else {
            // If there are no images, print immediately
            triggerPrint();
          }

          function triggerPrint() {
            printWindow.document.close(); // Necessary for older browsers
            printWindow.focus(); // Necessary for some browsers
            printWindow.print();
            printWindow.close();
          }
        }

    },
    
  };
  </script>
  
  <style scoped>
  /* Add any scoped styles here */
  </style>
  