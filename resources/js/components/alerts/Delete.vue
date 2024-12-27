<template>

</template>

  <script>

import DeleteSvg from '../svgs/Delete.vue';
import { data } from 'autoprefixer';
import Swal from 'sweetalert2';

  export default {
    components:{
        DeleteSvg,
    },
    name: 'Delete',
    data(){
      return{
        translations: window.translations,

      }
    },
    props: {
      buttonText: {
        type: String,
        required: true,
        default: 'Delete'
      },
      confirmText: {
        type: String,
        required: true,
        default: 'Are you sure you want to delete this item?'
      },
      confirmButtonText: {
        type: String,
        required: true,
        default: 'Yes, delete it!'
      },
      cancelButtonText: {
        type: String,
        required: true,
        default: 'Cancel'
      },
      deleteAction: {
        type: Function,
        required: true
      }
    },
    mounted() {
        this.showDeleteConfirm();
    },
    methods: {
      showDeleteConfirm() {
        Swal.fire({
          title: this.confirmText,
          showCancelButton: true,
          confirmButtonText: this.confirmButtonText,
          cancelButtonText: this.cancelButtonText,
          icon: 'warning'
        }).then((result) => {
          if (result.isConfirmed) {
            console.log('accept');
            this.deleteAction();
          }
        });
      }
    }
  };
  </script>

  <style scoped>
  /* Add any custom styles here */
  </style>
