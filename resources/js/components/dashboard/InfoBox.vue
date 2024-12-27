<template>
    <div class=" p-1  bg-white  border-[1px] shadow-xl border-gray-100">
        <!-- Render SVG dynamically -->
        <div class="flex justify-center space-x-2  rtl:space-x-reverse items-center mb-2">
            <div class="flex justify-center items-center rounded-full  md:w-12 xs:w-6 md:h-12 xs:h-6 bg-primary_blue">
                <component :is="svgComponent" class="md:w-6 md:h-6 xs:w-3 xs:h-3 text-secondary_blue" :color="'#1277D1'" />
            </div>
            <h1 class="md:text-sm xs:text-xs"> {{ infoItem['title'] }}</h1>
        </div>
        <div class="bottom-0 flex justify-center  items-center ">
            
            <h1 class="md:text-3xl xs:text-lg text-secondary_blue font-bold text-center">{{
                getCountText(infoItem['count']) }}</h1>
        </div>
       
        
    </div>
</template>

<script>
import ResponseSvg from '../svgs/Response.vue';
import DeviceSvg from '../svgs/Device.vue';
import FormSvg from '../svgs/Form.vue';
export default {
    components: { ResponseSvg, DeviceSvg, FormSvg },
    props: {
        infoItem: { type: Array },
    },
    computed: {
        svgComponent() {
            // Map titles to their respective SVG components
            const svgMap = {
                Responses: 'ResponseSvg',
                Devices: 'DeviceSvg',
                Forms: 'FormSvg',
                // Add more mappings here
            };
            return svgMap[this.infoItem.svg] || null;
        }
        },

        methods: {
             getCountText(count){
                if (count >= 1000000) {
                    // Divide by 1M and floor to one decimal place
                    return '+' + Math.floor((count / 1000000) * 10) / 10 + 'M';
                } else if (count >= 1000) {
                    // Divide by 1K and floor to one decimal place
                    return '+' + Math.floor((count / 1000) * 10) / 10 + 'K';
                } else {
                    // Return the original count if below 1K
                    return count.toString();
                }
            }
        }
    

}
</script>

<style lang="scss" scoped></style>