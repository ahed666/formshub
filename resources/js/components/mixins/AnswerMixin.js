// src/js/components/mixins/AnswerMixin.js

export const AnswerMixin = {
    methods: 
    {

      getCurrentAnswerText(answer) {
        let translation = answer.translations.find(
          (translation) => translation.language === this.currentTranslation.language
        );
        return translation ? translation.answer_text : 'Translation not available';
      },
      getAnswerText(answer){
            
            var translation=answer.translations.filter(trnas => trnas.language == this.currentLang)[0];
            return translation.answer_text;

      },
      calculatePercent(count,total){
      return  count?(count*100/total).toFixed(2):(0).toFixed(2);

      },

      initialTextAnswer(type_text,answerText){
        if(answerText==null)
        {return `<div class="flex justify-center items-center space-x-1" style="display:flex; justify-content:center; align-items:center; ">
          <h1 style="font-size:small">Skipped</h1><svg  style="color:#1277d1; margin-left:4px; width: 20px; height: 20px;"
          class="md:h-5 md:w-5 xs:h-3 xs:w-3 text-secondary_blue" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" >
    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
  </svg>
          </div>`;}
        else{
          if(type_text==='drawing')
          {  
              const baseURL = process.env.MIX_APP_URL || window.location.origin; // Get base URL
              const fullPath = `${baseURL}/storage/${answerText}`;
              return `<div class="flex justify-center items-center">
              <img src="${fullPath}" class="w-auto h-28 object contain border-[1px] border-gray-200 rounded p-1" />
              </div>`;}
          else return answerText;
          }

      },
      isSelected(answerId) {
        if (this.questionsWithAnswers && this.questionId) {
          const answersForQuestion = this.questionsWithAnswers[this.questionId];
          return answersForQuestion && answersForQuestion.answers[answerId] !== undefined;
        } else {
          return false;
        }
      },
      chooseAnswer(answer, isSelected,selectionType) {
          console.log('answer logic:',answer,isSelected);
          this.$emit('choose_answer', answer, isSelected,selectionType);
      },
      getSelectionType() {
          if (this.questionType.type_text === "mcq")
              return 'single';
          else if (this.questionType.type_text === "checkbox")
              return 'multiple';

      },
    },
  };
  