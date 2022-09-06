<template>
  <modal
    name="add-extra-attribute"
    classes="px-10 py-8 bg-card rounded-lg text-default w-1/2"
    height="auto"
    width="90%"
    :maxWidth="450"
    :adaptive="true"
    @before-close="beforeClose"
  >
    <h3 class="text-2xl">Add extra attribute</h3>   
    <p v-if="error" class="text-red-600 my-3">{{this.error}}</p>
    <form @submit.prevent="store" class="flex flex-1 mt-2 flex-col md:flex-row">
      <div class="w-full">
        <div>
          <label for="name" class="block"> Attribute name </label>
          <input
            class="py-2 px-1 w-full border border-gray-400 rounded-sm"
            placeholder="Name"
            name="name"
            id="name"
            type="text"
            autofocus
            autocomplete="name"
            v-model="name"
            required
          />
        </div>
        <div class="mt-2">
          <label for="value" class="block"> Value </label>
          <input
            id="value"
            class="py-2 px-1 w-full border border-gray-400 rounded-sm"
            placeholder="Value"
            name="value"
            type="text"
            v-model="value"
            autocomplete="value"
          >
        </div>
        <button class="button-primary mt-3">Add attribute</button>
      </div>
    </form>
  </modal>
</template>
<script>

export default {
  props: {
    initialLead: {
      default: function () {
        return {};
      },
    },
  },
  data() {
    return {
      lead: this.initialLead,
      name:'',
      value:'',
      error:''
    }
  },
  methods: {
    beforeClose(event) {
        this.error = "";
    },
    store:function(){
        let data = new FormData();
        data.append('name', this.name);
        data.append('value', this.value);
        axios.post('/leads/'+this.lead.id+'/attributes', data, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }).then((response) => {
            if (response.status == 204) {
                location.reload();
            }
        }).catch((error)=>{
            this.error=error.response.data.message
        })
    }
  },
};
</script>