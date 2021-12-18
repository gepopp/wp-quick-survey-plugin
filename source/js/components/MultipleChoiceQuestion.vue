<template>
  <div class="w-full">
    <div class="mt-5 flex justify-center space-x-3 w-full" v-if="!isAnswered">

      <div v-if="question.answermode == 'single'" class="flex flex-col w-full">
        <label v-for="( option, x ) in  question.answer" v-if="option != ''" class="flex items-center space-x-5 w-full text-left">
          <input type="radio"
                 class="form-radio h-4 w-4 border border-gray-600"
                 :style="{color:question.colors[x]}"
                 name="answer"
                 v-model="answer"
                 :value="option"
                 @change="AnswerFunctions.saveAnswer(answer)">
          <span>{{ option }}</span>
        </label>
      </div>

      <div v-if="question.answermode == 'multiple'" class="flex flex-col w-full">
        <label v-for="( option, x ) in  question.answer" v-if="option != ''" class="flex items-center space-x-5 w-full text-left">
          <input type="checkbox"
                 class="form-checkbox h-4 w-4 border border-gray-600"
                 :style="{color:question.colors[x]}"
                 v-model="answer"
                 :value="option">
          <span>{{ option }}</span>
        </label>
        <div class="w-full mt-4 py-3 bg-primary-100 text-white text-center cursor-pointer" @click="saveCheckboxes">
          antworten
        </div>
      </div>
    </div>
    <question-answered v-else></question-answered>
  </div>
</template>

<script>
import AnswerFunctions from "../AnswerFunctions.js"
import QuestionAnswered from "./QuestionAnswered.vue";


export default {
  name: "MultipleChoiceQuestion",
  components: {QuestionAnswered},
  props: ['question', 'isAnswered'],
  data() {
    return {
      answer: [],
      AnswerFunctions: new AnswerFunctions(this)
    }
  },
  methods:{
    saveCheckboxes(){
      this.answer.forEach((answer) => this.AnswerFunctions.saveAnswer(answer))
    }
  }
}
</script>

<style scoped>

</style>