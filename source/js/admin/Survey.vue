<template>
  <div class="w-full bg-gray-100">
    <div class="p-5">
      <label class="pb-3 font-semibold block">{{ translations.status }}</label>
      <div class="flex space-x-5 mb-4">
        <label>
          <input type="radio" name="qsy[status]" value="open" id="open" v-model="merged.status"/> {{ translations.open }}</label>
        <label>
          <input type="radio" name="qsy[status]" value="closed" v-model="merged.status"/> {{ translations.closed }}</label>
        <label>
          <input type="radio" name="qsy[status]" value="results" v-model="merged.status"/> {{ translations.showresults }}</label>
      </div>
    </div>
    <div class="p-5">
      <label class="pb-3 font-semibold block">Nach Newsletter Anmeldung fragen?</label>
      <div class="flex space-x-5 mb-4">
        <label>
          <input type="radio" name="qsy[newsletter]" value="none" v-model="merged.newsletter"/> Nicht fragen</label>
        <label>
          <input type="radio" name="qsy[newsletter]" value="allways" v-model="merged.newsletter"/> Immer unter Fragen und Ergebnissen anzeigen</label>
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
      <div class="border border-gray-500 p-5 bg-white wrapper" v-for="(question, index) in questions" :key="question.id">
        <div class="flex justify-between">
          <div class="font-bold text-xl">
            <span v-text="question.question != '' ? question.question : 'Noch keine Frage eingegeben'"></span>

          </div>
          <span v-text="question.id" ></span>
          <div class="flex space-x-5">
            <div @click="$emit('collapse', { id: question.id })" class="flex flex-col">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
              </svg>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </div>
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
        </div>
        <hr>
        <question :question="question" :survey_id="survey.id"></question>
      </div>
    </div>
    <div class="border border-black flex justify-end p-5">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" @click="addQuestion">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
      </svg>
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
    survey: {
      type: Object,
      default() {
        return {
          status: 'open',
          feedback: 'end'
        }
      }
    }
  },
  data() {
    return {
      translations: window.translations,
      questions: {},
      defaults: {
        status: 'open',
        newsletter: 'allways',
        questions: [
          {
            type: 'truefalse',
            id: Date.now(),
            collapsed: false
          }
        ]
      },
    }
  },
  computed: {
    merged() {
      return {
        ...this.defaults,
        ...this.survey
      }
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

    this.questions = Object.values(this.merged.questions);

  },
  methods: {
    async addQuestion() {
      this.questions.push({type: 'truefalse', id: Date.now()});
      await this.$nextTick();
      jQuery('.color-picker').wpColorPicker();
    },
    removeQuestion(id) {
      this.questions = this.questions.filter((question) => {
        if (question.id != id) return question;
      });
    },
  },
}
</script>

<style scoped>

</style>