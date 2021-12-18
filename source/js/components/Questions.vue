<template>
  <div class="relative h-full">
    <div v-if="answered.length < question_ids.length && status == 'open'">
      <div v-if="layout == 'paginate' && question_ids.length > 1 && !isFrontpage" class="w-2/3 mx-auto -mt-10">
        <vue-slide-bar
            :data="slideRange"
            :range="slideLabels"
            :showTooltip="false"
            :is-disabled="true"
            :lineHeight="10"
            :processStyle="sliderStyle"
            v-model="current"
        ></vue-slide-bar>
      </div>

      <transition-group name="fade" tag="div">
        <div v-for="(question, index) in questions" :key="question.id">
          <div v-show="showQuestion(index)">
            <div class="flex flex-col justify-center items-center"
                 :class="{'my-10 pb-10 border-b border-gray-600' : !isFrontpage}">
              <h1 class="text-xl font-bold" v-text="question.question"></h1>
              <p v-text="question.description" :class="{ 'line-clamp-2' : isFrontpage }"></p>


              <simple-question
                  :question="question"
                  :is-answered="isQuestionAnswered(question.id)"
                  @answer="newAnswer"
                  v-if="question.type == 'truefalse'"
              ></simple-question>
              <div class="w-full">
                <range-question
                    :question="question"
                    :is-answered="isQuestionAnswered(question.id)"
                    @answer="newAnswer"
                    v-if="question.type == 'range'"
                ></range-question>
              </div>
              <multiple-choice-question
                  :question="question"
                  :is-answered="isQuestionAnswered(question.id)"
                  @answer="newAnswer"
                  v-if="question.type == 'multiplechoice'"
              ></multiple-choice-question>
              <text-question
                  :question="question"
                  :is-answered="isQuestionAnswered(question.id)"
                  @answer="newAnswer"
                  v-if="question.type == 'text'"
              ></text-question>
            </div>
          </div>
        </div>
      </transition-group>

      <div class="w-full flex space-x-5 justify-center mb-5" v-if="layout == 'paginate' && question_ids.length > 1 && !isFrontpage">
        <button class="px-10 py-3 text-white text-center cursor-pointer bg-primary-100 disabled:bg-gray-300 disabled:cursor-not-allowed" @click="current--" :disabled="current <= 0">vorherige Frage</button>
        <button class="px-10 py-3 text-white text-center cursor-pointer bg-primary-100 disabled:bg-gray-300 disabled:cursor-not-allowed" @click="current++" :disabled="current >= question_ids.length - 1">nächste Frage</button>

      </div>
    </div>

    <div v-else class="flex h-full w-full items-center justify-center">
      <results :questions="questions" :survey="survey" :layout="layout" v-if="!isFrontpage"></results>
      <div v-else class="text-center">
        <h3 class="text-xl mb-3" v-text="surveyTitle"></h3>
        <a :href="postLink" target="_blank" class="bg-primary-100 py-2 px-5 text-center text-white inline-block">Zu den Umfrageergebnissen</a>
      </div>
    </div>


    <div v-if="newsletter == 'allways'">
      <newsletter-form></newsletter-form>
    </div>
  </div>
</template>

<script>
import SimpleQuestion from "./SimpleQuestion.vue";
import RangeQuestion from "./RangeQuestion.vue";
import MultipleChoiceQuestion from "./MultipleChoiceQuestion.vue";
import TextQuestion from "./TextQuestion.vue";
import Results from "./Results.vue";
import NewsletterForm from "./NewsletterForm.vue";
import VueSlideBar from "vue-slide-bar";

export default {
  name: "Questions",
  components: {
    NewsletterForm,
    Results,
    TextQuestion,
    MultipleChoiceQuestion,
    RangeQuestion,
    SimpleQuestion,
    VueSlideBar
  },
  props: {
    questions: Object,
    answersGiven: Object,
    survey: Number,
    layout: String,
    newsletter: String,
    isFrontpage: {
      type: Boolean,
      default: false
    },
    postLink: {
      type: String,
      default: ''
    },
    surveyTitle: String,
    status: String
  },
  mounted() {
    if(this.layout == 'paginate'){
      if(this.isQuestionAnswered(this.question_ids[this.current])) this.current++;
    }
  },
  watch:{
    current(){
      if(this.isQuestionAnswered(this.question_ids[this.current])) this.current++;
    }
  },
  data() {
    return {
      answered: this.answersGiven.answered,
      answers: this.answersGiven.answers,
      question_ids: Object.keys(this.questions),
      current: 0,
      sliderStyle: {
        backgroundColor: '#5c97d0'
      }
    }
  },
  methods: {
    isQuestionAnswered(question_id) {
      return !!this.answered.filter((answer) => {
        return answer == question_id;
      }).length;
    },
    newAnswer(e) {
      this.answered.push(e.question_id);
      this.answered = [...new Set(this.answered)];

      setTimeout(() => this.current++, 500);
    },
    showQuestion(index) {

      if (this.layout != 'paginate') {
        return true;
      }


      if (index == this.question_ids[this.current]) {

        // bug in range slider trigger resize event to fix
        setTimeout(() => window.dispatchEvent(new Event('resize')), 100);
        return true;
      }

      return false;
    }
  },
  computed: {
    slideRange() {
      var keys = this.question_ids.keys();
      return [...keys];
    },
    slideLabels() {
      var labels = []
      this.slideRange.forEach((i) => labels.push({label: 'Frage ' + (i + 1)}));
      return labels;
    }
  }
}
</script>

<style scoped lang="scss">
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
  opacity: 0;
  position: absolute;
}

.fade-enter {
  transition-delay: .5s;
}
</style>