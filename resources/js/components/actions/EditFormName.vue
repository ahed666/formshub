<!-- EditAction.vue -->
<template>
    <label for="name" class="text-md"> Form Name</label>
    <div  v-if="editMode" class="flex space-x-4  rtl:space-x-reverse items-center">
          <input ref="nameInput" maxlength="30" name="name" type="text" v-model="name"  :value="name" class="p-2 rounded focus:border-y-secondary_blue border-[1px]
          2xl:w-1/2 xl:w-1/2 lg:w-full md:w-full xs:w-full overflow-hidden">
          <ButtonComponent :btnClass="'bg-secondary_blue hover:bg-blue-700'" :handleClick="edit">
                    <span>{{ translations.buttons.save }}</span>
            </ButtonComponent>
    </div>
    <div v-else class="flex space-x-4 rtl:space-x-reverse  items-center">
        <span class="p-2 bg-white 2xl:w-1/2 xl:w-1/2 lg:w-full md:w-full xs:w-full overflow-hidden ">{{name}}</span>
        <EditSvg @click="enableEdit" />
    </div>


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
    formName:{
        type:String,
        required:true,
    },
    slug:{
        type:String,
        required:true,
    },
},
data() {
    return {
        editMode:false,
        name:'',
        translations: window.translations,

    }
},
mounted() {
    this.name = this.formName;
},
methods: {
    enableEdit(){
      this.editMode=true;
      this.$nextTick(() => {
                this.$refs.nameInput.focus();
            });
    },
     // show alert warning or success
     showAlert(type, title) {
            this.$swal.fire({
                position: 'top-end',
                icon: type,
                title: title,
                showConfirmButton: false,
                timer: 1500
            });
        },
    edit() {

        if(this.name==''){
            this.$refs.nameInput.classList.add('border-secondary_red');
        }

       this.$emit('editFormName',this.name);
       this.editMode=false;
  }
}
}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
