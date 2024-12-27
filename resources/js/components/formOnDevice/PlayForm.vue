<template>
    <div v-if="form != null">
        <SubmitError v-if="error == true" :message="errorMessage" />
        <StartPage v-if="currentStep == 1" @startform="startForm" :formTranslations="form.translations" />
        <QuestionsTemplate v-else-if="currentStep == 2" @finish="finishForm" :currentTranslation="response.translation"
            :questions="form.questions" @cancel="cancelForm" />
        <EndPage v-if="currentStep == 3" @startform="startForm" :currentTranslation="response.translation" />
    </div>

    <div>

    </div>
</template>

<script>
import StartPage from './StartPage.vue';
import QuestionsTemplate from './QuestionsTemplate.vue';
import EndPage from './EndPage.vue';
import SubmitError from './SubmitError.vue';
export default {

    components: {
        StartPage,
        QuestionsTemplate,
        EndPage,
        SubmitError,


    },
    props: {
        form: {
            type: Object,
            Required: true,
        },
        device: {
            type: Object,
            Required:true,
        },
        step: {
            type: Number,
            Required: true,
            default: 1,
        },
    },
    
    data() {
        return {
            currentStep: this.step,
            error: false,
            errorMessage: null,
            response: { form: this.form, device: this.device, translation: {}, questionsWithAnswers: [] },


        }
    },
    watch: {
        // watch for duration change
        step: {
            
            handler(newVal) {
                console.log(newVal);
                this.currentStep = newVal;


            }
        },
        // device: {
        //     immediate: true, // Trigger immediately
        //     handler(newVal) {
        //         console.log('device:',newVal);
        //         this.deviceinfo = newVal;


        //     }
        // }


    },
    methods: {
        startForm(translation) {
            this.response.translation = translation;
            this.currentStep = 2;
            this.$emit('updateCurrentStep', this.currentStep);
            console.log(this.response, this.currentStep);

        },
        cancelForm(){
            this.currentStep = 1;
            this.$emit('updateCurrentStep', this.currentStep);
        },
       async finishForm(questionsWithAnswers) {
            this.response.questionsWithAnswers = questionsWithAnswers;
            this.response.device=this.device;

            if (this.checkOnlineStatus) {
                try {

                    const response = await axios.post('/api/submit-response', this.response,
                        { headers: { 'Content-Type': 'multipart/form-data' } }
                    )
                        .then(response => {
                            if (response.status === 200) {
                                // If the response status is 200, move to the next step
                                this.currentStep = 3;
                            }
                            // may be the subscription for user was expired then refresh form to update the status of device
                            else if(response.status === 403)
                            {
                                this.error=true;
                                this.errorMessage = error.response ? error.response.data.message : 'An unexpected error occurred';
                            }
                        })
                        .catch(error => {
                            // If there's an error, show the error component and set the error message
                            this.error=true;
                            this.errorMessage = error.response ? error.response.data.message : 'An unexpected error occurred';
                            
                        });
                    ;
                   

                } catch (error) {
                    this.error=true;
                    this.errorMessage = error;
                }
            }

        },

        checkOnlineStatus() {
            this.isOnline = navigator.onLine;

            if (!this.isOnline) {
                this.error = true;
                this.errorMessage = "There is no internet connection";
                return false;

            }
            else return true;
        }


    },

}
</script>

<style lang="scss" scoped></style>