<template>
    <div>
        <span v-if="error" class="text-red-600">{{error}}</span>
        <button class="button-danger" v-on:click="action">
          {{this.buttonText}}
        </button>
    </div>
</template>

<script>
export default {
  props: {
    urlSuffix: { default: "" },
    urlPrefix: { default: "" },
    buttonText: {default:"Archive"},
    companyId: { default: "" },
  },
  data() {
    return {
        error:null,
    };
  },  
  methods: {
    action: function () {
      let inputs = document.querySelectorAll("input[type='checkbox']");
      for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].checked === true) {
            axios.delete(`${this.urlPrefix}/${inputs[i].value}${this.urlSuffix}`).then(()=>{
                location.reload();
            }).catch(()=>{
                this.error="Something went wrong";
            });
        }
      }
    },
  },
};
</script>