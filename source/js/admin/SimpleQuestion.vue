<template>
  <div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.green }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][green]'" v-model="merged.green">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">{{ translations.greencolorlabel }}</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[0]">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.red }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][red]'" v-model="merged.red">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">{{ translations.greencolorlabel }}</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[1]">
    </div>
  </div>
</template>

<script>
export default {
  name: "SimpleQuestion",
  props: {
    question: {
      type: Object,
      default: {
        red: 'Nein',
        green: 'Ja'
      }
    },
  },
  data() {
    return {
      translations: window.translations,
      defaults: {
        red: window.translations.redvalue,
        green: window.translations.greenvalue,
        colors: ['#16a34a', '#dc2626']
      },
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
