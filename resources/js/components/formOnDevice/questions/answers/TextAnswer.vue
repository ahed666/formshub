<template>

    <div v-if="questionType != null">
        <div v-if="questionType.type_text === 'number'" class="p-2 w-full">

                <PhoneNumberInput @update:error="onErrorTyping"  @update:phoneNumber="handlePhoneNumberUpdate" />
            
        </div>

        <div v-else class="p-2 w-full">
            <div class="my-1 min-h-5 flex justify-center items-center">
            <span v-if="error" class="text-red-600 text-sm">{{ error }}</span>
            </div>
            <!-- Fallback for other input types -->
            <div  class="grid  items-center mb-2">

                <input ref="textInput" id="textinput" name="textinput"
                 type="text" v-model="inputText" placeholder="Type here..."
                    class="border border-gray-300 p-2 w-full " />
            </div>
            

            <!-- keyboard -->
            <TextKeyboard :questionType="questionType.type_text" @key-press="addToInput" @backspace="removeLastCharacter"
                @clear-input="clearInput" />
        </div>
    </div>
</template>

<script>
import { AnswerMixin } from '../../../mixins/AnswerMixin';
import TextKeyboard from './TextKeyboard.vue';
import PhoneNumberInput from './PhoneNumberInput.vue';
import NumberKeyboard from './NumberKeyboard.vue';

export default {
    mixins: [AnswerMixin], // Use the mixin in the parent component

    components: {
        TextKeyboard,PhoneNumberInput,NumberKeyboard
    },
    props: {

        questionType: {
            type: Object,
            Required: true,
        },
        questionId: {
            type: String,

        },
        questionsWithAnswers: { // Receive questionsWithAnswers as a prop
            type: Object,
            required: true,
        },


    },
    data() {
        return {
            inputText: '',
            error: null,
            
            
        }
    },
    methods: {

        addToInput(key) {
            this.inputText += key;

            this.handleTypeText();

        },
        
        removeLastCharacter() {
            this.inputText = this.inputText.slice(0, -1);
            this.handleTypeText();
        },
        clearInput() {
            this.inputText = '';
            this.$emit('typingAnswer', this.inputText);
        },
        clearInputElement(){
            this.inputText = '';
        },
        handleTypeText() {
            if (this.questionType.type_text === "email") {
                this.validateEmail(this.inputText) ? this.$emit('typingAnswer', this.inputText) : '';

            }
            
            else
                this.$emit('typingAnswer', this.inputText);
        },
        
        handlePhoneNumberUpdate(phoneNumber) {
        // this.inputText = phoneNumber; // Update the inputText with the complete phone number
        this.$emit('typingAnswer', phoneNumber); // Emit the complete phone number
        },
        validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(email)) {
                this.error = 'Invalid email address';
                return false;
            } else {
                this.error = null;
                return true;
            }

        },

        onErrorTyping(){
            this.$emit('onErrorTyping');
        },

    },
}
</script>

<style lang="scss" scoped></style>