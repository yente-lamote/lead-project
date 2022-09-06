<template>
  <canvas id="leadsDoughnut" width="20" height="8"></canvas>
</template>

<script>
export default {
  props: {
    initialLabels: { default: null },
    initialData: { default: null },
  },
  data() {
    return {
      labels: this.initialLabels,
      data: this.initialData,
      colors: [
        "rgb(38, 235, 61)",
        "rgb(41, 109, 255)",
        "rgb(255, 157, 74)",
        "rgb(235, 41, 38)",
      ],
    };
  },
  methods: {
    sortData: function () {
      let parentThis = this;
      let arrayOfObj = this.initialLabels.map(function (d, i) {
        return {
          label: d,
          data: parentThis.initialData[i] || 0,
          color: parentThis.colors[i],
        };
      });

      let sortedArrayOfObj = arrayOfObj.sort(function (a, b) {
        if (a.data > b.data) {
          return -1;
        }
        if (a.data < b.data) {
          return 1;
        }
        return 0;
      });
      let sortedLabels = [];
      let sortedData = [];
      let sortedColors = [];
      sortedArrayOfObj.forEach(function (d) {
        sortedLabels.push(d.label);
        sortedData.push(d.data);
        sortedColors.push(d.color);
      });
      this.labels = sortedLabels;
      this.data = sortedData;
      this.colors = sortedColors;
    },
  },
  mounted: function () {
    this.sortData();
    var ctx = document.getElementById("leadsDoughnut").getContext("2d");
    var myChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: this.labels,
        datasets: [
          {
            data: this.data,
            backgroundColor: this.colors,
          },
        ],
      },
      options: {
        rotation: 1 * Math.PI,
        circumference: 1 * Math.PI,
        responsive: true,
        maintainAspectRatio: true,
        legend: {
          position: "right",
          labels: {
            boxWidth: 20,
            padding: 10,
          },
        },
        title: {
          display: true,
          text: "Leads status distribution",
        },
      },
    });
  },
};
</script>