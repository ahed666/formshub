<template>
    <div>
      <DeleteSvg @click="showDeleteConfirm" />
    </div>
  </template>

  <script>

import DeleteSvg from '../svgs/Delete.vue';
import { data } from 'autoprefixer';
import Swal from 'sweetalert2';
import { MessageMixin } from '../mixins/MessageMixin';
  export default {
    mixins: [MessageMixin], 
    components:{
        DeleteSvg,
    },
    name: 'Delete',
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
    data(){
      return{
        translations: window.translations,

      }
    },
    methods: {
      showDeleteConfirm() {
        // Swal.fire({
        //   title: this.confirmText,
        //   showCancelButton: true,
        //   confirmButtonText: this.confirmButtonText,
        //   cancelButtonText: this.cancelButtonText,
        //   icon: 'warning'
        // }).then((result) => {
        //   if (result.isConfirmed) {
        //     this.deleteAction();
        //   }
        // });
        this.showWarning(this.deleteAction,this.confirmText,this.confirmButtonText,this.cancelButtonText);

      }
    }
  };
  </script>

  <style scoped>
  /* Add any custom styles here */
  </style>
