<template>
  <div v-if="!isLoading"  class="w-full">
    <div v-if="layout == 'paginate' && question_ids.length > 1" class="w-2/3 mx-auto">
      <vue-slide-bar
          :data="slideRange"
          :range="slideLabels"
          :showTooltip="false"
          :lineHeight="10"
          :draggable="false"
          :processStyle="sliderStyle"
          v-model="current"
      ></vue-slide-bar>
    </div>
    <div v-for="(question, index) in questions" :key="question.id">
      <div v-show="showAnswer(index)">
        <div class="flex flex-col justify-center items-center my-10 pb-10 border-b border-gray-600">
          <h1 class="text-xl font-bold" v-text="question.question"></h1>
          <p v-text="question.description"></p>
          <div class="w-full">
            <div v-if="hasAnswers(question.id)">
            <bar-chart v-if="question.type == 'multiplechoice' && showAnswer(index)" :chart-data="chartData(question.id)" :height="200"></bar-chart>
            <bar-chart v-if="question.type == 'truefalse' && showAnswer(index)" :chart-data="chartData(question.id)" :height="200"></bar-chart>

            <div v-if="question.type == 'range' && showAnswer(index)">
              <line-chart :chart-data="chartData(question.id)" :height="200"></line-chart>
              <div class="flex justify-between -mt-5 mb-5 text-xs">
                <span v-text="question.mintext"></span>
                <span v-text="question.midtext"></span>
                <span v-text="question.maxtext"></span>
              </div>
            </div>

            <div v-if="question.type == 'text'">

                <div v-for="answer in answers"
                     v-if="answer.question_id == question.id"
                     v-text="answer.answer"
                     class="border-b border-gray-900 even:bg-gray-100 list-none p-3"
                ></div>
              </div>
            </div>
            <div v-else class="flex items-center justify-center h-48">
              <p class="font-semibold text-gray-600">Noch keine Antworten vorhanden</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="w-full flex space-x-5 justify-center mb-5" v-if="layout == 'paginate' && question_ids.length > 1">
      <button class="px-10 py-3 text-white text-center cursor-pointer bg-primary-100 disabled:bg-gray-300 disabled:cursor-not-allowed" @click="current--" :disabled="current <= 0">vorherige Antwort</button>
      <button class="px-10 py-3 text-white text-center cursor-pointer bg-primary-100 disabled:bg-gray-300 disabled:cursor-not-allowed" @click="current++" :disabled="current >= question_ids.length - 1">nächste Antwort</button>
    </div>
  </div>
  <div v-else class="flex justify-center items-center w-full h-64">
    <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-primary-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
  </div>
</template>

<script>
import Axios from "axios";
import Qs from "qs"
import BarChart from "./BarChart.vue";
import LineChart from "./LineChart.vue";
import VueSlideBar from "vue-slide-bar";

export default {
  name: "Results",
  components: {LineChart, BarChart, VueSlideBar},
  props: ['questions', 'survey', 'layout'],
  data() {
    return {
      answers: [],
      isLoading: true,
      question_ids: Object.keys(this.questions),
      current: 0,
      sliderStyle: {
        backgroundColor: '#5c97d0'
      }
    }
  },
  mounted() {

    Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
      action: 'qsy_load_survey_answers',
      survey_id: this.survey,
    }))
        .then((response) => {
          this.answers = response.data.answers;
          this.isLoading = false;
        });

  },
  methods: {
    getLabels(id) {

      var question = this.questions[id];

      var predefined = [];

      if (question.type == 'truefalse') {
        predefined.push(question.green);
        predefined.push(question.red);
      }

      if (question.type == 'multiplechoice') {
        predefined = Object.values(question.answer).filter((e) => e != "");
      }

      var answerlabels = this.answers.filter((answer) => {
        if (answer.question_id == id) return answer;
      }).map((e) => e.answer);

      var merged = [...predefined, ...answerlabels];

      return [...new Set(merged)];
    },
    getData(id) {
      return this.answers.filter((answer) => {
        if (answer.question_id == id) return answer;
      }).map((e) => e.count)
    },
    getRangeLabels(id) {

      var min = parseInt(this.questions[id].min);
      var max = parseInt(this.questions[id].max);
      var step = parseInt(this.questions[id].step);

      var range = Array.from({length: max / step}, (v, k) => (k + 1) * step);

      if(step > 1){
        range.unshift(1);
      }

      return range;
    },
    getRangeData(id) {


      var range = this.getRangeLabels(id);

      var values = [];
      var answers = this.answers.filter((answer) => answer.question_id == id);

      range.forEach((index) => {

        values.push(0);

        answers.forEach((answer) => {
          if (answer.answer == index) {
            values.pop();
            values.push(parseInt(answer.count));
          }
        })
      })

      return values;
    },

    getColors(id) {
      return this.questions[id].colors;
    },
    chartData(id) {
      return {
        labels: this.questions[id].type == 'range' ? this.getRangeLabels(id) : this.getLabels(id),
        datasets: [{
          label: '',
          data: this.questions[id].type == 'range' ? this.getRangeData(id) : this.getData(id),
          backgroundColor: this.getColors(id),
          borderWidth: 0
        }]
      };
    },
    showAnswer(index) {

      if (this.layout != 'paginate') {
        return true;
      }

      if (index == this.question_ids[this.current]) {

        // bug in range slider trigger resize event to fix
        setTimeout(() => window.dispatchEvent(new Event('resize')), 100);
        return true;
      }

      return false;
    },
    hasAnswers(question_id){
      return !!this.answers.filter((answer) => answer.question_id == question_id).length;
    }
  },
  computed: {
    slideRange() {
      var keys = this.question_ids.keys();
      return [...keys];
    },
    slideLabels() {
      var labels = []
      this.slideRange.forEach((i) => labels.push({label: 'Antwort ' + (i + 1)}));
      return labels;
    }
  }
}
</script>

<style scoped>

</style>