import '../scss/styles.scss';
import 'dragula/dist/dragula.min.css'
import Vue from "vue";

import Axios from 'axios'

import Survey from './admin/Survey.vue'

(function ($) {

    $(function () {
        $('.color-picker').wpColorPicker();
    })
})

const app = new Vue({
    el: '#quick-survey-admin',
    components : {
        Survey
    }
});