<!-- Modal.vue -->
<template>
  <modal-template @submit="saveForm" :disableButtons="isSaving" 
    @close="closeModal"  :title=" translations.titles.add_form">


    <div class="mt-4 grid grid-cols-12 gap-2 col-span-12">
      <div class="col-span-8">
        <input v-model="formName" type="text" maxlength="30" class="w-full border rounded px-3 py-2 my-1"
          placeholder="Form Name">
        <div v-if="validationMessage" class="text-red-500 text-sm mt-1">{{ validationMessage }}</div>
      </div>
      <div class="col-span-4">
        <!-- Image Upload Input -->
        <FormLogo @image-selected="onImageSelected"></FormLogo>

      </div>

    </div>

    





  </modal-template>

</template>

<script>
import ModalTemplate from '../ModalTemplate.vue';
import FormLogo from '../forms/FormLogo.vue';
import { MessageMixin } from '../mixins/MessageMixin';
export default {
  mixins: [MessageMixin], 

  name: 'AddFormModal',
  components: {
    ModalTemplate,
    FormLogo,
    

  },
  props: {
   
    canAdd: {
      type: Boolean,
      required: true,
      default:false,
    },
    errorAddMessage:{
      tpe:String,
      default:'error!!',
    }
    
  },
  data() {
    return {
      formName: '',
      validationMessage: '',
      formImage: null,  // Holds the selected image file
      imagePreview: null, // Holds the image preview URL
      isSaving: false,
      translations:window.translations,

    };

  },
  methods: {

    // close modal
    closeModal() {
      this.$emit('close');
    },

    // Handle image selection
    onImageSelected(imageFile) {
      this.formImage = imageFile;  // Store the selected image file
    },
    async saveForm() {
     
      if (!this.formName) {
        this.validationMessage = 'Form name is required';
      
        return;
      }
  

      try {
        this.isSaving = true;
        const formData = new FormData();
        formData.append('name', this.formName);
        formData.append('image', this.formImage);

        const response = await axios.post("/createform",
          formData
          , {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }).then(response => {
            if(response.data.can)
            window.location.href = response.data.redirect_url;

            else
            {this.showAlert('error', response.data.message);
            this.$emit('close');}


            // window.location.href = `/editform/${response.data.form.slug}`;
            
          }).catch(error => {

            this.showAlert('error', 'Failed to add form');
            
          });
           this.$emit('close');
          this.isSaving = false;
        // Handle success, perhaps clear the form or show a success message
      } catch (error) {
        console.error("Error adding form:", error);
        this.isSaving = false;
      }

      
    },


  }
};
</script>

<style scoped>
/* Add any scoped styles here */
</style>
