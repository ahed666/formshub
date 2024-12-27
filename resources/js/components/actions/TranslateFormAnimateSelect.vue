<!-- EditAction.vue -->
<template>
 <label for="name" class="text-md"> Translate Animation</label>
    <div   class="flex xs:space-x-8 rtl:space-x-reverse  2xl:justify-between md:justify-between  items-center">
        <select @change="edit" ref="animateSelect" v-model="selectedAnimation" class="p-2 2xl:w-1/2 md:w-1/2 xs:w-full border rounded">
            <option v-for="animation in animations" :key="animation.key" :value="animation.key">
          {{ animation.value }}
        </option>
      </select>
          <!-- <ButtonComponent :btnClass="'bg-secondary_blue hover:bg-blue-700'" :handleClick="edit">
                    <span>save</span>
            </ButtonComponent> -->
    </div>
    <!-- <div v-else class="flex xs:space-x-8  2xl:justify-between md:justify-between items-center">
        <span class="p-2 bg-white  w-9/12">{{currentAnimationValue }}</span>
        <EditSvg @click="enableEdit" />
    </div> -->

</template>

<script>
import EditSvg from '../svgs/Edit.vue';
import ButtonComponent from './ButtonComponent.vue';
export default {
name: 'EditFormName',
components:{
    EditSvg,
    ButtonComponent,
},
props:{
    currentAnimation:{
        type:String,
        required:true,
    },

},
data() {
    return {
        editMode:false,
        selectedAnimation: '', // Store selected animation
        translations: window.translations,

        animations: [
        { key: 'fadeIn', value: 'fade In' },
        { key: 'fadeInDown', value: 'fade In Down' },
        { key: 'bounceIn', value: 'bounce In' },
        { key: 'bounceIn', value: 'bounce In' },
        { key: 'backInUp', value: 'back In Up' },
    ],
    }
},
computed: {
    currentAnimationValue() {
      const animation = this.animations.find(anim => anim.key === this.currentAnimation);
      return animation ? animation.value : '';
    }
  },
mounted() {
    this.selectedAnimation = this.currentAnimation;
},
watch: {
    currentAnimation(newAnimation) {
      this.selectedAnimation = newAnimation;
    },
    },

methods: {

    edit() {

        this.$emit('update',  this.selectedAnimation );
      this.editMode = false;


  }
}
}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
