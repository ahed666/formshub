<template>
    <modal-template  
    @close="closeModal" 
    :showButtons="false" 
    :title="'Warning'">
    <Error  :error="errorMessage" />

    </modal-template>
</template>

<script>
import Error from '../actions/Error.vue';
import ModalTemplate from '../ModalTemplate.vue';

    export default {
components:{Error,ModalTemplate},
        props:{
            errorMessage:{type:String},
           
        },
        methods:{
            closeModal(){
                this.$emit('close');
            }
        }
        
    }
</script>

<style lang="scss" scoped>

</style>