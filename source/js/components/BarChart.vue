<script>
import { HorizontalBar } from 'vue-chartjs'

export default {
  extends: HorizontalBar,
  props : ['data'],
  data(){
    return{
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
      }
    }
  },
  mounted () {
    // this.chartData is created in the mixin.
    // If you want to pass options please create a local options object
    this.renderChart(this.data, {
      legend : {
        display: false
      },
      responsive: true,
      maintainAspectRatio: false,
      indexAxis: 'y',
      tooltips:{
        enabled:false
      },
      scales: {
        xAxes: [{
          gridLines: {
            display:false
          },
          "ticks":{"beginAtZero":true, display: false}
        }],
        yAxes: [{
          gridLines: {
            display:false
          },
          "ticks":{"beginAtZero":true}
        }],
      }
    })
  }
}
</script>