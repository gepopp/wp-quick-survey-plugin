<template>
  <div>
    <div class="flex flex-col justify-center items-center my-10 pb-10 border-b border-gray-900">
      <h1 class="text-xl font-bold" v-text="question.question"></h1>
      <p v-text="question.description"></p>
      <div class="mt-5 flex flex-col w-full" v-if="!is_answered">
        <textarea class="w-full border border-gray-900 p-3" rows="8" v-model="answer"></textarea>
        <div class="w-full mt-4 py-3 bg-primary-100 text-white text-center cursor-pointer" @click="AnswerFunctions.saveAnswer(answer)">
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
import AnswerFunctions from "../AnswerFunctions.js"


export default {
  name: "TextQuestion",
  props: ['question'],
  data() {
    return {
      answer: '',
      is_answered : false,
      AnswerFunctions : new AnswerFunctions(this)
    }
  },
  mounted() {

    this.is_answered = this.AnswerFunctions.updateStatus();
    this.$parent.$on('answer_saved', () => this.is_answered = this.AnswerFunctions.updateStatus());

  },
}
</script>

<style scoped>

</style>