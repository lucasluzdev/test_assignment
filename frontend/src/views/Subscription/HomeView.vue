<template>
  <section class="container-fluid bg-emerald-50 pt-10 h-screen">
    <div class="container">
      <h1 class="text-4xl text-center font-bold text-emerald-600">
        Subscribers Dashboard
      </h1>
      <p class="text-center">Manage your subscribers.</p>

      <div class="flex justify-between gap-x-4 mt-10">
        <div class="w-[80%] mt-[18px]">
          <RouterLink to="/create">
            <button
              class="bg-sky-500 text-white p-2 w-[70%] flex flex-row justify-around items-center"
            >
              <img :src="Plus" class="h-[28px] w-[28px]" />
              <span class="w-[90%]">New subscriber</span>
            </button>
          </RouterLink>
        </div>

        <div class="w-[80%] flex flex-col">
          <label>Search</label>
          <input
            type="text"
            class="bg-grey-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-100 p-2"
            v-model="search"
            @keyup="debounceInput"
            required
          />
        </div>

        <div class="w-[60%] flex flex-col">
          <label>Sort by</label>
          <select
            class="bg-grey-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-100 p-2"
            v-model="this.sort"
            @change="debounceInput"
          >
            <option value="created-desc" selected>Newest subscribers</option>
            <option value="created-asc">Oldest subscribers</option>
            <option value="user-asc">User ascending</option>
            <option value="user-desc">User descending</option>
            <option value="email-asc">Email ascending</option>
            <option value="email-desc">User descending</option>
            <option value="status-asc">Unsubscribed</option>
          </select>
        </div>

        <div class="w-[40%] flex flex-col">
          <label>Page size</label>
          <select
            class="bg-grey-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-100 p-2"
            v-model="this.pageSize"
            @change="debounceInput"
          >
            <option value="10">10</option>
            <option value="30">30</option>
            <option value="50">50</option>
          </select>
        </div>
      </div>

      <table
        class="table-fixed w-full text-sm text-left rtl:text-right text-gray-500 mt-5"
      >
        <thead class="text-xs text-white uppercase bg-emerald-600">
          <tr>
            <th scope="col" class="px-6 py-3 text-lg">User</th>
            <th scope="col" class="px-6 py-3 text-lg">Email</th>
            <th scope="col" class="px-6 py-3 text-lg w-[120px]">Status</th>
            <th scope="col" class="px-6 py-3 text-lg w-[100px]">Edit</th>
            <th scope="col" class="px-6 py-3 text-lg w-[100px]">Remove</th>
          </tr>
        </thead>
        <tbody class="bg-emerald-50 border-b text-black" v-if="this.subscriptions.length > 0">
          <tr
            v-for="(subscription, index) in itemsToDisplay"
            :key="index"
            class="border border-grey-900 hover:bg-green-100"
          >
            <td class="px-6 py-2">
              {{ `${subscription.first_name} ${subscription.last_name}` }}
            </td>
            <td class="px-6 py-2">{{ subscription.email }}</td>
            <td
              class="px-6 py-2 font-bold"
              :style="{ color: subscription.status === 2 ? 'green' : 'red' }"
            >
              {{ subscription.status === 2 ? "Subscribed" : "Unsubscribed" }}
            </td>
            <td class="px-6 py-2">
              <RouterLink
                :to="{ name: 'update', params: { id: subscription.id } }"
              >
                <button
                  class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg h-[40px] w-[50px]"
                >
                  <img
                    :src="Pencil"
                    class="h-[20px] w-[20px]"
                    style="margin: auto"
                  />
                </button>
              </RouterLink>
            </td>
            <td class="px-6 py-2">
              <button
                type="button"
                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg h-[40px] w-[50px]"
                @click="deletionAlert(subscription.id)"
              >
                <img
                  :src="TrashCan"
                  class="h-[20px] w-[20px]"
                  style="margin: auto"
                />
              </button>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="5" class="text-center">No users</td>
          </tr>
        </tbody>
      </table>

      <nav class="mt-8 flex justify-center">
        <ul class="flex flex-row gap-x-2">
          <li>
            <button @click="firstPage" :disabled="currentPage === 1" class="bg-emerald-600 hover:bg-emerald-500 text-white w-[80px] h-[40px]">
              First
            </button>
          </li>
          <li>
            <button @click="prevPage" :disabled="currentPage === 1" class="bg-emerald-600 hover:bg-emerald-500 text-white w-[80px] h-[40px]">
              Previous
            </button>
          </li>


          <li v-for="page in pageNumbers" :key="page" @click="goToPage(page)">
            <button
              :style="{
                backgroundColor:
                  this.currentPage === page ? 'black' : '#10B981',
              }"
              class="bg-emerald-600 hover:bg-emerald-500 text-white w-[40px] h-[40px]"
              :disabled="currentPage === page"
            >
              {{ page }}
            </button>
          </li>


          <li>
            <button
              @click="nextPage"
              :disabled="currentPage >= pageCount + 1"
              class="bg-emerald-600 hover:bg-emerald-500 text-white w-[80px] h-[40px]"
            >
              Next
            </button>
          </li>
          <li>
            <button
              @click="lastPage"
              :disabled="currentPage >= pageCount + 1"
              class="bg-emerald-600 hover:bg-emerald-500 text-white w-[80px] h-[40px]"
            >
              Last
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </section>
</template>

<script>
import { readMany, readOne, remove } from "@/api/subscription";
import { debounce } from "@/helpers/input";
import Pencil from "@/assets/icons/pencil.svg";
import TrashCan from "@/assets/icons/trash-can.svg";
import Plus from "@/assets/icons/plus.svg";

export default {
  name: "home",
  setup() {
    return {
      Pencil,
      TrashCan,
      Plus,
    };
  },
  data() {
    return {
      tableBody: 0,
      subscriptions: [],
      pageSize: 10,
      currentPage: 1,
      totalItems: 10,
      pagesToShow: 10,
      search: "",
      sort: "created-desc",
    };
  },

  computed: {
    pageCount() {
      return Math.ceil(this.totalItems / this.pageSize);
    },
    itemsToDisplay() {
      return this.subscriptions;
    },
    debounceInput() {
      this.currentPage = 1;
      return debounce(this.searchSubscriber, 500);
    },

    pageNumbers() {
      const halfButtons = Math.floor(this.pagesToShow / 2);
      let startPage = Math.max(1, this.currentPage - halfButtons);
      let endPage = Math.min(this.pageCount, this.currentPage + halfButtons);

      const shortfall = this.pagesToShow - (endPage - startPage + 1);

      if (startPage === 1) {
        endPage = Math.min(this.pagesToShow, endPage + shortfall);
      } else if (endPage === this.pagesToShow) {
        startPage = Math.max(1, startPage - shortfall);
      }

      const result = Array.from(
        { length: endPage - startPage + 1 },
        (_, i) => startPage + i
      );

      return result;
    },
  },

  mounted() {
    this.getSubscriptions();
  },

  methods: {
    getSubscriptions() {
      readMany(this.pageSize, this.currentPage, this.sort).then((response) => {
        this.subscriptions = response.data.data;
        this.pageSize = response.data.pageSize;
        this.currentPage = response.data.currentPage;
        this.totalItems = response.data.totalItems;        
      });
    },

    searchSubscriber() {
      readOne(this.search, this.pageSize, this.currentPage, this.sort).then(
        (response) => {
          this.subscriptions = response.data.data;
        }
      );
    },

    deletionAlert(id) {
      this.$swal
        .fire({
          title: "Warning",
          text: "Are you sure you want to remove this user?",
          icon: "warning",
          showCloseButton: true,
          showCancelButton: true,
        })
        .then((result) => {
          if (result.isConfirmed) {
            remove(id).then((response) => {
              if (response.data.status === 200) {
                this.$swal.fire("Subscription removed successfully!");
                if (this.search.length > 0) {
                  this.searchSubscriber();
                } else {
                  this.getSubscriptions();
                }
              }
            });
          }
        });
    },

    firstPage() {
      this.currentPage = 1;
      this.getSubscriptions();
    },
    lastPage() {
      this.currentPage = Math.ceil(this.totalItems / this.pageSize);
      this.getSubscriptions();
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.getSubscriptions();
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.getSubscriptions();
      }
    },
    goToPage(pageNumber) {
      this.currentPage = pageNumber;
      this.getSubscriptions();
    },
  },
};
</script>

<style>
.container {
  margin: auto;
}

.table {
  width: 100dvh;
}

.table tr {
  width: 100%;
}

.table td {
  text-align: center;
  font-size: 1.4rem;
}

.table th {
  font-size: 1.6rem;
}

.pagination button {
  height: 40px;
  width: 40px;
}
</style>
