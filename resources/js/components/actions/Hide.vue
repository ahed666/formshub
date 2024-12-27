<!-- EditAction.vue -->
<template>
    <Hidesvg @click="edit" :statusHide="statusHide" />

</template>

<script>
import Hidesvg from '../svgs/Hide.vue';

export default {
name: 'EditAction',
components:{
    Hidesvg,
},
props:{
    statusHide:{
       type:Boolean,
       Required:false,
   },
   itemId:{
       type:Number,
       Required:true,
   },

},
data(){
return{
    translations: window.translations,

}
},
methods: {
    edit() {
   
    this.$emit('hide',this.itemId);
  }
}
}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
