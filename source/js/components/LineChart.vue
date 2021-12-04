<script>

import {Line, mixins} from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
  extends: Line,
  mixins:[reactiveProp],
  props: ['labels'],
  data() {
    return {
      options: {
        line:{
          lineTension: 0
        },
        layout:{
          padding: 10
        },
        legend: {
          display: false
        },
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
          enabled: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false
            },
            "ticks": {"beginAtZero": true}
          }],
          yAxes: [{
            gridLines: {
              display: false
            },
            "ticks": {"beginAtZero": true, display: false}
          }],
        }
      },
      chartData:{
        labels: this.labels,
        datasets:[
          {
            label: '',
            data: this.labels
          }
        ]
      }
    }
  },
  mounted () {
    this.renderChart(this.chartData, this.options)
  },
  methods: {
    updateChartdata(labels, values) {

      this.chartData = {
          labels: labels,
          datasets: [
            {
              label: '',
              data: values,
              backgroundColor: '#5C97D0',
            }
          ]
      };
    }
  }
}
</script>

<style scoped>

</style>