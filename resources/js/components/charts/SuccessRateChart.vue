<template>
  <canvas v-bind:id="chartId" width="20" height="20"></canvas>
</template>

<script>
    export default {
        props: {
            chartId:"",
            successRate: "",
            enableTooltips: true,
        },
        data() {
            return {
              colors:[
                'rgb(37, 99, 235)',      
                'rgba(37, 99, 235,0.2)',              
              ],
            };
        },

        mounted:function(){
            var ctx = document.getElementById(this.chartId).getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                labels: ['Positive leads','Other leads excl. New'],
                datasets: [
                  {
                    label: 'Dataset 1',
                    data: [this.successRate,100-this.successRate],
                    backgroundColor: this.colors,
                    }
                  ]
                },
                options:{
                  responsive: true,
                  maintainAspectRatio: true, 
                  cutoutPercentage: 70,
                  legend:{
                    display:false,
                  },
                  tooltips: {
                    enabled: this.enableTooltips,
                    mode: 'single',
                    callbacks: {
                           label: function (tooltipItems,data) {
                             var i = tooltipItems.index;
                              return data.labels[i] + ": " + data.datasets[0].data[i] + "%";
                           }
                  }
                  },
                  elements:{
                    arc: {
                        borderWidth: 0
                    }
                  }
                },
            });
        }
    }
</script>