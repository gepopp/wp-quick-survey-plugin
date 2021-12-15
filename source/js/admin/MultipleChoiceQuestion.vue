<template>
  <div>
    <div class="p-5">
      <label class="pb-3 font-semibold block">{{ translations.multiplechoicelabel }}</label>
      <div class="flex space-x-5 mb-4">
        <label>
          <input type="radio" :name="'qsy[questions][' + merged.id + '][answermode]'" value="single" v-model="merged.answermode"/> {{ translations.singleanswerlabel }}</label>
        <label>
          <input type="radio" :name="'qsy[questions][' + merged.id + '][answermode]'" value="multiple" v-model="merged.answermode"/> {{ translations.multianswerlabel }}</label>
      </div>
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.answerlabel }} 1</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][answer][1]'" v-model="merged.answer[1]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">Auswhal 1 Farbe</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[0]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.answerlabel }} 2</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][answer][2]'" v-model="merged.answer[2]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">Auswhal 2 Farbe</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[1]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.answerlabel }} 3</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][answer][3]'" v-model="merged.answer[3]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">Auswhal 3 Farbe</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[2]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.answerlabel }} 4</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][answer][4]'" v-model="merged.answer[4]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">Auswhal 4 Farbe</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[3]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.answerlabel }} 5</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][answer][5]'" v-model="merged.answer[5]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">Auswhal 4 Farbe</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[4]">
    </div>
  </div>
</template>

<script>
export default {
  name: "MultipleChoiceQuestion",
  props: {
    question: {
      type: Object,
      default(){
        return {
          answermode : 'single',
          answer: ['', '', '', '', ''],

        }
      }
    }
  },
  data() {
    return {
      translations: window.translations,
      defaults : {
        answermode : 'single',
        answer: ['', '', '', '', ''],
        colors: ['#5c97d0', '#5c97d0', '#5c97d0', '#5c97d0', '#5c97d0']
      }
    }
  },
  computed: {
    merged() {
      return {
        ...this.defaults,
        ...this.question
      }
    }
  }
}
</script>

<style scoped>

</style>