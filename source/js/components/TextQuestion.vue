<template>
  <div class="w-full">
    <div class="mt-5 flex flex-col w-full" v-if="!isAnswered">
      <textarea class="w-full border border-gray-900 p-3" rows="3" v-model="answer"></textarea>
      <div class="w-full mt-4 py-3 bg-primary-100 text-white text-center cursor-pointer" @click="AnswerFunctions.saveAnswer(answer)">
        antworten
      </div>
    </div>
    <question-answered v-else></question-answered>
  </div>
</template>

<script>
import AnswerFunctions from "../AnswerFunctions.js"
import QuestionAnswered from "./QuestionAnswered.vue";


export default {
  name: "TextQuestion",
  components: {QuestionAnswered},
  props: ['question', 'isAnswered'],
  data() {
    return {
      answer: '',
      AnswerFunctions: new AnswerFunctions(this)
    }
  }
}
</script>

<style scoped>

</style>