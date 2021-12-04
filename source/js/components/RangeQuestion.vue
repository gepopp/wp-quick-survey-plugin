<template>
  <div>
    <div v-if="!show_results">
      <div class="flex flex-col justify-center items-center" v-show="!answered">
        <h1 class="text-xl font-bold" v-text="question"></h1>
        <p v-text="description"></p>
        <div class="w-full">
            <vue-slide-bar
                :data="createSteps"
                :range="labels"
                :processStyle="sliderCustomzie.processStyle"
                :lineHeight="sliderCustomzie.lineHeight"
                :tooltipStyles="sliderCustomzie.tooltipStyles"
                v-model="rangevalue"
            ></vue-slide-bar>
          <div class="flex justify-between -mt-5 text-xs">
            <div v-text="mintext"></div>
            <div v-text="medtext"></div>
            <div v-text="maxtext"></div>
          </div>
          <div class="flex justify-end mt-5">
            <button class="px-5 py-3 bg-primary-100 text-white text-center" @click="answer">weiter</button>
          </div>
        </div>
      </div>
      <div v-show="feedback && answered" class="flex flex-col justify-center items-center">
        <h1 class="text-xl font-bold">Geben Sie uns Feedback</h1>
        <textarea class="border w-full p-3" rows="5" placeholder="Warum haben Sie diese Antwort gegeben..." v-model="feedback_text"></textarea>
        <input type="email" class="w-full border p-3 mt-3" placeholder="E-Mail" v-model="email">
        <label class="mt-4 w-full text-left">
          <input type="checkbox" class="mr-3" required v-model="newsletter">Ja, ich möchte über Artikel und Umfrageergebnisse per E-Mail infromiert werden.
        </label>
        <div class="flex justify-end items-center w-full space-x-5 mt-4">
          <span class="text-xs" @click="show_results = true">überspringen</span>
          <button class="px-5 py-3 bg-green-500 text-white text-center" @click="save_feedback()">Zu den Ergebnissen</button>
        </div>
      </div>
    </div>

    <div v-else>
      <h1 class="text-xl font-bold text-center" v-text="question"></h1>
      <div class="mt-5">
        <line-chart ref="chart" :labels="createSteps" :chart-data="{}" class="h-32"></line-chart>
        <div class="flex justify-between text-xs px-5">
          <div v-text="mintext"></div>
          <div v-text="medtext"></div>
          <div v-text="maxtext"></div>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
import Qs from 'qs';
import Axios from "axios";
import Cookies from "js-cookie";
import VueSlideBar from 'vue-slide-bar'
import LineChart from "./LineChart.vue";

export default {
  name: "RangeQuestion",
  components: {VueSlideBar, LineChart},
  props: ['post_id', 'question', 'description', 'min', 'max', 'step', 'mintext', 'medtext', 'maxtext', 'feedback', 'answers_given'],
  data() {
    return {
      rangevalue: this.min,
      answered: false,
      answer_saved: false,
      answers: this.answers_given,
      feedback_text: '',
      email: '',
      newsletter: false,
      show_results: false,
      chartdata: {},
      sliderCustomzie: {
        lineHeight: 10,
        processStyle: {
          backgroundColor: '#5C97D0'
        },
        tooltipStyles: {
          backgroundColor: '#5C97D0',
          borderColor: '#5C97D0'
        }
      }
    }
  },
  mounted() {

    this.updateChart();

    if (Cookies.get('survey_' + this.post_id) != undefined) {
      this.show_results = true;
    }

  },
  computed: {
    createSteps(){
      var range = Array.from({length: this.max/this.step}, (v, k) => (k+1) * this.step);
      if(this.step > 1){
        range.unshift(this.min);
      }
      return range;
    },
    labels(){
      var labels = [];
      this.createSteps.forEach((val) =>{
        labels.push({label: val})
      });
      return labels;
    }
  },
  methods: {

    answer() {

      Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
        action: 'qsy_save_answer',
        post_id: this.post_id,
        answer: this.rangevalue
      }))
          .then((response) => {
            this.answer_saved = response.data;
            this.answered = true;
            Cookies.set('survey_' + this.post_id, true);
            if (!this.feedback) {
              this.show_results = true;
              this.updateChart();
            }
          });
    },
    save_feedback() {
      Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
        action: 'qsy_save_answer_feedback',
        answer_id: this.answer_saved,
        feedback: this.feedback_text,
        email: this.email,
        newsletter: this.newsletter
      }))
          .then((response) => {
            Cookies.set('survey_' + this.post_id, true);
            this.show_results = true;
            this.updateChart();
          })
    },
    updateChart(){

      var self = this;

      Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
        action: 'qsy_get_answers_count',
        post_id: this.post_id
      })).then((rsp) => {

        var values = [];

        values = this.createSteps.map((i,v) => {
          return rsp.data[i] == undefined ? 0 : rsp.data[i];
        });


        self.$refs.chart.updateChartdata(this.createSteps, values);
      })
    }
  }
}
</script>

<style scoped>

</style>