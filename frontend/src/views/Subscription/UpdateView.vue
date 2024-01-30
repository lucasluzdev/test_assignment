<template>
  <section class="container-fluid bg-emerald-50">
    <div class="container flex flex-col justify-center items-center h-screen">
      <h2
        class="text-4xl text-center font-bold bg-emerald-600 p-2 w-full text-white"
      >
        Update subscriber
      </h2>

      <div v-if="errors.length" class="my-5">
        <b class="text-red-400 text-xl">Please, check the following errors:</b>
        <ul>
          <li v-for="error in errors" class="text-red-600 text-lg">
            {{ error }}
          </li>
        </ul>
      </div>

      <form class="w-full border border-grey px-2 bg-sky-100">
        <div class="flex flex-row justify-around my-10 gap-x-10">
          <div class="w-full">
            <label>First name</label>
            <input
              type="text"
              v-model="model.subscription.first_name"
              class="bg-grey-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 w-full"
              required
            />
          </div>
          <div class="w-full">
            <label>Last name</label>
            <input
              type="text"
              v-model="model.subscription.last_name"
              class="bg-grey-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 w-full"
              required
            />
          </div>
        </div>

        <div class="flex flex-row justify-around my-10 gap-x-10">
          <div class="w-full">
            <label>Email</label>
            <input
              type="text"
              class="bg-grey-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 w-full"
              v-model="model.subscription.email"
              required
            />
          </div>
          <div class="w-full">
            <label>Confirm email</label>
            <input
              type="text"
              class="bg-grey-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 w-full"
              v-model="model.subscription.confirm_email"
              required
            />
          </div>
        </div>

        <div class="flex flex-row justify-center my-10 gap-x-10">
          <div class="w-full">
            <label>Current status</label>
            <select
              class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 w-full"
              v-model="model.subscription.status"
            >
              <option value="2">Subscribed</option>
              <option value="1">Unsubscribed</option>
            </select>
          </div>
          <div class="w-full flex justify-between items-center">
            <RouterLink to="/" class="w-[45%]">
              <button
                type="button"
                class="mt-5 focus:outline-none text-black bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-red-300 font-medium rounded-lg w-[100%] h-[40px]"
              >
                Cancel
              </button>
            </RouterLink>

            <button
              type="button"
              @click="updateSubscription"
              class="mt-5 focus:outline-none text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg w-[45%] h-[40px]"
            >
              Update
            </button>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
import { update, readOneByID } from "../../api/subscription";
import { validateData } from "@/helpers/input";

export default {
  name: "update-subscription",
  data() {
    return {
      errors: [],
      data: {},
      model: {
        subscription: {
          id: this.$route.params.id,
          first_name: "",
          last_name: "",
          email: "",
          confirm_email: "",
          status: 2,
        },
      },
    };
  },

  mounted() {
    readOneByID(this.$route.params.id).then((response) => {
      this.model.subscription = response.data.data[0];
    });
  },

  methods: {
    updateSubscription() {
      const { subscription } = this.model;
      this.errors = [];
      const isValid = validateData(subscription);
      if (isValid.error) {
        this.$swal.fire({
          title: "Warning!",
          icon: "warning",
          showConfirmButton: false,
          timer: 1200,
        });
        this.errors = isValid.errors;
      } else {
        update(isValid.data).then((response) => {
          this.data = response.data;
          if (this.data.status === 200) {
            this.model.subscription = {};
            this.$swal
              .fire({
                title: "success!",
                icon: "success",
                showConfirmButton: false,
                timer: 1200,
              })
              .then(() => this.$router.push("/"));
          } else if (data.status === 1062) {
            const description = `The email ${isValid.data.email} is already being used by another user. Try another!`;
            this.$swal.fire({
              title: "Warning",
              description,
              icon: "warning",
              showConfirmButton: false,
              timer: 1200,
            });
            this.errors.push(description);
          } else {
            this.$swal.fire({
              title: "Error!",
              description:
                "Error while trying to update subscriber information. Try again later!",
              icon: "error",
              showConfirmButton: false,
              timer: 1200,
            });
          }
        });
      }
    },
  },
};
</script>

<style>
.container {
  width: 60dvw;
}
</style>
