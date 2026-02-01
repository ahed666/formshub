<template>
  <div>
    <!-- Warning modal that appears after inactivity -->
    <div v-if="showWarning" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <p class="text-lg font-semibold text-red-600 mb-4">
          You have been inactive for a while. Are you still there?
        </p>
        <p class="text-sm text-gray-600">Page will refresh in {{ remainingTime }} seconds.</p>
        <button 
          @click="resetInactivityTimer"
          class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg mt-4"
        >
          I'm Here
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      inactivityTimeout: null, // Holds the inactivity timeout reference
      showWarning: false, // Controls visibility of the warning message
      warningDelay: 1 * 60 * 1000, // Show warning after 2 minutes (120000 milliseconds)
      remainingTime: 15, // Time remaining before the page refreshes after showing the warning (120 seconds)
      refreshDelay: 30 * 1000, // Additional 30 seconds delay before refreshing the page
      countdownInterval: null, // Reference to the countdown interval
      warningTimeout: null // Holds the warning timeout reference for the final refresh
    };
  },
  mounted() {
    // Start the inactivity timer when the component is mounted
    this.startInactivityTimer();

    // Add event listeners for user activity
    window.addEventListener('mousemove', this.resetInactivityTimer);
    window.addEventListener('keydown', this.resetInactivityTimer);
    window.addEventListener('click', this.resetInactivityTimer);
  },
  beforeDestroy() {
    // Clean up event listeners and timeouts when the component is destroyed
    window.removeEventListener('mousemove', this.resetInactivityTimer);
    window.removeEventListener('keydown', this.resetInactivityTimer);
    window.removeEventListener('click', this.resetInactivityTimer);
    clearTimeout(this.inactivityTimeout);
    clearTimeout(this.warningTimeout);
    clearInterval(this.countdownInterval);
  },
  methods: {
    // Start the inactivity timer
    startInactivityTimer() {
      this.inactivityTimeout = setTimeout(() => {
        this.showWarning = true; // Show warning after 2 minutes
        this.startCountdown(); // Start the countdown (2 minutes)

        // After the countdown (2 minutes), give an additional 30 seconds before refreshing
        this.warningTimeout = setTimeout(() => {
          this.$emit('resetstep');
        }, this.refreshDelay); // 30 seconds after the initial 2 minutes countdown
      }, this.warningDelay); // Initial inactivity delay (2 minutes)
    },
    // Reset the inactivity timer and the warning
    resetInactivityTimer() {
      // Hide the warning if it's visible
      if (this.showWarning) {
        this.showWarning = false;
      }

      // Reset the warning timeout and stop the countdown
      clearTimeout(this.warningTimeout);
      clearInterval(this.countdownInterval); // Stop the countdown

      // Clear the existing inactivity timeout and start a new one
      clearTimeout(this.inactivityTimeout);
      this.startInactivityTimer();

      // Reset remaining time to 15 seconds for the next countdown
      this.remainingTime = 15;
    },
    // Start the countdown timer for 120 seconds
    startCountdown() {
      this.remainingTime = 15; // Set the countdown to 15 seconds 
      this.countdownInterval = setInterval(() => {
        this.remainingTime--;
        if (this.remainingTime <= 0) {
          clearInterval(this.countdownInterval); // Stop the countdown when it reaches 0
        }
      }, 1000); // Decrease the countdown every second
    }
  }
};
</script>

<style scoped>
/* No custom styles needed, using Tailwind CSS for all styling */
</style>
