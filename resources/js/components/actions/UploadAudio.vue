<template>

    <div :class="{ 'animate-pulse': isLoading }" class="flex space-x-4 items-center ">


        <!-- if there is audio -->
        <div class=" 2xl:w-1/2 xl:w-1/2 lg:w-full md:w-full xs:w-full" v-if="currentAudioPath">
            <audio class="bg-white p-2 rounded-full w-full" ref="audioPlayer" controls>
                <source :src="currentAudioPath" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>

        <!-- if not -->
        <div v-else>
            <span>No audio file available</span>
        </div>

        <!-- Upload button -->
        <label
            class="flex flex-col items-center justify-center m-0">
            <!-- <div class="flex flex-col items-center justify-center">
                Add New
            </div> -->
            <UploadSvg />

            <input ref="fileInput" id="dropzone-file" type="file" class="hidden" @change="onFileChange"
                accept="audio/*" />



        </label>

    </div>
</template>

<script>
import axios from 'axios';
import UploadSvg from '../svgs/Upload.vue';
export default {
    name: 'UploadAudio',
    components:
    {
        UploadSvg,
    },
    props: {

        audioPath: {
            type: String,
            required: true
        },
        playlistId: {
            type: String,
            required: true

        },




    },
    data() {
        return {
            currentAudioPath: null,
            file: null,
            isLoading: false,
            translations: window.translations,

        };
    },
    mounted() {
        this.currentAudioPath = this.audioPath;
    },
    methods: {
        // to reset file input
        resetFileInput() {
            // Reset the file input field
            this.$refs.fileInput.value = null;
            this.file = null;
        },


        onFileChange(event) {
            this.file = event.target.files[0];

            this.uploadAudio();


        },
        // show alert warning or success
        showAlert(type, title) {
            this.$swal.fire({
                position: 'top-end',
                icon: type,
                title: title,
                showConfirmButton: false,
                timer: 1500
            });
        },


        //upload audio file
        async uploadAudio() {
            this.isLoading = true;
            const formData = new FormData();
            formData.append('audio', this.file);
            formData.append('playlistId',this.playlistId)


            const response = await axios.post('/uploadplaylistaudio',
                formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {


                this.showAlert('success', 'Audio uploaded successfully');

                this.currentAudioPath = response.data.path; // Update the audioPath with the uploaded file path
                console.log(this.currentAudioPath, response.data);
                this.$emit('uploaded',this.currentAudioPath);
                this.$nextTick(() => {
                  this.$refs.audioPlayer.load();
                //   this.$refs.audioPlayer.play();
                });
                this.isLoading = false;
            }).catch(error => {
                this.isLoading = false;
                this.showAlert('error', 'Failed to upload audio');
                console.log(error);
            });

        },
    },
};
</script>

<style scoped></style>
