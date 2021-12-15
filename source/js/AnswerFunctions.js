export default class AnswerFunctions {

    constructor(question) {
        this.question = question;
    }

    updateStatus() {
        return this.question.$parent.isAnswered(this.question.question.id);
    }

    saveAnswer(value){
        this.question.$emit('answer', {
            id: this.question.question.id,
            answer: value
        })
    }

}