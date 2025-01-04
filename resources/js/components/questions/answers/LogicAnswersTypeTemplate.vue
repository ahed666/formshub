<!-- ModalTemplate.vue -->
<template>
   
     <div class="md:flex md:space-x-2 md:space-y-0 xs:grid xs:space-x-0 xs:space-y-2 rtl:space-x-reverse  justify-center">
        <div v-for="(answer,index) in Answers" class="border flex justify-between space-x-2 rtl:space-x-reverse items-center rounded-lg p-2">
        
              <input @change="changeAnswerText(index,$event)" type="text" name=""
               :value="answer.text[currentLang]" :id="answer.id">
              
      
              <!-- Image Dropdown -->
              <div class="relative">
                    <!-- Trigger: Display Current Image -->
                    <img 
                    :src="answer.image" 
                    class="w-8 h-8 rounded-full cursor-pointer" 
                    alt="Answer Image" 
                    @click="toggleDropdown(index)"
                    />

                    <!-- Dropdown Menu with Image Options -->
                    <div v-if="dropdownVisible[index]" class="absolute mt-2 right-0 bottom-4 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                    <ul>
                        <!-- Placeholder Option -->
                        <li class="p-2 text-gray-500">Choose new image</li>
                        
                        <!-- Image Options -->
                        <li v-for="(option, id) in ImagesOptions" :key="id" @click="selectImage(option.image, index)" class="flex items-center p-2 hover:bg-gray-100 cursor-pointer">
                        <img :src="option.image" class="w-8 h-8 rounded-full mr-2" alt="Option Image" />
                        <span>{{ option.title }}</span>
                        </li>
                    </ul>
                    </div>
              </div>
               
              
    </div>
     </div>
    
    
  </template>

  <script>

 
  export default {
    components:{
        
    },
    props: {
        answers:{
            type:Array,
            required:false,
        },
        currentLang:{
            type:String,
            required:false,
            default:'lang1',
        },
        
      
    },

    data(){
        return{


            
            // images for answers that user can add it to answer 
            ImagesOptions:[{image:'/images/icons/like.png',title:'Like'}
            ,{image:'/images/icons/dislike.png',title:'Dislike'}], 

            Answers:[],
            dropdownVisible: {}, // Controls the visibility of each dropdown

            // defult values for answers 
            predefinedAnswers: {
            'logic': [
            { id: '0', text: { lang1: 'Yes', lang2: 'Yes' }, image: null },
            { id: '1', text: { lang1: 'No', lang2: 'No' }, image: null }
            ]
            },
        }
        
    },
    watch: {
        answers: {
            immediate: true, // Trigger immediately
            handler(newVal) {
                   // If newVal is not empty, use it; otherwise, use predefined answers
                this.Answers = newVal && newVal.length > 0 ? newVal : this.predefinedAnswers['logic'];
                console.log(this.Answers,newVal );
                this.$emit('updateAnswers', this.Answers); // Emit updated answers to parent
            },
        },
        
    },
    methods:{

        changeAnswerText(index,event){
          this.Answers[index].text[this.currentLang]=event.target.value;
           this.$emit('updateAnswers',this.Answers);



        },
        // open or close dropdown menu of answer images
        toggleDropdown(index) {
      // Toggle the visibility of the dropdown for the current answer
      this.dropdownVisible[index]=!this.dropdownVisible[index];
    },


    // when user select image from dropdown menu
    selectImage(selectedImage, index) {
      // Update the answer's image with the selected option
      this.Answers[index].image = selectedImage;
      // Close the dropdown after selecting an image
      this.dropdownVisible[index]=false;
      
      this.$emit('updateAnswers',this.Answers);
    }
    },
   
        
}
  </script>

  <style scoped>
  /* Add any additional styling here */
  </style>
