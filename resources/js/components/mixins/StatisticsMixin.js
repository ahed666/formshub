// src/js/components/mixins/StatisticsMixin.js

export const StatisticsMixin = {
    methods: {
        // to download file after create file to export it
        downloadFile(data,name){
            const blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = name+'.xlsx'; // Specify the filename for the download
            link.click(); // Trigger the download
            this.showAlert('success', 'Export successful!');
        },
        formatDateTime(datetime) {
            console.log('formDatetime',datetime);
            return datetime.replace('T', ' ').slice(0, 19);
        },
        formatNumber(number,fixed){
                    return number.toFixed(fixed);
        },
        calculateAge(birthDateString) {
            const birthDate = new Date(birthDateString);
            const today = new Date();
    
            // Calculate the difference in time (in milliseconds)
            const diffInMs = today - birthDate;
            
            // Calculate days, weeks, months, and years from the difference in milliseconds
            const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
            const diffInWeeks = Math.floor(diffInDays / 7);
            const diffInMonths = (today.getFullYear() - birthDate.getFullYear()) * 12 + (today.getMonth() - birthDate.getMonth());
            const diffInYears = today.getFullYear() - birthDate.getFullYear();
    
            // Determine output based on the calculated differences
            if (diffInDays === 0) {
                return "today";
            } else if (diffInDays < 7) {
                return `${diffInDays} day${diffInDays > 1 ? 's' : ''}`;
            } else if (diffInWeeks < 4) {
                return `${diffInWeeks} week${diffInWeeks > 1 ? 's' : ''}`;
            } else if (diffInMonths < 12) {
                return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''}`;
            } else {
                return `${diffInYears} year${diffInYears > 1 ? 's' : ''}`;
            }
        }
    },
  };
  