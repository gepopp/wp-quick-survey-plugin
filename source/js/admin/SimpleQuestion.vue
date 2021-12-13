<template>
  <div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.green }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][green]'" v-model="question.green">
    </div>
    <div class="mb-4">
      <label class="mb-3 font-semibold">{{ translations.red }}</label>
      <input type="text" class="block w-full border border-black" :name="'qsy[questions][' + question.id + '][red]'" v-model="question.red">
    </div>
  </div>
</template>

<script>
export default {
  name: "SimpleQuestion",
  props: {
    question: {
      type: Object,
      default:{
        red : 'Nein',
        green : 'Ja'
      }
    },
    order:{
      type:Number
    }
  },
  data(){
    return{
      translations: window.translations,

    }
  }
}
</script>
