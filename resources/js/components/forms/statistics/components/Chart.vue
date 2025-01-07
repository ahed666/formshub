<template>
    <div class=" flex-wrap flex items-center flex-col bg-white p-1 rounded-lg">

        <v-chart :class="[' ',chartHeight]" v-if="option" :option="option" />
        

    </div>
</template>

<script>
import { use } from "echarts/core";
import { CanvasRenderer } from "echarts/renderers";
import { PieChart } from "echarts/charts";
import {
    TitleComponent,
    TooltipComponent,
    LegendComponent
} from "echarts/components";
import VChart, { THEME_KEY } from "vue-echarts";
import { ref, watch, defineProps } from "vue";

use([
    CanvasRenderer,
    PieChart,
    TitleComponent,
    TooltipComponent,
    LegendComponent
]);

export default {
    components: {
        VChart,
    },
    props: {
        data: {
            type: Array,
            required: true
        },
        legend: {
            type: Array,
            required: true
        },
        title:{
            type:String,
            default:null,
        },
        radius:{
            type:String,
            default:"60%",
        },
        center:{
            type:Array,
            default:["50%", "30%"],
        },
        chartHeight:{
            type:String,
            default:'h-96',
        }
    },

    data() {
        return {
            translations: window.translations,

            option: ref({
                title: {
                    text:this.title,
                    left: "center",
                    show: false,
                },
                tooltip: {
                    trigger: "item",
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    show: true,
                    orient: "horizontal",
                    left: "left",
                    data: this.legend,
                },
                series: [
                    {

                        type: "pie",
                        radius: this.radius,
                        center: this.center,
                        data: this.data,
                        label: {            // Label configuration for each slice
                            show: false,     // Display label on each slice
                            position: 'center',
                            formatter: "{b} ({d}%)" // Format to show label name and percentage
                        },
                        labelLine: {         // Disable label lines
                            show: false,

                        },
                        emphasis: {
                            label: {
                                show: false,
                                fontSize: 40,
                                fontWeight: 'bold'
                            },
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: "rgba(0, 0, 0, 0.5)"
                            }
                        }
                    }
                ]
            }),
        }
    },

    watch: {
        data: {
            immediate: true, // Trigger immediately
            handler(newData) {
                console.log('new data', newData);

                this.updateOptionSeries(newData);
            },
        },
        legend: {
            immediate: true, // Trigger immediately

            handler(newLegend) {
                console.log('new legend', newLegend);
                this.updateOptionLegend(newLegend);
            }
        }
    },
    methods: {
        updateOptionSeries(newData) {
            this.option.series[0].data = newData;

        },
        updateOptionLegend(newLegend) {
            this.option.legend.data = newLegend;
        },
    }

}





</script>

<style scoped></style>