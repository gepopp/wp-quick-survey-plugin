<template>
  <div>
    <div v-if="!show_results">
      <div class="flex flex-col justify-center items-center" v-show="!answered">
        <h1 class="text-xl font-bold" v-text="question"></h1>
        <p v-text="description"></p>
        <div class="mt-5 flex justify-center space-x-3 w-full">
          <button class="px-5 py-3 bg-green-500 text-white text-center w-full" v-text="green" @click="answer(green)"></button>
          <button class="px-5 py-3 bg-red-500 text-white text-center w-full" v-text="red" @click="answer(red)"></button>
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
      <bar-chart :green="green" :red="red" :chart-data="chartdata" ref="chart"></bar-chart>
    </div>


  </div>
</template>

<script>
import Qs from 'qs';
import Axios from "axios";
import Cookies from "js-cookie";
import BarChart from "./BarChart.vue";

export default {
  name: "SimpleQuestion",
  components: {BarChart},
  props: ['post_id', 'question', 'description', 'green', 'red', 'feedback', 'answers_given'],
  data() {
    return {
      answered: false,
      answer_saved: false,
      answers: this.answers_given,
      feedback_text: '',
      email: '',
      newsletter: false,
      show_results: false,
      chartdata: {},
    }
  },
  mounted() {

    this.updateChart();

    if (Cookies.get('survey_' + this.post_id) != undefined) {
      this.show_results = true;
    }

  },
  methods: {
    answer(value) {

      Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
        action: 'qsy_save_answer',
        post_id: this.post_id,
        answer: value
      }))
          .then((response) => {
            console.log(response.data);
            this.answer_saved = response.data;
            this.answered = true;
            if (!this.feedback) {
              Cookies.set('survey_' + this.post_id, true);
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
        self.$refs.chart.updateChardata([rsp.data[this.green], rsp.data[this.red]]);
      })
    }
  }
}
</script>

<style scoped>

</style>