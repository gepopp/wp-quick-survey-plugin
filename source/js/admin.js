import '../scss/styles.scss';
import 'dragula/dist/dragula.min.css'
import Vue from "vue";

import Survey from './admin/Survey.vue'
import AnswersTable from "./admin/AnswersTable.vue";
import Axios from "axios";


jQuery(document).ready(function ($) {
    $('.color-picker').wpColorPicker();
})

const app = new Vue({
    el: '#quick-survey-admin',
    components : {
        Survey,
    }
});



const app2 = new Vue({
    el: '#quick-survey-table',
    components:{
        AnswersTable
    }
})