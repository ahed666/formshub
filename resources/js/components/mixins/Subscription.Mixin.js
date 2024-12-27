
  // src/js/components/mixins/SubscriptionMixin.js
  console.log(window.translations); // Add this before accessing it

  var translations=window.translations;

export const SubscriptionMixin = {
    methods: {
        // status color 
            getStatusClass(status){
                switch (status) {
                    case 'active':
                        return 'bg-green-500 text-white'
                        break;
                    case 'canceled':
                        return 'bg-red-400 text-white'
                        break;
                    case 'trialing':
                        return 'bg-yellow-400 '
                        break;
                    default:
                        break;
            }

        },
        createDaysLeftText(daysLeft) {
            let className, message;
            
            daysLeft=Math.round(daysLeft);
            
            if (daysLeft > 0) {
                message = translations.subscriptions.subscription_expires_in.replace(':days', `<span class="text-valid mx-1 font-bold">${daysLeft}</span>`);
            } else if (daysLeft < 0) {
                message = translations.subscriptions.subscription_expired.replace(':days', `<span class="text-primary_red mx-1 font-bold">${Math.abs(daysLeft)}</span>`);
            } else {
                message = translations.subscriptions.subscription_expires_today;
            }
            
            return message;
        }
    }
  };
  