<!-- FormLogo.vue -->
<template>
    <div class="grid justify-center items-center">
       
      <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse mb-1">
        <h1 class="text-md">{{ translations.forms.form_logo }}</h1>
      <!-- Upload button positioned at bottom-right -->
      

      </div>

      <!-- Display default image or user-uploaded image -->
       <div class="relative group rounded-lg  ">
          <div class="absolute w-full h-full hidden group-hover:flex  justify-center items-center bg-blue-200/85">
                
            <div class="w-16 h-16 rounded-full flex  justify-center items-center bg-white">
              <UploadSvg  :viewTitle="false" @click="triggerFileInput" />
          
              <!-- Hidden input field for file selection -->
              <input 
                  ref="fileInput" 
                  type="file" 
                  accept="image/*" 
                  class="hidden" 
                  @change="onImageSelected"
              />
            </div>
              
          </div>
          <img :src="imagePreview || defaultImage" alt="Form Image" class="w-32 h-32 object-contain border-[1px]  rounded" />
       </div>
  
    </div>
  </template>
  
  <script>
  import UploadSvg from '../svgs/Upload.vue';
  export default {
    components:{UploadSvg},
    props: {    
      defaultImage: {
        type: String,
        default: '/images/logo/form/default-form-logo.png',  // Placeholder image URL
      }
    },
    data() {
      return {
        imagePreview: null,  // Stores the selected image preview
        translations: window.translations,

      };
    },
    methods: {
      triggerFileInput() {
        this.$refs.fileInput.click();  // Simulates a click on the hidden file input
      },
      onImageSelected(event) {
        const file = event.target.files[0];
        if (file) {
          this.imagePreview = URL.createObjectURL(file);  // Sets the preview of the selected image
          this.$emit('image-selected', file);  // Emits the selected image to the parent component
        }
      }
    }
  };
  </script>
  
  <style scoped>
  /* Style for the image container and button */
  button {
    cursor: pointer;
  }
  </style>
  