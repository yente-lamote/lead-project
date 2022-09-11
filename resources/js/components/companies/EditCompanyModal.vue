<template>
  <modal
    name="edit-company"
    classes="px-10 py-8 bg-card rounded-lg text-default w-1/2"
    height="auto"
    width="90%"
    :maxWidth="680"
    :adaptive="true"
  >
    <h3 class="text-2xl">Edit company</h3>   
    <form @submit.prevent="update" class="flex flex-1 mt-2 flex-col md:flex-row">
        <div class="w-full">
            <img ref="image" class="sm:w-3/4 w-1/2 mx-auto mt-2" :src="'/assets/images/companies/'+company.id+'.png'"  @error="imageLoadError"/>
            <input ref="newImage" class="mt-3" type="file" name="fileToUpload" v-on:change="onFileChange" id="fileToUpload" accept=".png">
            <div class="flex justify-center">
              <span v-if="error" class="text-red-600 block mt-5">{{ this.error }}</span>
            </div>
        </div>
      <div class="w-full">
        <div>
          <label for="name" class="block"> Name </label>
          <input
            class="py-2 px-1 w-full border border-gray-400 rounded-sm"
            v-model="company.name"            
            placeholder="Name"
            name="name"
            id="name"
            type="text"
            autofocus
            autocomplete="name"
            required
          />
        </div>
        <div class="mt-2">
          <label for="Description" class="block"> Description </label>
          <textarea
            id="description"
            class="py-2 px-1 w-full h-40 border border-gray-400 rounded-sm"
            placeholder="description"
            v-model="company.description"
            name="name"
            type="text"
            autocomplete="description"
            required
          ></textarea>
        </div>
        <button class="button-primary mt-3">Update</button>
      </div>
    </form>
  </modal>
</template>
<script>

export default {
  props: {
    initialCompany: {
      default: function () {
        return {};
      },
    },
  },
  data() {
    return {
      company: this.initialCompany,
      error:""
    }
  },
  methods: {
    onFileChange(e) {
      const file = e.target.files[0];
      this.company.url = URL.createObjectURL(file);
      this.$refs.image.src =this.company.url;
    },
    imageLoadError: function(){
      this.$refs.image.src ="/assets/images/companies/default.png";
    },

    update:function(){
      let data = new FormData();
      data.append('name',this.company.name);
      data.append('description',this.company.description);
      if(this.$refs.newImage.files[0]){
        data.append('image',this.$refs.newImage.files[0]);
      }
      axios.post('/companies/'+this.company.id,data,{
          headers: {
          'Content-Type': 'multipart/form-data',
        }
      }).then((response)=>{
        if(response.status==204){
          location.reload();
        }
      }).catch((error) => {
          this.error = error.response.data.message;
      });
    },
  }
};
</script>