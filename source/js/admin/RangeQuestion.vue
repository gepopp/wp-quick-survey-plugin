<template>
  <div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.minlabel }}</label>
      <input type="number" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][min]'" v-model="merged.min">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.maxlabel }}</label>
      <input type="number" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][max]'" v-model="merged.max">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.steplabel }}</label>
      <input type="number" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][step]'" v-model="merged.step">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.mintextlabel }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][mintext]'" v-model="merged.mintext">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.midtextlabel }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][midtext]'" v-model="merged.midtext">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.maxtextlabel }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + merged.id + '][maxtext]'" v-model="merged.maxtext">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold block">{{ translations.barcolorlabel }}</label>
      <input type="text" class="color-picker" :name="'qsy[questions][' + merged.id + '][colors][]'" v-model="merged.colors[0]">
    </div>
  </div>
</template>

<script>
export default {
  name: "RangeQuestion",
  props: ['question'],
  data() {
    return {
      translations: window.translations,
      defaults: {
        min: 1,
        max: 10,
        step: 1,
        mintext: 'Nichts',
        midtext: 'Die Hälfte',
        maxtext: 'Alles',
        colors: ['#5c97d0']
      }
    }
  },
  computed:{
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