import Vue from 'vue'
import App from './App.vue'
import axios from 'axios';
import router from "@/router";
Vue.config.productionTip = false
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
axios.defaults.withCredentials = true;
Vue.prototype.$axios = axios;
new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
