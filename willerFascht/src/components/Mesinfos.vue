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
      <button class="button rounded-lg retourintra">Retour</button>
    </router-link>

    <div class="flex items-center h-screen w-full justify-center carduser">
      <div class="max-w-xs">
        <div class="bg-white shadow-xl rounded-lg py-3">
          <div v-for="profil in profils" class="photo-wrapper p-2" style="text-overflow: ellipsis">
            <div v-if="profil.user_id == user.id">
       
          <img
            v-bind:src="profil.url"
            alt=""
            class="w-40 h-40 rounded-full mx-auto"
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
<style>
/* .mesinfos {
  margin-top: 8%;
} */
.retourintra {
  margin-left: 10%;
  margin-bottom: -25px !important;
}
.carduser {
  margin-top: 194px;
  height: 100px !important;
}
</style>
