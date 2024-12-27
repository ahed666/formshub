<!-- ButtonComponent.vue -->
<template>
    <button :disabled="disabled" class=" 
    text-white text-sm rounded        p-1 h-auto  text-center hover:cursor-pointer
    ease-in delay-100  hover:-translate-z-1 hover:scale-[1.1]  duration-200   disabled:bg-gray-400   " :class="btnClass" @click="handleClick">
      <slot></slot>
    </button>
  </template>

  <script>
  export default {
    props: {
      buttonClass: {
        type: String,
        default: 'btn-primary'
      },
      btnClass: {
      type: String,
      default: '',
    },
    disabled:{
        type: Boolean,
        default: false

    },
      handleClick: {
        type: Function,
        default: () => {}
      }
    },
    data(){
      return {
        translations: window.translations,
      }
    },
  };
  </script>

  <style scoped>

  /* Add more styles as needed */
  </style>
