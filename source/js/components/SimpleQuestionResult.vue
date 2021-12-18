<template>
    <div>
      <bar-chart :chart-data="chartData(question.id)"></bar-chart>
    </div>
</template>

<script>
import BarChart from "./BarChart.vue";

export default {
  name: "SimpleQuestionResult",
  props: ['question', 'answers']
}
</script>

<style scoped>

</style>