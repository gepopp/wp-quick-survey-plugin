<template>
  <div class="w-full">
    <div class="mt-5 flex justify-center space-x-3 w-full" v-if="!isAnswered">
      <button class="px-5 py-3 text-white text-center w-full"
              :style="{ backgroundColor:  question.colors[0] }"
              v-text="question.green"
              @click="AnswerFunctions.saveAnswer(question.green)"
      ></button>
      <button class="px-5 py-3 text-white text-center w-full"
              :style="{ backgroundColor:  question.colors[1] }"
              v-text="question.red"
              @click="AnswerFunctions.saveAnswer(question.red)"
      ></button>
    </div>
    <question-answered v-else></question-answered>
  </div>
</template>

<script>
import AnswerFunctions from "../AnswerFunctions.js"
import BarChart from "./BarChart.vue";
import QuestionAnswered from "./QuestionAnswered.vue";


export default {
  name: "SimpleQuestion",
  components: {QuestionAnswered, BarChart},
  props: ['question', 'isAnswered'],
  data() {
    return {
      AnswerFunctions: new AnswerFunctions(this),
    }
  }
}
</script>

<style scoped>

</style>