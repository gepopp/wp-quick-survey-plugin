<template>
  <div>
    <input type="hidden" :name="'qsy[questions][' + question.id + '][id]'" v-model="question.id">
    <label class="mb-5 font-semibold">{{ translations.type }}</label>
    <div class="flex space-x-5 mb-4">
      <label>
        <input type="radio" :name="'qsy[questions][' + question.id + '][type]'" value="truefalse" v-model="type"/> {{ translations.truefalse }}
      </label>
      <label>
        <input type="radio" :name="'qsy[questions][' + question.id + '][type]'" value="range" v-model="type"/> {{ translations.skala }}
      </label>
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.question }}</label>
      <input type="text" class="block w-full bid bid-black" :name="'qsy[questions][' + question.id + '][question]'" v-model="question.question"/>
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.description }}</label>
      <textarea class="block w-full bid bid-black" :name="'qsy[questions][' + question.id + '][description]'" v-model="question.description"></textarea>
    </div>
    <div v-if="type == 'truefalse'">
      <simple-question :question="question"></simple-question>
    </div>
    <div v-if="type == 'range'">
      <range-question :question="question"></range-question>
    </div>
  </div>
</template>

<script>
import SimpleQuestion from "./SimpleQuestion.vue";
import RangeQuestion from "./RangeQuestion.vue";

export default {
  name: "Question",
  components: {
    SimpleQuestion,
    RangeQuestion
  },
  props: {
    question: {
      type: Object,
      default: {
        id:1,
        red: 'Nein',
        green: 'Ja'
      }
    },
  },
  data() {
    return {
      translations: window.translations,
      type: this.question.type,
      green: this.question.green,
      red: this.question.red
    }
  }
}
</script>
