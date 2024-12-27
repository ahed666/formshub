import './bootstrap';
import 'flowbite';
import VueSweetalert2 from 'vue-sweetalert2';

// If you want to include the default styles
import 'sweetalert2/dist/sweetalert2.min.css';

let player=document.getElementById('main');

if ('serviceWorker' in navigator) {
    console.log('Service Worker test');
        window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
            //   player.innerHTML+='.....Service Worker registered with scope:'+registration.scope;
            console.log('Service Worker registered with scope:', registration.scope);
            })
            .catch(error => {
                // player.innerHTML+='.....Service Worker registration failed:'+error;
            console.log('Service Worker registration failed:', error);
            });
        });
    }
    else{
        // player.innerHTML+='.....Service Worker is not supported in this browser';
    }



import { createApp } from 'vue'
import AppTemplate from './components/AppTemplate.vue'
import Counter from './components/Counter.vue'
import DeviceList from './components/devices/DeviceList.vue'
import Header from './components/HeaderDiv.vue'
import Button from './components/actions/ButtonComponent.vue'
import Devices from './components/devices/Devices.vue'
import Forms from './components/forms/Index.vue'
import FormDeviceTemplate from './components/formOnDevice/FormDeviceTemplate.vue'

import EditForm from './components/forms/EditForm.vue'
import StatisticsForm from './components/forms/statistics/StatisticsIndex.vue'

import AddFormModal from './components/modals/AddFormModal.vue'

import AddDeviceModal from './components/modals/AddDeviceModal.vue'
import DashboardIndex from './components/dashboard/Index.vue';

import BillingInfo from './components/profile/BillingInfo.vue'

import Echarts from 'vue-echarts';

import 'echarts/lib/chart/bar'
import 'echarts/lib/chart/line'

import 'echarts/lib/component/title'
import 'echarts/lib/component/legend'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/grid'
import 'echarts/lib/component/toolbox'


// subscriptions
import Pricing from './components/subscriptions/Pricing.vue'; 
import SubscriptionIndex from './components/subscriptions/SubscriptionIndex.vue';

import DashboardSvg from './components/svgs/Dashboard.vue';
import DeviceSvg from './components/svgs/Device.vue';
import FormSvg from './components/svgs/Form.vue';
import SubscriptionsSvg from './components/svgs/Subscriptions.vue';
import ProfileSvg from './components/svgs/Profile.vue'

const app = createApp()
const devicePlayer=createApp()
const sideBar=createApp()

app.component('chart', Echarts)

app.component('counter', Counter)
app.component('device-list', DeviceList)
app.component('forms', Forms)

app.component('headerpage', Header)
app.component('button-component', Button)
app.component('app-template', AppTemplate)
app.component('devices-comp', Devices)
app.component('add-device-modal', AddDeviceModal)
app.component('add-form-modal', AddFormModal)
app.component('edit-form', EditForm)
app.component('statistics-form', StatisticsForm)

app.component('pricing', Pricing)
app.component('subscription-index', SubscriptionIndex)
app.component('dashboard-index',DashboardIndex)
app.component('billing-info',BillingInfo)

sideBar.component('dashboard-svg',DashboardSvg)
sideBar.component('device-svg',DeviceSvg)
sideBar.component('form-svg',FormSvg)
sideBar.component('profile-svg',ProfileSvg)
sideBar.component('subscriptions-svg',SubscriptionsSvg)

devicePlayer.component('form-device', FormDeviceTemplate)


devicePlayer.use(VueSweetalert2)
app.use(VueSweetalert2)
sideBar.use(VueSweetalert2)
app.mount('#app')
sideBar.mount('#logo-sidebar')
devicePlayer.mount('#device_player')
