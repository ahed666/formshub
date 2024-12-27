<template>
    <div class="relative inline-block text-left">
      
      <MenuSvg class="w-8 h-8 hover:cursor-pointer" @click="toggleDropdown" />
  
      <div v-if="isOpen" class="absolute mt-2 w-56 bg-gray-100 border border-gray-200 rounded-md shadow-lg">
        <ul v-if="options.length>0&&!loading" class="py-1">
          <li  v-for="(option, index) in options"  :key="index">
            <!-- toggle switch -->
            <div v-if="option.type === 'toggle_switch'&&option.view" class="px-4 py-2 flex space-x-2 rtl:space-x-reverse items-center">
                <ToggleSwitch @toggle="emitAction(option.action)" :value="option.value" />
                <h1>{{ option.value?option.labelTrue:option.labelFalse }}</h1>
            </div>
            <!-- button -->
            <div v-if="option.type === 'button'&&option.view" class="px-4 py-2">
              <button @click="emitAction(option.action)" :class="[' h-auto  hover:scale-105 hover:shadow-lg p-2 rounded  text-center  text-white w-full',{'bg-secondary_blue':option.buttonPos,'bg-primary_red':!option.buttonPos}]">
                {{ option.label }}
              </button>
            </div>
            <!-- link -->
            <div v-if="option.type === 'link'&&option.view" class="px-4 py-2">
              <a @click="emitAction(option.action)" class="w-full text-left text-gray-700">
                {{ option.label }}
              </a>
            </div>
  
            
          </li>
        </ul>
        <div v-else>
         <Loader  />{{ translations.titles.proccessing }}
      </div>
      </div>
      
    </div>
  </template>
  
  <script>
import Loader from '../svgs/Loader.vue';
import MenuSvg from '../svgs/Menu.vue';
import ToggleSwitch from './ToggleSwitch.vue';

  export default {
    components:{
        Loader,MenuSvg,ToggleSwitch,
    },
    props:{

        options:{
            type:Object,

        },
        loading:{type:Boolean}
    },
    data() {
      return {
        isOpen: false, // To control dropdown visibility
        translations: window.translations,

        
      };
    },
    methods: {
      toggleDropdown() {
        this.isOpen = !this.isOpen;
      },
      toggleSubmenu(option) {
        option.submenu.isOpen = !option.submenu.isOpen;
      },
      emitAction(action) {
      this.$emit("action-selected", action); // Emit the action to the parent
      this.isOpen = false; // Close the dropdown after option click
    },
      
    }
  };
  </script>
  
  <style scoped>
  /* Tailwind CSS styles for dropdown */
  .dropdown-menu {
    display: none;
  }
  </style>
  