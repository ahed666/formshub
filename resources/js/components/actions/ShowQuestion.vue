<template>
    <div  :class="class"
        class=" left-[35%] bottom-[35%] opacity-75 border-[1px] border-secondary_blue flex justify-center items-center absolute z-1000 rounded-full bg-white ">
        <div @click.stop="openShowPopup(media)"
            class=" cursor-pointer flex justify-center items-center  rounded-lg p-1 ">
            <!-- video svg -->

            <VideoSvg v-if="isVideo(media.type)" />


            <!-- image svg -->

            <ImageSvg v-if="isImage(media.type)" />


        </div>
    </div>

    <div v-if="showPopup" class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-75 z-50">
            <div class="bg-white rounded-lg p-4 max-w-[500px] max-h-[500px] overflow-y-auto">
                <video v-if="isVideo(mediaToShow.type)" controls autoplay class="w-full">
                    <source :src="mediaToShow.path" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <img v-else-if="isImage(mediaToShow.type)" :src="mediaToShow.path" class="w-full object-contain" alt="">
                <button @click="closeShowPopup" class="absolute top-2 right-2 text-white focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>


</template>
<script>

import VideoSvg from '../svgs/Video.vue';
import ImageSvg from '../svgs/Image.vue';

export default {
    components: {
        VideoSvg,
        ImageSvg,
    },
    props: {
        media: {
            type: Object,
            required: true,
        },
        class:{
            type: String,
            default: 'max-w-16 max-h-16 h-16 w-16',
        }



    },
    data(){
        return{
            translations: window.translations,

        }
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
        document.addEventListener('keydown', this.handleEscapePress);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
        document.removeEventListener('keydown', this.handleEscapePress);
    },
    data() {
        return {
            showPopup: false,
            mediaToShow: null,
        };
    },



    methods: {

        openShowPopup(mediaToShow) {
            this.mediaToShow = mediaToShow;
            this.showPopup = true;

        },
        closeShowPopup() {
            this.mediaToShow = null;
            this.showPopup = false;
        },

        // to close the model when click outisde it
        handleClickOutside(event) {

            if (this.showPopup && !this.$el.contains(event.target)) {
                this.closeShowPopup();
            }
        },
         // to close the model when click esc button
        handleEscapePress(event) {

            if (event.key === 'Escape' && this.showPopup) {
                this.closeShowPopup();
            }
        },
        // check if item media is video or image
        isImage(mimeType) {
            return mimeType.startsWith('image/');
        },
        isVideo(mimeType) {
            return mimeType.startsWith('video/');
        },








    },




};
</script>
