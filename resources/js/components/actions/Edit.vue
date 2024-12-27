<!-- EditAction.vue -->
<template>
        <EditSvg @click="edit" />

  </template>

  <script>
  import EditSvg from '../svgs/Edit.vue';
  
  export default {
    name: 'EditAction',
    components:{
        EditSvg,
    },
    data(){
      return{
        translations: window.translations,

      }
    },
    methods: {
        edit() {
       
        this.$emit('edit');
      }
    }
  }
  </script>

  <style scoped>
  /* Add any necessary styles here */
  </style>
