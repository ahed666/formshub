<template>
    <app-template>
        <headerpage>
            <ButtonComponent :btnClass="'bg-secondary_blue hover:bg-blue-700'" @click="showModal()"> Create One
            </ButtonComponent>
        </headerpage>
        <h1>{{ translations.titles.forms_title }}</h1>
        <div class="grid grid-cols-12 md:gap-2 xs:gap-1">
            <!-- template for each form -->
            <div class="md:col-span-4 xs:col-span-12 w-full bg-white   md:p-4 xs:p-2 border rounded" v-for="form in currentForms"
                :key="form.id">

                <FormCard class="grid" :form="form" />

                
            </div>
        </div>

        

        <ModalManager 

            v-if="isModalVisible"
            :userCanAdd="userCanAddForm"
            :errorMessage="errorAddMessage"
            :typeModal="'addform'"
            @close="hideModal" 
            

        />


    </app-template>
</template>
<script>
import ButtonComponent from "../actions/ButtonComponent.vue";
import ChangesFooter from '../ChangesFooter.vue';
import ModalManager from "../modals/ModalManager.vue";
import axios from "axios";
import FormCard from "./FormCard.vue";
export default {
    components: {
        ButtonComponent,
        ChangesFooter,
        ModalManager,
        FormCard,
    },
    props: {
        forms: {
            type: Array,
            required: true,
        },
        maxNumForms:{
            type: Number,
            required: true,

        }
    },
    data() {
        return {
            isModalVisible: false,
            userCanAddForm:false,
            errorAddMessage:null,
            currentForms: [],
            translations:window.translations,
        };
    },
    mounted() {
        this.currentForms = this.forms;
    },
    methods: {

       


       async showModal() {
            try {
                const response=await axios.get('/canusermakeaction/addform');
                console.log(response);
                this.userCanAddForm=response.data.result;
                this.errorAddMessage=response.data.resultmessage;
                this.isModalVisible = true;
                
            } catch (error) {
                
            }
           
            
        },
        hideModal() {
            this.isModalVisible = false;
        },

        // add form 
       

        
    },
};
</script>

<style>
li:hover input[type="checkbox"] {
    display: block;
}
</style>
