<template>
    <div class="max-w-7xl mx-auto py-6">
      <h1 class="text-3xl font-semibold mb-6">Available Plans</h1>
  
      <div v-if="plans.length > 0" 
        :class="['grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6',{'animate-pulse':plans.length==0}]">
        <div
          v-for="plan in plans"
          :key="plan.id"
          class="bg-white p-6 rounded-lg shadow-md border border-gray-200"
        >
          <h2 class="text-xl font-semibold text-gray-800">{{ plan.name }}</h2>
          <p class="text-gray-500 text-sm mb-4">{{ plan.description }}</p>
  
          <div class="mb-4">
            <p class="font-bold text-lg">Features:</p>
            <ul class="list-disc pl-5 text-gray-700">
              <li v-for="(value, key) in decodeJson(plan.features)" :key="key">
                <strong>{{ key+1 }})</strong> {{ value.name }}
              </li>
            </ul>
          </div>
  
          <div class="mb-4">
            <p class="text-lg font-semibold text-gray-800">
              {{ plan.currency.toUpperCase() }} {{ plan.price }} / {{ plan.interval }}
            </p>
          </div>
  
          <button
          v-if="!plan.is_free"
            class="bg-indigo-600 text-white px-4 py-2 rounded-full hover:bg-indigo-700 focus:outline-none"
            @click="selectPlan(plan)"
          >
            Select Plan
          </button>
        </div>
      </div>
  
      
    </div>
  </template>
  
  <script>  
  export default {
    data() {
      return {
        plans: [],
      };
    },
    mounted() {
      this.fetchPlans();
    },
    methods: {
      async fetchPlans() {
        try {
          const response = await axios.get('/getplans'); // Call Laravel API
          console.log(response);
          this.plans = response.data.plans;
        } catch (error) {
          console.error('Error fetching plans:', error);
        }
      },
      async selectPlan(plan) {
        // Handle plan selection logic (e.g., show checkout modal or redirect to checkout page)
        console.log(plan);
        const response = await axios.get(`/subscription-checkout/${plan.stripe_product_id}/${plan.stripe_price_id}`);
        window.location.href = response.data.url;
      },
      decodeJson(jsonStr) {
      try {
        return JSON.parse(jsonStr);  // Decoding JSON string
      } catch (error) {
        console.error('Error decoding JSON:', error);
        return {};  // Return an empty object if decoding fails
      }
    }
    },
  };
  </script>
  
  <style scoped>
  /* Add any custom styles here */
  </style>
  