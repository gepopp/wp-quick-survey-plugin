<script>
import {HorizontalBar, mixins} from 'vue-chartjs'

const {reactiveProp} = mixins;

export default {
  extends: HorizontalBar,
  mixins: [reactiveProp],
  props: ['labels', 'values'],
  data() {
    return {
      options: {
        legend: {
          display: false
        },
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        tooltips: {
          enabled: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false
            },
            "ticks": {"beginAtZero": true, display: false}
          }],
          yAxes: [{
            gridLines: {
              display: false
            },
            "ticks": {"beginAtZero": true}
          }],
        }
      }
    }
  },
  mounted() {
    // this.chartData is created in the mixin.
    // If you want to pass options please create a local options object
    this.renderChart(this.chartData, this.options)
  },
  methods: {
  }
}
</script>