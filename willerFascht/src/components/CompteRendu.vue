<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      mode: false,
      user: [],
      compterendus: [],
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);
    // if (localStorage.getItem("user") != null) {
    //   this.mode = true;
    // }

    let fetched_compterendus = await fetch(
      "http://127.0.0.1:8000/api/compterendu"
    );
    this.compterendus = await fetched_compterendus.json();
    console.table(this.compterendus);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
  },
};
</script>
<template>
  <h1>Compte Rendu</h1>
  <router-link :to="`/addCompterendu`">
      <button
        href="#"
        class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700"
      >
        Ajouter le nouveau compte rendu 
      </button></router-link
    >
  <table>
   
      <thead>
        <th>Date</th>
        <th>Participants</th>
        <th v-show="user.niveau == '1'">Supprimer</th>
      </thead>
      <tbody>
        <tr v-for="compterendu in compterendus">
          
          <td>
            <router-link
                :to="`/detail/${compterendu.id}`"
       
              >
            {{ compterendu.date }}
            </router-link>
          </td>

          <td>
            {{ compterendu.participants }}
          </td>

          <td v-show="user.niveau == '1'">
            <button @click="delete_compterendu(compterendu)" title="Effacer ?">
              X
            </button>
          </td>
        </tr>
      </tbody>
    </table>
</template>
<style>
.compterendu {
  margin-top: 8% !important;
  margin-left: 3%;
  width: 95%;
}
</style>
