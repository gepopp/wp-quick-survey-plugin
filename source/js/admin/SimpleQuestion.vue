<template>
  <div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.green }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][green]'" v-model="merged.green">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.red }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][red]'" v-model="merged.red">
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
        green: window.translations.greenvalue
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
