<template>
     <app-template>
    <div class="" v-if="form">
      <!-- Tabs Navigation -->
      <div class="md:flex xs:grid p-1  md:gap-0 xs:gap-2 xs:space-x-0 md:space-x-4  rtl:space-x-reverse  border-[1px] border-gray-200">
        <button 
          v-for="tab in tabs" 
          :key="tab" 
          @click="activeTab = tab.code" 
          :class="['px-4 py-2 m-1 border-secondary_blue border-[1px] rounded', activeTab === tab.code ? ' text-white bg-secondary_blue font-bold' : 'text-blue-600 bg-white']"
        >
          {{ tab.title }}
        </button>
      </div>
  
      <!-- Tab Content -->
     
      <div class="p-2">
        <component 
          :is="getComponentForTab(activeTab)" 
          :form="form" 
          :responses="responses"
          :latestResponses="latestResponses"
        />
      </div>
    </div>
    </app-template>
  </template>
  
  <script>
  import QuestionStatistics from './QuestionStatistics.vue';
  import AllResponses from './AllResponses.vue';
  import Overview from './Overview.vue';
  
  export default {
    components:{
           QuestionStatistics,
           AllResponses,
           Overview
    },
    props: {
      form: {
        type: Array,
        required: true,
      },
      responses: {
        type: Array,
      },
      latestResponses:{
        type:Array,
      },
    },
    data() {
      return {
        translations: window.translations,

        tabs: [],
        activeTab: "overview",// Default active tab
      
      };
    },
    created() {
    this.tabs = [
      { title: this.translations.forms.overview_tab_title, code: "overview" },
      { title: this.translations.forms.question_statistics_tab_title, code: "question_statistics" },
      { title: this.translations.forms.all_responses_tab_title, code: "all_responses" },
    ];
   
  },
    methods: {
      getComponentForTab(tab) {
        switch (tab) {
          case "overview":
            return Overview;
          case "question_statistics":
            return QuestionStatistics;
          case "all_responses":
            return AllResponses;
          default:
            return Overview;
        }
      }
    },
   
  };
  </script>
  
  <style scoped>
  /* Optional: Add custom styling for hover or active state if needed */
  </style>
  