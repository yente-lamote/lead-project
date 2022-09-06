<template>
  <div>
    <p v-if="error" class="text-red-600 mb-3">{{ this.error }}</p>
    <div class="name-description-dropdown-container">
      <p class="employee-label">Change {{ attributeName }}</p>
      <div class="relative mt-2">
        <div class="inline-flex shadow-sm w-full rounded-md divide-x divide-blue-700">
          <div
            class="relative z-0 inline-flex w-full shadow-sm rounded-md divide-x divide-blue-700"
          >
            <div
              class="relative inline-flex w-full items-center bg-blue-600 py-2 pl-3 pr-4 border border-transparent rounded-l-md shadow-sm text-white"
            >
              <svg
                class="h-5 w-5"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
              >
                <path
                  fill-rule="evenodd"
                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  clip-rule="evenodd"
                />
              </svg>
              <p class="ml-2.5 text-sm font-medium">
                {{ this.selectedAttribute.name }}
              </p>
            </div>
            <button
              type="button"
              class="relative inline-flex items-center bg-blue-600 p-2 rounded-l-none rounded-r-md text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:z-10 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-blue-500"
              aria-haspopup="listbox"
              aria-expanded="true"
              aria-labelledby="listbox-label"
              v-on:click="handleDropDownClick"
            >
              <span class="sr-only">Change {{ this.attributeName }}</span>
              <svg
                class="h-5 w-5 text-white"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </div>
        </div>
        <ul
          class="origin-top-right absolute right-0 mt-2 w-11/12 max-h-52 md:max-h-64 overflow-y-auto rounded-md shadow-lg overflow-hidden bg-white divide-y divide-gray-200 ring-1 ring-black ring-opacity-5 focus:outline-none"
          tabindex="-1"
          role="listbox"
          aria-labelledby="listbox-label"
          aria-activedescendant="listbox-option-0"
          v-bind:class="[this.dropdownIsOpen ? 'visible' : 'invisible']"
        >
          <li
            v-for="attribute in attributes"
            :key="attribute.id"
            @click="changeSelected(attribute)"
            v-bind:class="[selectedAttribute.id === attribute.id ? 'bg-blue-200' : '']"
          >
            <div class="flex flex-col">
              <div
                class="flex justify-between"
                v-bind:class="[
                  selectedAttribute.id === attribute.id ? 'font-semibold' : '',
                ]"
              >
                <p class="attribute-name">
                  {{ attribute.name }}
                </p>

                <span
                  class="checkbox-container"
                  v-bind:class="[
                    selectedAttribute.id !== attribute.id ? 'invisible' : '',
                  ]"
                >
                  <svg
                    class="h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </span>
              </div>
              <p class="attribute-description">
                {{ attribute.description }}
              </p>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div v-if="attributeName==='status'" class="mt-2">
      <label for="note" class="employee-label">
        Note
      </label>
      <textarea v-model="note" name="note" id="note" rows="4" class="block p-2 py-3 border outline-none block w-full shadow-sm focus:ring-indigo-500 focus:border-blue-600 border-gray-300 rounded-md" autocomplete="note"></textarea>
    </div>
    <div class="flex justify-center mt-6">
      <button class="button-primary" @click="update">Save changes</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    attributes: {
      default: function () {
        return {};
      },
    },
    initialAttribute: {
      default: function () {
        return {
          name:"Select an item",
          id:0
        };
      },
    },
    attributeName: { default: "" },
    postUrl: { default: "" },
  },
  data() {
    return {
      dropdownIsOpen: false,
      selectedAttribute: this.initialAttribute,
      error: "",
      note:"",
    };
  },
  methods: {
    handleDropDownClick: function () {
      this.dropdownIsOpen = !this.dropdownIsOpen;
    },
    changeSelected: function (attribute) {
      this.selectedAttribute = attribute;
      this.dropdownIsOpen = false;
    },
    update: function () {
      let data = new FormData();
      data.append(this.attributeName + "_id", this.selectedAttribute.id);
      if(this.attributeName==="status")data.append('note',this.note);
      axios
        .post(this.postUrl, data, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.status == 204) {
            location.reload();
          }
        })
        .catch((error) => {
          this.error = error.response.data.message;
        });
    },
  },
};
</script>
