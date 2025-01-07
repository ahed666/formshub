<template>
  <div class="h-full flex flex-col overflow-hidden bg-gray-100">
    <!-- First Row -->
    <div class="grid md:grid-cols-4 gap-2 mb-2 ">
      <div class="bg-white md:col-span-3  rounded-lg shadow p-4">
        <Info :info="data.info" />
      </div>
      <div class="bg-white rounded-lg shadow p-4">
        <SubscriptionBox :subscription="data.subscription" />
      </div>
    </div>

    <!-- Second Row -->
    <div class="grid md:grid-cols-2 gap-2 my-2  ">
      <div class="bg-white rounded-lg shadow p-4 overflow-auto">
        <h1 class="text-lg font-bold text-center mb-4">{{ translations.dashboard.devices_section_title }}</h1>
        <DevicesSection :devices="data.devices" />
      </div>
      <div class="bg-white rounded-lg shadow p-4 overflow-auto">
        <h1 class="text-lg font-bold text-center mb-4">{{translations.dashboard.forms_section_title}}</h1>
        <FormsSection :forms="data.forms" />
      </div>
    </div>

    <!-- Third Row -->
    <div class="grid md:grid-cols-2 gap-2 ">
      <!-- Responses Per Form Chart -->
      <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-bold text-center mb-4">{{ translations.dashboard.responsesperform_section_title }}</h2>
        <Chart
          :chartHeight="'md:h-[450px] sm:h-[350px] xs:h-[300px] '"
          :radius="'50%'"
          :center="['50%', '40%']"
          :legend="data.responsesPerForm.legend"
          :data="data.responsesPerForm.data"
        />
      </div>

      <!-- Responses Per Device Chart -->
      <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-bold text-center mb-4">{{translations.dashboard.responsesperdevice_section_title}}</h2>
        <Chart
          :chartHeight="'h-[450px]'"
          :radius="'50%'"
          :center="['50%', '40%']"
          :legend="data.responsesPerDevice.legend"
          :data="data.responsesPerDevice.data"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Chart from '../forms/statistics/components/Chart.vue';
import DevicesSection from './DevicesSection.vue';
import FormsSection from './FormsSection.vue';
import Info from './Info.vue';
import SubscriptionBox from './SubscriptionBox.vue';

export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  data(){
    return{
      translations:window.translations,
    }
  },
  components: { Info, SubscriptionBox, DevicesSection, FormsSection, Chart },
};
</script>

<style scoped>
/* Responsive improvements */
.grid {
  display: grid;
  gap: 1rem;
}

.shadow {
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
