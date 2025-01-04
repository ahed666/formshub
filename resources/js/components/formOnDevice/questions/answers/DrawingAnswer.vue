<template>
    <div class="md:flex xs:grid xs:justify-center space-x-4 md:gap-0 xs:gap-1 items-center">
      <canvas
        ref="signatureCanvas"
        width="auto"
        height="250"
        class="border border-gray-200 rounded-lg bg-gray-100 touch-none "
        :class="{ 'cursor-crosshair': drawing }" 
        @mousedown="startDrawing"
        @mouseup="stopDrawing"
        @mousemove="draw"
        @touchstart="startDrawing"
        @touchend="stopDrawing"
        @touchmove="draw"
      ></canvas>
      <div class="flex justify-center items-center ">
        <button @click="clearCanvas" class="bg-red-500 rounded-lg text-white px-4 py-2">Clear</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      questionId: {
        type: String,
        required: true,
      },
      questionsWithAnswers: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        drawing: false,
        context: null,
        
      };
    },
    mounted() {
      const canvas = this.$refs.signatureCanvas;
      this.context = canvas.getContext('2d');
      this.context.lineWidth = 2;
      this.context.lineJoin = 'round';
      this.context.strokeStyle = 'black';
    },
    methods: {
      startDrawing(event) {
        event.preventDefault(); // Prevent scrolling

        console.log('drawing started');
        this.drawing = true;
        this.draw(event);
      },
      stopDrawing(event) {
        event.preventDefault(); // Prevent scrolling

        this.drawing = false;
        this.context.beginPath(); // Begin a new path after stopping the drawing
        this.saveSignature(); // Save the signature when stopping
      },
      draw(event) {
        if (!this.drawing) return;
  
        const rect = this.$refs.signatureCanvas.getBoundingClientRect();
        let x, y;
        if (event.touches) {
        // Handle touch events
        x = event.touches[0].clientX - rect.left;
        y = event.touches[0].clientY - rect.top;
        } else {
            // Handle mouse events
            x = event.clientX - rect.left;
            y = event.clientY - rect.top;
        }
  
        this.context.lineTo(x, y);
        this.context.stroke();
        this.context.beginPath();
        this.context.moveTo(x, y);
      },
      clearCanvas() {
        this.context.clearRect(0, 0, this.$refs.signatureCanvas.width, this.$refs.signatureCanvas.height);
        this.$emit('saveDrawing',null); 
      },
      saveSignature() {
        const dataURL = this.$refs.signatureCanvas.toDataURL('image/png');
        this.convertToBlob(dataURL).then((blob) => {
         this.$emit('saveDrawing',blob); // Save the blob to the corresponding question
        });
      },
      async convertToBlob(dataURL) {
        return fetch(dataURL)
          .then((res) => res.blob())
          .then((blob) => {
            return blob;
          });
      },
    },
  };
  </script>
  
  <style scoped>
  
  </style>
  