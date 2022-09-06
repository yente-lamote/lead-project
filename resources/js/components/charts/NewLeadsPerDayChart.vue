<template>
        <div class="w-full p-4">
            <canvas id="chart"></canvas>
        </div>    
</template>

<script>
    export default {
        props: {
            initialData: { default: "[]" },
        },
        data() {
            return {
                data: JSON.parse(this.initialData),
                //rgb value
                lineColor: '37, 99, 235',
            };
        },
        mounted:function(){
            var ctx = document.getElementById('chart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.data.map(function({created_at}){
                            let date = new Date(created_at);
                            return date.getDate() + " "+date.toLocaleString("en-US", { month: 'short'}) ;
                        }),
                    datasets: [{
                        label: 'Amount of new leads per day',
                        data: this.data.map(function({amount}){
                            return amount;
                        }),
                        fill:false,
                        lineTension:0,
                        borderColor :`rgba(${this.lineColor},0.5)`,
                        pointBorderColor: `rgb(${this.lineColor})`,
                        pointBackgroundColor:`rgb(${this.lineColor})`,
                    }]
                },
                options: {
                    bezierCurve : false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision:0,
                            }
                        }]
                    },
                }
            });
        }
    }
</script>