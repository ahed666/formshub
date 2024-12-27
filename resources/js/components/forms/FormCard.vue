<template>
    <label class=" ">

        <div class="flex flex-wrap justify-between items-center" :class="{'max-w-[50%]':!viewOptions}">

            <!-- name -->
             <div class="overflow-hidden  " :class="{'max-w-[50%]':viewOptions}" >
                <h1 :title="form.name" 
                class="ml-2 font-bold text-lg  whitespace-nowrap " >{{ form.name }}</h1>
             </div>
            

            <!-- options for form -->
            <div v-if="viewOptions" class="flex items-center space-x-2 rtl:space-x-reverse">
                <!-- go to statistics -->

                <a class="bg-secondary_blue  w-20 text-white text-sm rounded        p-1 h-auto  text-center hover:cursor-pointer
                    ease-in delay-100  hover:-translate-z-1 hover:scale-[1.1]  duration-200   disabled:bg-gray-400 " @click.prevent="viewStatistics(form.slug)" @click.sto>
                    {{ translations.buttons.view_statistics }}
                </a>
                <!-- edit form -->
                <EditAction @edit="EditForm(form)" @click.stop />
                <!-- delete form -->
                <DeleteAction :button-text="translations.buttons.button_delete_title"
                 :confirm-text="translations.forms.confirm_delete_message"
                    :confirm-button-text="translations.buttons.confirm_delete_button_title" 
                    :cancel-button-text="translations.buttons.cancel"
                    :delete-action="() => deleteForm(form.slug)" />
            </div>

        </div>

        <div class="flex justify-center  items-center space-x-2 rtl:space-x-reverse " :class="{ 'md:mt-4 xs:mt-2 space-x-8 rtl:space-x-reverse': viewOptions }">
            <!-- num of question -->

            <Counter :count="form.questions_count" :unit="[translations.forms.questions_unit, translations.forms.question_single_unit]"
                :zeroCountText="translations.forms.questions_zero_unit"></Counter>

            <!-- num of responses -->
            <Counter :count="form.responses_count" :unit="[translations.forms.responses_unit,translations.forms.response_single_unit ]"
                :zeroCountText="translations.forms.responses_zero_unit"></Counter>




            

        </div>
    </label>
</template>

<script>

import EditAction from '../actions/Edit.vue';
import DeleteAction from '../actions/Delete.vue';
import Counter from "./Counter.vue";
import axios from "axios";
import { MessageMixin } from '../mixins/MessageMixin';
import ButtonComponent from '../actions/ButtonComponent.vue';
export default {
    mixins: [MessageMixin],
    components: { EditAction, DeleteAction, Counter,ButtonComponent },
    props: {
        form: { type: Object },
        viewOptions: { type: Boolean, default: true }
    },
    data(){
        return{
            translations: window.translations,

        }
    },
    methods: {

        // edit form routing
        EditForm(form) {
            window.location.href = `/editform/${form.slug}`;

        },

        // view statistics for form
        viewStatistics(slug) {
            console.log(slug);
            window.location.href = `/forms/statistics/${slug}`;
        },
        // delete form by slug
        deleteForm(slug) {
            // Handle the deletion of selected forms

            // Example: Send a request to delete the selected forms
            axios.delete(`/deleteform/${slug}`).then(response => {
                // Handle success
                this.currentForms = this.currentForms.filter(form => form.slug !== slug);


                this.showAlert('success', this.translations.forms.delete_form_success);


            }).catch(error => {
                console.error('Error deleting forms:', error);
            });
        },


    }
}
</script>

<style lang="scss" scoped></style>