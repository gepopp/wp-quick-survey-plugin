<template>
  <div>
    <div v-for="answer in answers" :key="answer.id" class="grid grid-cols-8 gap-5 py-2 even:bg-gray-100 border-b border-gray-300">
      <div v-text="answer.id"></div>
      <div v-text="answer.question_id"></div>
      <div v-text="answer.user_id"></div>
      <div v-text="answer.user_ip"></div>
      <div v-text="answer.user_email"></div>
      <div v-text="answer.answer"></div>
      <div v-text="answer.created_at"></div>
      <div class="text-red-900 cursor-pointer" @click="deleteAnswer(answer.id)">löschen</div>
    </div>
  </div>
</template>

<script>
import Axios from "axios";
import Qs from "qs";

export default {
  name: "AnswersTable",
  props: ['answersGiven'],
  data(){
    return {
      answers: this.answersGiven
    }
  },
  methods:{
    deleteAnswer(id){
      Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
        action: 'qsy_delete_answer',
        id : id
      }))
          .then((response) => {
            this.answers = this.answers.filter((a) => a.id != id);
          });
    }
  }
}
</script>

<style scoped>

</style>