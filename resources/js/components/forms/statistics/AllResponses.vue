<template>
  <div class="w-full">
    <!-- export button -->
    <div class="flex justify-end items-center mb-1">
      <PrimaryButton :title="'Export'" @onClick="exportAllResponses()" />

    </div>
    <div class=" w-full">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100 sticky top-0 z-10"> <!-- Keep header fixed -->
          <tr>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.date }}</th>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.source }}</th>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.completion_avg }}</th>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.options }}</th>
          </tr>
        </thead>
      </table>

      <!-- Wrapper for scrollable body -->
      <div v-if="responses.length > 0" class="overflow-y-auto " style="max-height: 70vh;">
        <!-- Set your max-height here -->
        <table class="min-w-full border-t border-gray-200 text-sm">
          <tbody>
            <tr v-for="(response, index) in responses.reverse()" :key="index"
              class="border-b hover:bg-gray-50 min-h-14">
              <td class="py-2 px-4  w-1/4 text-xs text-center">{{ formatDateTime(response.created_at) }}</td>
              <td class="py-2 px-4  w-1/4 text-center">{{ response.device_id != null ? response.device.name : translations.forms.unknown
                }}
              </td>
              <td class="py-2 px-4   flex justify-between items-center space-x-1 rtl:space-x-reverse">
                <ProgressBar :percent="formatNumber(response.completion_avg, 2)" class="min-w-[75%] max-w-[75%]" />
                <h1>{{ formatNumber(response.completion_avg, 2) }}</h1>
              </td>
              <td class="py-2 px-4  w-1/4 ">
                <ViewSvg class="flex justify-center items-center" @click="showResponse(response)" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <Loader v-if="loading" />
  <ViewResponseModal v-if="viewResponse && responseData != null && formQuestions != null" :responseData="responseData"
    :formQuestions="formQuestions" @close="closeViewResponseModal" />

</template>

<script>
import PrimaryButton from '../../actions/PrimaryButton.vue';
import { StatisticsMixin } from '../../mixins/StatisticsMixin';
import { MessageMixin } from '../../mixins/MessageMixin';

import ViewResponseModal from '../../modals/ViewResponseModal.vue';
import ProgressBar from './components/ProgressBar.vue';
import ViewSvg from '../../svgs/View.vue';
import Loader from '../../svgs/Loader.vue';
export default {
  mixins: [StatisticsMixin, MessageMixin],

  components: {
    PrimaryButton, ViewResponseModal, ViewSvg, ProgressBar,Loader
  },
  props: {
    responses: {
      type: Array,
    },
    form: {
      type: Object,
    },
  },
  data() {

    return {

      currentResponseToView: null,
      viewResponse: false,
      responseData: null,
      formQuestions: null,
      loading:false,
      translations: window.translations,


    }
  },
  methods: {
    async exportAllResponses() {
      this.loading = true;

      try {
        const response = await axios.post('/statistics/responses/export', {

          responses: this.responses,
        }, {
          responseType: 'blob', // This is crucial to handle binary data
        });
        console.log(response);
        if (response.status === 200) {

          this.downloadFile(response.data, 'responses');

        }
      } catch (error) {
        console.log('error:', error);
        if (error.response && error.response.data) {
          error.response.data.message ? this.showAlert('error', error.response.data.message) : this.showAlert('error', 'An error occurred.');
        } else {
          this.showAlert('error', 'An unexpected error occurred.');

        }
      } finally {
        this.loading = false;
      }

    },


    // view response into modal
    async showResponse(response) {
      console.log("Show response start");
      await this.initalViewResponseData(response);

      console.log("Show response after initalViewResponseData");
      this.currentResponseToView = response;
      this.viewResponse = true;
      console.log("Show response end");

    },
    // close viewresponse modal
    closeViewResponseModal() {
      this.viewResponse = false;
      this.responseData = null;
    },
    //initial data for view response 
    async initalViewResponseData(response) {
      this.responseData = response.question_responses.reduce((acc, curr) => {
        if (!acc[curr.question_id]) {
          acc[curr.question_id] = {
            question_id: curr.question_id,
            answers: [],
            text_response: curr.text_response,
            skip: curr.skip
          };
        }
        acc[curr.question_id].answers.push(curr.answer_id);
        return acc;
      }, {});
      this.formQuestions = await this.getQuestionsWithAnswers();
      console.log('response data', this.responseData, this.formQuestions);
    },

    // get questions with answers for view response
    async getQuestionsWithAnswers() {
      return this.form.questions.reduce((acc, question) => {
        acc[question.id] = question;
        return acc;
      }, {});
    },
  },

}
</script>

<style lang="scss" scoped></style>