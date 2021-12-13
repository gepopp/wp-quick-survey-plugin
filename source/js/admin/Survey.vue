<template>
  <div class="w-full bg-gray-100">
    {{ survey }}
    <div class="p-5">
      <label class="pb-3 font-semibold block">{{ translations.status }}</label>
      <div class="flex space-x-5 mb-4">
        <label>
          <input type="radio" name="qsy[status]" value="open" id="open" v-model="survey.status"/> {{ translations.open }}</label>
        <label>
          <input type="radio" name="qsy[status]" value="closed" v-model="survey.status"/> {{ translations.closed }}</label>
        <label>
          <input type="radio" name="qsy[status]" value="results" v-model="survey.status"/> {{ translations.showresults }}</label>
      </div>
    </div>
    <div class="p-5">
      <label class="pb-3 font-semibold block">{{ translations.feedbacklabel }}</label>
      <div class="flex space-x-5 mb-4">
        <label>
          <input type="radio" name="qsy[feedback]" value="none" v-model="survey.feedback"/> {{ translations.feedbackclosed }}</label>
        <label>
          <input type="radio" name="qsy[feedback]" value="every" v-model="survey.feedback"/> {{ translations.feedbackevery }}</label>
        <label>
          <input type="radio" name="qsy[feedback]" value="end" v-model="survey.feedback"/> {{ translations.feedbackend }}</label>
      </div>
    </div>
    <div class="border border-gray-500 flex justify-between p-5">
      <h3 class="text-xl font-semibold">Fragen</h3>
      <div>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" @click="addQuestion">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
      </div>
    </div>
    <div class="border border-gray-500 flex flex-col p-5 wrapper" id="dragable">
      <div class="border border-gray-500 p-5 bg-white wrapper" v-for="(question, index) in questions" :key="questions.id">
        <div class="flex justify-end">
          <div class="relative w-10 h-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
            </svg>
            <div class="absolute top-0 left-0 w-full h-full handle hover:cursor-move"></div>
          </div>

          <div @click="removeQuestion(question.id)">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="roud" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
          </div>
        </div>
        <question :question="question"></question>
      </div>
    </div>
  </div>
</template>

<script>
import Question from "./Question.vue";
import dragula from "dragula";

export default {
  name: "Survey",
  components: {
    Question
  },
  props: {
    survey : {
      type : Object,
      default(){
        return {
          status : 'open',
          feedback : 'end'
        }
      }
    }
  },
  data() {
    return {
      translations: window.translations,
      questions: this.survey.questions == undefined ? [] : Object.values(this.survey.questions),
    }
  },
  mounted() {
    var self = this;
    var from = null;
    var drake = dragula([document.querySelector('#dragable')], {
      direction: 'vertical',
      moves: function (el, container, handle) {
        return handle.classList.contains('handle');
      }
    });
  },
  methods: {
    addQuestion() {
      this.questions.push({type: 'truefalse', id: Date.now()})
    },
    removeQuestion(id) {

      this.questions = this.questions.filter((question) => {
        if (question.id != id) return question;
      });

    },
  }
}
</script>

<style scoped>

</style>