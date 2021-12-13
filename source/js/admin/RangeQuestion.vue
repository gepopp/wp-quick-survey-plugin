<template>
  <div id="range" class="swap">
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.minlabel }}</label>
      <input type="number" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][min]'">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.maxlabel }}</label>
      <input type="number" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][max]'">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.steplabel }}</label>
      <input type="number" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][step]'">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.mintextlabel }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][mintext]'">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.midtextlabel }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][midtext]'">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.maxtextlabel }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][maxtext]'"  >
    </div>
  </div>
</template>

<script>
export default {
  name: "RangeQuestion",
  props: ['question'],
  data() {
    return {
      translations: window.translations
    }
  }
}
</script>

<style scoped>

</style>