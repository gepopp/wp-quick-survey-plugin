import Qs from "qs";
import Axios from "axios";

export default class AnswerFunctions {

    constructor(question) {
        this.question = question;
    }

    saveAnswer(answer) {

        Axios.post(qsy_xhr.ajaxurl, Qs.stringify({
            action: 'qsy_save_answer',
            survey_id: this.question.question.survey,
            question_id: this.question.question.id,
            answer: answer
        }))
            .then((response) => {
                this.question.$emit('answer', response.data);
            });
    }

}