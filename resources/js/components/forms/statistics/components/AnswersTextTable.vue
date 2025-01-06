<template>
   <div class="max-h-full w-full bg-white p-1">
  <table class="min-w-full border border-gray-200">
    <thead class="bg-gray-100 sticky top-0 z-10"> <!-- Keep header fixed -->
      <tr>
        <th class="py-2 px-4 border-b font-semibold text-left w-4/5">{{ translations.forms.answer }}</th>
        <th class="py-2 px-4 border-b font-semibold text-left w-1/5">{{ translations.forms.date }}</th>
      </tr>
    </thead>
  </table>
  
  <!-- Wrapper for scrollable body -->
  <div class="overflow-y-auto" style="max-height: 150px;"> <!-- Set your max-height here -->
    <table class="min-w-full border-t border-gray-200">
      <tbody>
        <tr v-for="(answer, index) in answers" :key="index" class="hover:bg-gray-50">
          <td class="py-2 px-4 border-b w-4/5 flex space-x-1 rtl:space-x-reverse">{{ index + 1 }}.
            <span class="mx-2" v-html="initialTextAnswer(questionType.type_text, answer.text)"></span>
          </td>
          <td class="py-2 px-4 border-b w-1/5">{{ formatDateTime(answer.date) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</template>

<script>
import { StatisticsMixin } from '../../../mixins/StatisticsMixin';
import { AnswerMixin } from '../../../mixins/AnswerMixin';
    export default {
      mixins: [StatisticsMixin,AnswerMixin], // Use the mixin in the parent component

        props:{
            answers:{
                type:Array,

            },
            questionType:{
              type:Object,
            },
        },
        data(){
          return{
            translations: window.translations,

          }
        },
        methods:{
            
        },
        
    }
</script>

<style lang="scss" scoped>

</style>