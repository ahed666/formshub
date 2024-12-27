<!-- EditAction.vue -->
<template>

           <ButtonComponent :btnClass="'bg-secondary_blue'" :handleClick="add">
             {{translations.buttons.add_question}}
           </ButtonComponent>
</template>

<script>
import ButtonComponent from './ButtonComponent.vue';



export default {
name: 'AddQuestion',
components:{
  ButtonComponent
},
data(){
  return {
    translations: window.translations,

  }
},
methods: {
    add() {
      this.$emit('addquestion');
  }
}
}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
