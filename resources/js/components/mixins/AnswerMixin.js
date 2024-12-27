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
          if(type_text==='drawing')
          {
              const baseURL = process.env.MIX_APP_URL || window.location.origin; // Get base URL
              const fullPath = `${baseURL}/storage/${answerText}`;
              return `<img src="${fullPath}" class="w-auto h-28 object contain border-[1px] border-gray-200 rounded p-1" />`;}
          else return answerText;

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
  