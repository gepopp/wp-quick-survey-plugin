<template>
  <div>
    <div class="w-full" v-if="!isAnswered">
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

      <div class="block w-full mt-4 py-3 bg-primary-100 text-white text-center cursor-pointer"
           @click="AnswerFunctions.saveAnswer(rangevalue)"
           style="z-index: 99999"
      >
        antworten
      </div>
    </div>
    <question-answered v-else></question-answered>
  </div>
</template>

<script>
import VueSlideBar from 'vue-slide-bar'
import LineChart from "./LineChart.vue";
import AnswerFunctions from "../AnswerFunctions.js"
import QuestionAnswered from "./QuestionAnswered.vue";

export default {
  name: "RangeQuestion",
  components: {
    QuestionAnswered,
    VueSlideBar,
    LineChart
  },
  props: ['question', 'isAnswered'],
  data() {
    return {
      answer: false,
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
  }
}
</script>
