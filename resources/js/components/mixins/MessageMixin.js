  // src/js/components/mixins/MessageMixin.js

export const MessageMixin = {
    methods: {
        showAlert(type, title) {
            this.$swal.fire({
                position: 'top-end',
                icon: type,
                title: title,
                showConfirmButton: false,
                timer: 3500
            });
        },

        // show warning 
        showWarning(action,text,confirmButtonText,cancelButtonText='Cancel') {
            this.$swal.fire({
              title: "Are you sure?",
              text: text,
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#dc2626",
              cancelButtonColor: "#9cafa3",
              cancelButtonText:cancelButtonText,
              confirmButtonText: confirmButtonText,
              reverseButtons: true,
            }).then((result) => {
              if (result.isConfirmed) {
                // Call the passed action
                action();
      
                
              }
            });
          },
    },
  };