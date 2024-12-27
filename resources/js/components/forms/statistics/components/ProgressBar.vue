<template>
    <div class="w-full bg-gray-200 rounded-full h-3">
      <div
        class="h-full rounded-full transition-all duration-300"
        :class="barColor"
        :style="{ width: computedPercent }"
      ></div>
    </div>
  </template>
  
  <script>
  export default {
    name: "ProgressBar",
    props: {
      percent: {
        type: Number,
        required: true,
        default: 0, // Default percentage is 0
      },
      color: {
        type: String,
        required:false,
        default: "bg-secondary_blue", // Default color of the progress bar
      },
    },
    data(){
      return{
        translations: window.translations,
      }
    },
    computed: {
      computedPercent() {
        return `${Math.min(Math.max(this.percent, 0), 100)}%`; // Clamps value between 0 and 100%
      },
      barColor() {
        return this.color; // Allows setting custom color
      },
    },
  };
  </script>
  
  <style scoped></style>
  