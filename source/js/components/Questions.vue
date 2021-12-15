<template>
  <div class="relative">
    <div v-for="(question, index) in questions" :key="question.id" v-if="answered.length < question_ids.length">
      <transition name="fade">
        <div v-show="showQuestion(index)">
          <simple-question
              :question="question"
              v-if="question.type == 'truefalse'"
              @answer="saveAnswer"
          ></simple-question>
        </div>
      </transition>
      <transition name="fade">
        <div v-show="showQuestion(index)">
          <range-question
              :question="question"
              v-if="question.type == 'range'"
              @answer="saveAnswer"
          ></range-question>
        </div>
      </transition>
      <transition name="fade">
        <div v-show="showQuestion(index)">
          <multiple-choice-question
              :question="question"
              v-if="question.type == 'multiplechoice'"
              @answer="saveAnswer"
          ></multiple-choice-question>
        </div>
      </transition>
      <transition name="fade">
        <div v-show="showQuestion(index)">
          <text-question
              :question="question"
              v-if="question.type == 'text'"
              @answer="saveAnswer"
          ></text-question>
        </div>
      </transition>
    </div>
    <div v-show="current + 1 < question_ids.length && answered.length < question_ids.length">
      <p class="text-xs cursor-pointer text-gray-600 w-full text-right -mt-5 mb-5" @click="current++">überspringen</p>
    </div>

    <div v-if="answered.length == question_ids.length || current + 1 > question_ids.length">
      <results :questions="questions" :survey="survey"></results>
    </div>


    <div v-if="newsletter == 'allways'">
      <div>
        <h2 class="text-center">Zum Abschluss ein E-Mail?</h2>
        <p>Melden Sie sich zu unserem Umfragen-Newsletter an, wir informieren Sie regelmäßig über die Ergebnisse unserer Umfragen.</p>
        <form class="js-cm-form" id="subForm" action="https://www.createsend.com/t/subscribeerror?description=" method="post" data-id="30FEA77E7D0A9B8D7616376B90063231D64469A1EA51DCFD4422C27B24190228EF9BD565173221EF9A19512E227B9DADF9C4E947AC85335607FE668A3062818C">
          <div>
            <div>
              <label class="block text-gray-600 font-semibold">Name </label>
              <input aria-label="Name"
                     id="fieldName" maxlength="200"
                     name="cm-name"
                     placeholder="Name"
                     class="block border border-gray-600 p-2 w-full"
              >
            </div>
            <div>
              <label class="block text-gray-600 font-semibold">E-Mail-Adresse </label>
              <input autocomplete="Email"
                     aria-label="E-Mail-Adresse"
                     class="js-cm-email-input qa-input-email block border border-gray-600 p-2 w-full"
                     id="fieldEmail"
                     maxlength="200"
                     name="cm-yumilj-yumilj"
                     placeholder="E-Mail-Adresse"
                     required=""
                     type="email">
            </div>
            <div>
              <div>
                <input id="egfy" name="cm-ol-egfy" type="checkbox" value="egfy">
                <label for="egfy">Auch zum Standardnewsletter der unabhängigen Immobilien Redaktion anmelden</label>
              </div>
            </div>
            <div>
              <div>
                <div>
                  <input aria-required="" id="cm-privacy-consent" name="cm-privacy-consent" required="" type="checkbox">
                  <label for="cm-privacy-consent">Ich stimme dem Erhalt von E-Mails der Immobilien Redaktion zu</label>
                </div>
                <input id="cm-privacy-consent-hidden" name="cm-privacy-consent-hidden" type="hidden" value="true">
              </div>
            </div>
          </div>
          <button type="submit" class="bg-primary-100 text-center text-white py-2 w-full mt-4">anmelden</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Cookies from "js-cookie";
import Axios from "axios";
import Qs from "qs";
import SimpleQuestion from "./SimpleQuestion.vue";
import RangeQuestion from "./RangeQuestion.vue";
import MultipleChoiceQuestion from "./MultipleChoiceQuestion.vue";
import TextQuestion from "./TextQuestion.vue";
import Results from "./Results.vue";

export default {
  name: "Questions",
  components: {
    Results,
    TextQuestion,
    MultipleChoiceQuestion,
    RangeQuestion,
    SimpleQuestion
  },
  props: ['questions', 'survey', 'layout', 'newsletter'],
  data() {
    return {
      answered: [],
      question_ids: Object.keys(this.questions),
      current: 0
    }
  },
  mounted() {

    var cookie = Cookies.get('survey_' + this.survey);
    this.answered = Object.values(Qs.parse(cookie));

  },
  methods: {
    saveAnswer(e) {

      Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
        action: 'qsy_save_answer',
        survey_id: this.survey,
        question_id: e.id,
        answer: e.answer
      }))
          .then((response) => {
            this.answered.push(e.id);
            Cookies.set('survey_' + this.survey, Qs.stringify(this.answered));
            this.$emit('answer_saved');
            this.current++;
          });
    },
    isAnswered(question) {
      var answered = Object.values(Qs.parse(Cookies.get('survey_' + this.survey)));

      if (!Array.isArray(answered)) return false;

      return answered.includes(question);
    },
    showQuestion(index) {

      if (this.layout != 'paginate') {
        return true;
      }

      if (index == this.question_ids[this.current]) {
        return true;
      }

      return false;

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