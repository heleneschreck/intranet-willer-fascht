<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      mode: false,
      user: [],
      profils: []
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
    let fetched_profils = await fetch("http://127.0.0.1:8000/api/profils");
    this.profils = await fetched_profils.json();
    console.table(this.profils);
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
  <div class="mesinfos">
    <router-link :to="`/accueilintra`">
      <button class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste">Retour</button>
    </router-link>

    <div class="flex items-center h-screen w-full justify-center carduser">
      <div class="max-w-xs">
        <div class="bg-white shadow-xl rounded-lg py-3" >
          <div v-for="profil in profils" class="photo-wrapper p-2" style="text-overflow: ellipsis">
            <div v-if="profil.user_id == user.id">
       
          <img
            v-bind:src="profil.url"
            alt=""
            class="w-60 h-60 rounded-full mx-auto"
            
          />
         </div>
          </div>
          <div class="p-2">
            <h3 class="text-center text-xl text-gray-900 font-medium leading-8">
              {{ user.name }} {{ user.prenom }}
            </h3>
            <!-- <div class="text-center text-gray-400 text-xs font-semibold">
                <p>Web Developer</p>
            </div> -->
            <table class="text-xs my-3">
              <tbody>
                <tr>
                  <!-- <td class="px-2 py-2 text-gray-500 font-semibold">Mot de passe</td>
                    <td class="px-2 py-2">{{user.password}}</td> -->
                </tr>
                <tr>
                  <td class="px-2 py-2 text-gray-500 font-semibold">
                    Télèphone
                  </td>
                  <td class="px-2 py-2">{{ user.telephone }}</td>
                </tr>
                <tr>
                  <td class="px-2 py-2 text-gray-500 font-semibold">Email</td>
                  <td class="px-2 py-2">{{ user.email }}</td>
                </tr>
              </tbody>
            </table>

            <div class="text-center my-3">
              <router-link :to="`/updateinfos/${user.id}`">
                <a
                  class="text-xs text-indigo-500 italic hover:underline hover:text-indigo-600 font-medium"
                  href="#"
                  >Modifier mes infos</a
                >
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
/* .mesinfos {
  margin-top: 8%;
} */
.retourintra {
  margin-left: 10%;
  margin-bottom: 45px !important;
  border-radius: 20px;
  width: 200px;
  border: 5px solid rgb(70, 137, 226);
  background-color: rgb(70, 137, 226);
  color: white;
  font-weight: bold;
  font-size: 20px;
  height: 70px;
  margin-bottom: 5% ;
}
.carduser {
  margin-top: 220px !important;
  height: 100px !important;
  
}
td{
  border: 1px solid black;
}
</style>
