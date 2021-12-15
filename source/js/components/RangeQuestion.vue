<template>
  <div>
    <div class="flex flex-col justify-center items-center my-10 pb-10 border-b border-gray-900">
      <h1 class="text-xl font-bold" v-text="question.question"></h1>
      <p v-text="question.description"></p>
      <div v-if="!is_answered">
        <vue-slide-bar
            :data="createSteps"
            :range="labels"
            :processStyle="sliderCustomzie.processStyle"
            :lineHeight="sliderCustomzie.lineHeight"
            :tooltipStyles="sliderCustomzie.tooltipStyles"
            v-model="rangevalue"
        ></vue-slide-bar>
        <div class="flex justify-between -mt-5 text-xs">
          <div v-text="question.mintext"></div>
          <div v-text="question.midtext"></div>
          <div v-text="question.maxtext"></div>
        </div>

        <div class="w-full mt-4 py-3 bg-primary-100 text-white text-center cursor-pointer" @click="AnswerFunctions.saveAnswer(rangevalue)">
          antworten
        </div>
      </div>
      <div v-else>
        <p class="text-green-800">Vielen Dank, ihre Antwort wurde gespeichert.</p>
      </div>
    </div>
  </div>
</template>

<script>
import VueSlideBar from 'vue-slide-bar'
import LineChart from "./LineChart.vue";
import AnswerFunctions from "../AnswerFunctions.js"

export default {
  name: "RangeQuestion",
  components: {VueSlideBar, LineChart},
  props: ['question'],
  data() {
    return {
      answer: false,
      is_answered: false,
      AnswerFunctions: new AnswerFunctions(this),
      rangevalue: this.min,
      saveTimeout: null,
      sliderCustomzie: {
        lineHeight: 10,
        processStyle: {
          backgroundColor: this.question.colors[0]
        },
        tooltipStyles: {
          backgroundColor: this.question.colors[0],
          borderColor: this.question.colors[0]
        }
      }
    }
  },
  mounted() {

    this.is_answered = this.AnswerFunctions.updateStatus();
    this.$parent.$on('answer_saved', () => this.is_answered = this.AnswerFunctions.updateStatus());

    this.$nextTick(() => window.dispatchEvent(new Event('resize')));


  },
  computed: {
    createSteps() {

      var min = parseInt(this.question.min);
      var max = parseInt(this.question.max);
      var step = parseInt(this.question.step);

      var range = Array.from({length: max / step}, (v, k) => (k + 1) * step);
      if (step > 1) {
        range.unshift(min);
      }
      return range;
    },
    labels() {
      var labels = [];
      this.createSteps.forEach((val) => {
        labels.push({label: val})
      });
      return labels;
    }
  },
  renderTracked() {
    window.dispatchEvent(new Event('resize'));
  }
}
</script>
