<template>
  <div>
    <div v-show="!collapsed">
      <input type="hidden" :name="'qsy[questions][' + question.id + '][id]'" v-model="question.id">
      <input type="hidden" :name="'qsy[questions][' + question.id + '][survey]'" v-model="survey_id">
      <label class="mb-5 font-semibold">{{ translations.type }}</label>
      <div class="flex space-x-5 mb-4">
        <label>
          <input type="radio" :name="'qsy[questions][' + question.id + '][type]'" value="truefalse" v-model="type" @change="colorPicker"/> {{ translations.truefalse }}
        </label>
        <label>
          <input type="radio" :name="'qsy[questions][' + question.id + '][type]'" value="range" v-model="type" @change="colorPicker"/> {{ translations.skala }}
        </label>
        <label>
          <input type="radio" :name="'qsy[questions][' + question.id + '][type]'" value="multiplechoice" v-model="type" @change="colorPicker"/> {{ translations.multiplechoice }}
        </label>
        <label>
          <input type="radio" :name="'qsy[questions][' + question.id + '][type]'" value="text" v-model="type" @change="colorPicker"/> {{ translations.textquestionlabel }}
        </label>
      </div>

      <div class="mb-4">
        <label class="mb-3 font-semibold">{{ translations.question }}</label>
        <input type="text" class="block w-full bid bid-black" :name="'qsy[questions][' + question.id + '][question]'" v-model="question.question"/>
      </div>
      <div class="mb-4">
        <label class="mb-3 font-semibold">{{ translations.description }}</label>
        <textarea class="block w-full bid bid-black p-2" :name="'qsy[questions][' + question.id + '][description]'" v-model="question.description"></textarea>
      </div>
      <div v-if="type == 'truefalse'">
        <simple-question :question="question"></simple-question>
      </div>
      <div v-if="type == 'range'">
        <range-question :question="question"></range-question>
      </div>
      <div v-if="type == 'multiplechoice'">
        <multiple-choice-question :question="question"></multiple-choice-question>
      </div>
    </div>
  </div>
</template>

<script>
import SimpleQuestion from "./SimpleQuestion.vue";
import RangeQuestion from "./RangeQuestion.vue";
import MultipleChoiceQuestion from "./MultipleChoiceQuestion.vue";

export default {
  name: "Question",
  components: {
    SimpleQuestion,
    RangeQuestion,
    MultipleChoiceQuestion
  },
  props: {
    survey_id: {
      type: Number
    },
    question: {
      type: Object,
      default: {
        id: 1,
        red: 'Nein',
        green: 'Ja'
      }
    },
  },
  mounted() {
    this.$parent.$on('collapse', (e) => {

      if(this.question.id == e.id)
      this.collapsed = !this.collapsed
    });

  },
  data() {
    return {
      translations: window.translations,
      type: this.question.type,
      green: this.question.green,
      red: this.question.red,
      collapsed: false
    }
  },
  methods:{
    async colorPicker(){
     await this.$nextTick();
      jQuery('.color-picker').wpColorPicker()
    }
  }
}
</script>
