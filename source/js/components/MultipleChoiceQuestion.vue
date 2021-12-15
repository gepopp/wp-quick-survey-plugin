<template>
  <div>
    <div class="flex flex-col justify-center items-center my-10 pb-10 border-b border-gray-900">
      <h1 class="text-xl font-bold" v-text="question.question"></h1>
      <p v-text="question.description"></p>
      <div class="mt-5 flex justify-center space-x-3 w-full" v-if="!is_answered">

        <div v-if="question.answermode == 'single'" class="flex flex-col w-full">
          <label v-for="( option, x ) in  question.answer" v-if="option != ''" class="flex items-center space-x-5 text-lg w-full text-left">
            <input type="radio"
                   class="form-radio h-6 w-6 border border-gray-600"
                   :style="{color:question.colors[x]}"
                   name="answer"
                   v-model="answer"
                   :value="option"
                   @change="AnswerFunctions.saveAnswer(answer)">
            <span>{{ option }}</span>
          </label>
        </div>

        <div v-if="question.answermode == 'multiple'" class="flex flex-col w-full">
          <label v-for="( option, x ) in  question.answer" v-if="option != ''" class="flex items-center space-x-5 text-lg w-full text-left">
            <input type="checkbox"
                   class="form-checkbox h-6 w-6 border border-gray-600"
                   :style="{color:question.colors[x]}"
                   v-model="answer"
                   :value="option">
            <span>{{ option }}</span>
          </label>

          <div class="w-full mt-4 py-3 bg-primary-100 text-white text-center cursor-pointer" @click="save">
            antworten
          </div>
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
  name: "MultipleChoiceQuestion",
  props: ['question'],
  data() {
    return {
      answer: [],
      is_answered : false,
      AnswerFunctions : new AnswerFunctions(this)
    }
  },
  mounted() {

    this.is_answered = this.AnswerFunctions.updateStatus();
    this.$parent.$on('answer_saved', () => this.is_answered = this.AnswerFunctions.updateStatus());

  },
  methods: {
    save(){
        this.answer.forEach((answer) => {
          this.AnswerFunctions.saveAnswer(answer);
        })
    }
  }
}
</script>

<style scoped>

</style>