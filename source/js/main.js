import '../scss/styles.scss';

import Vue from "vue";
import SimpleQuestion from "./components/SimpleQuestion.vue";
import RangeQuestion from "./components/RangeQuestion.vue";

import Axios from 'axios'




const Api = Axios.create({
    baseURL: qsy_xhr.rootapiurl,
    headers: {
        'content-type': 'application/json',
        'X-WP-Nonce': qsy_xhr.nonce
    }
});
Vue.prototype.$rest = Api;

const Ajax = Axios.create({
    baseURL: qsy_xhr.ajaxurl,
    headers: {
        'content-type': 'application/json',
        'X-WP-Nonce': qsy_xhr.nonce
    }
})
Vue.prototype.$xhr = Ajax;



const app = new Vue({
    el: '#quick-survey',
    components : {
        SimpleQuestion,
        RangeQuestion
    }
});