<template>
  <div>
    <div v-for="(question, index) in questions" :key="question.id">
      <div class="flex flex-col justify-center items-center my-10 pb-10 border-b border-gray-900">
        <h1 class="text-xl font-bold" v-text="question.question"></h1>
        <p v-text="question.description"></p>
        <div class="w-full">
          <bar-chart v-if="question.type == 'truefalse' && answers.length != 0" :chart-data="chartData(question.id)"></bar-chart>
          <bar-chart v-if="question.type == 'multiplechoice' && answers.length != 0" :chart-data="chartData(question.id)"></bar-chart>
          <line-chart v-if="question.type == 'range' && answers.length != 0" :chart-data="chartData(question.id)"></line-chart>

          <div v-if="question.type == 'text'">
            <ul>
              <li v-for="answer in answers" v-if="answer.question_id == question.id" v-text="answer.answer"></li>
            </ul>
          </div>

        </div>
        <hr>
      </div>
    </div>
  </div>
</template>

<script>
import Axios from "axios";
import Qs from "qs"
import BarChart from "./BarChart.vue";
import LineChart from "./LineChart.vue";

export default {
  name: "Results",
  components: {LineChart, BarChart},
  props: ['questions', 'survey'],
  data() {
    return {
      answers: []
    }
  },
  mounted() {
    this.loadAnswers();
  },
  methods: {
    loadAnswers() {
      Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
        action: 'qsy_load_answers',
        survey: this.survey
      }))
          .then((rsp) => this.answers = rsp.data)
      .catch((rsp) => console.log(rsp.response));
    },
    getLabels(id) {

      var question = this.questions[id];

      var predefined = [];

      if(question.type == 'truefalse'){
        predefined.push(question.green);
        predefined.push(question.red);
      }

      if(question.type == 'multiplechoice'){
        predefined = Object.values(question.answer).filter((e) => e != "");
      }

      var answerlabels = this.answers.filter((answer) => {
        if (answer.question_id == id) return answer;
      }).map((e) => e.answer);

      var merged = [...predefined, ...answerlabels];

      return [ ... new Set(merged)];
    },
    getData(id) {
      return this.answers.filter((answer) => {
        if (answer.question_id == id) return answer;
      }).map((e) => e.count)
    },
    getRangeLabels(id){

      var min = parseInt(this.questions[id].min);
      var max = parseInt(this.questions[id].max);
      var step = parseInt(this.questions[id].step);

      var range = Array.from({length: max / step}, (v, k) => (k + 1) * step);
      if (step > 1) {
        range.unshift(min);
      }
      return range;
    },
    getRangeData(id){


       var range = this.getRangeLabels(id);
       var values = [];
       var answers = this.answers.filter((answer) => answer.question_id == id);

       range.forEach((index) => {

         values[index-1] = 0;

         answers.forEach((answer) => {
           if(answer.answer == index){
             values[index-1] = ( parseInt(answer.count));
           }
         })
       })
      return values;
    },

    getColors(id){
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
    }
  }
}
</script>

<style scoped>

</style>