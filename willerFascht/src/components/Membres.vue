<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      mode: false,
      user: [],
      membres: [],
      mode: "nodisplay",
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    console.log(this.user);

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    console.table(this.membres);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    delete_membres: function (membre) {
      console.log(membre.id);

      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://127.0.0.1:8000/api/users/" + membre.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    display: function () {
      this.mode = "display";
    },
    survol: function () {
      this.mode = "nodisplay";
    },
    ChangeStatut: function (membre) {
      console.log(membre.niveau);
      if (membre.niveau == "1") {
        console.log("Vous êtes administrateur");
        var myHeaders = new Headers();

        var urlencoded = new URLSearchParams();
        urlencoded.append("niveau", "0");

        var requestOptions = {
          method: "PUT",
          body: urlencoded,
          redirect: "follow",
        };
        let url = "http://127.0.0.1:8000/api/users/" + membre.id;
        fetch(url, requestOptions)
          .then((response) => response.text())
          .then((result) => console.log(result))
          .catch((error) => console.log("error", error));
        setTimeout(() => {
          this.$router.go(this.$router.currentRoute);
        }, "2000");
      } else {
        console.log("Vous êtes un utilisateur");
        var myHeaders = new Headers();

        var urlencoded = new URLSearchParams();
        urlencoded.append("niveau", "1");

        var requestOptions = {
          method: "PUT",
          body: urlencoded,
          redirect: "follow",
        };
        let url = "http://127.0.0.1:8000/api/users/" + membre.id;
        fetch(url, requestOptions)
          .then((response) => response.text())
          .then((result) => console.log(result))
          .catch((error) => console.log("error", error));
        setTimeout(() => {
          this.$router.go(this.$router.currentRoute);
        }, "2000");
      }
    },
  },
};
</script>
<template>
  <router-link :to="`/accueilintra`">
    <button
      class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-white dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700 retourliste"
    >
      Retour
    </button>
  </router-link>
  <br>
  <div class="membres">
    <table>
      <caption>
        Membres de l'association
      </caption>
      
      <thead>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Télèphone</th>
        <th>Email</th>
        <th v-show="user.niveau == '1'">Niveau</th>
        <th v-show="user.niveau == '1'">Supprimer</th>
      </thead>
      <div v-show="user.id == user.id"></div>
      <tbody>
        <tr v-for="membre in membres">
          <td @mouseover="survol()">
            {{ membre.name }}
          </td>

          <td @mouseover="survol()">
            {{ membre.prenom }}
          </td>
          <td @mouseover="survol()">
            {{ membre.telephone }}
          </td>
          <td @mouseover="survol()">
            {{ membre.email }}
          </td>
          <!-- <router-link :to="`/todo`">
<button  class="button rounded-lg retourtodo">Retour</button>
</router-link> -->
          <td v-show="user.niveau == '1'">
            <div v-if="membre.niveau == 1">
              Administrateur
              <br />
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  type="checkbox"
                  value=""
                  @click="ChangeStatut(membre)"
                  class="sr-only peer"
                  checked
                />
                <div
                  class="group peer ring-0 bg-gradient-to-r from-rose-400 to-red-900 rounded-full outline-none duration-700 after:duration-300 w-24 h-12 shadow-md peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-emerald-900 peer-focus:outline-none after:content-[''] after:rounded-full after:absolute after:bg-gray-50 after:outline-none after:h-10 after:w-10 after:top-1 after:left-1 peer-checked:after:translate-x-12 peer-hover:after:scale-95"
                >
                  <svg
                    class="group-hover:scale-75 duration-300 absolute top-1 left-12 stroke-gray-900 w-10 h-10"
                    height="100"
                    preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 100 100"
                    width="100"
                    x="0"
                    xmlns="http://www.w3.org/2000/svg"
                    y="0"
                  >
                    <path
                      class="svg-fill-primary"
                      d="M50,18A19.9,19.9,0,0,0,30,38v8a8,8,0,0,0-8,8V74a8,8,0,0,0,8,8H70a8,8,0,0,0,8-8V54a8,8,0,0,0-8-8H38V38a12,12,0,0,1,23.6-3,4,4,0,1,0,7.8-2A20.1,20.1,0,0,0,50,18Z"
                    ></path>
                  </svg>
                  <svg
                    class="group-hover:scale-75 duration-300 absolute top-1 left-1 stroke-gray-900 w-10 h-10"
                    height="100"
                    preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 100 100"
                    width="100"
                    x="0"
                    xmlns="http://www.w3.org/2000/svg"
                    y="0"
                  >
                    <path
                      d="M30,46V38a20,20,0,0,1,40,0v8a8,8,0,0,1,8,8V74a8,8,0,0,1-8,8H30a8,8,0,0,1-8-8V54A8,8,0,0,1,30,46Zm32-8v8H38V38a12,12,0,0,1,24,0Z"
                      fill-rule="evenodd"
                    ></path>
                  </svg>
                </div>
              </label>
            </div>
            <div v-else>
              Utilisateur
              <br />
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  type="checkbox"
                  value=""
                  @click="ChangeStatut(membre)"
                  class="sr-only peer"
                />
                <div
                  class="group peer ring-0 bg-gradient-to-r from-rose-400 to-red-900 rounded-full outline-none duration-700 after:duration-300 w-24 h-12 shadow-md peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-emerald-900 peer-focus:outline-none after:content-[''] after:rounded-full after:absolute after:bg-gray-50 after:outline-none after:h-10 after:w-10 after:top-1 after:left-1 peer-checked:after:translate-x-12 peer-hover:after:scale-95"
                >
                  <svg
                    class="group-hover:scale-75 duration-300 absolute top-1 left-12 stroke-gray-900 w-10 h-10"
                    height="100"
                    preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 100 100"
                    width="100"
                    x="0"
                    xmlns="http://www.w3.org/2000/svg"
                    y="0"
                  >
                    <path
                      class="svg-fill-primary"
                      d="M50,18A19.9,19.9,0,0,0,30,38v8a8,8,0,0,0-8,8V74a8,8,0,0,0,8,8H70a8,8,0,0,0,8-8V54a8,8,0,0,0-8-8H38V38a12,12,0,0,1,23.6-3,4,4,0,1,0,7.8-2A20.1,20.1,0,0,0,50,18Z"
                    ></path>
                  </svg>
                  <svg
                    class="group-hover:scale-75 duration-300 absolute top-1 left-1 stroke-gray-900 w-10 h-10"
                    height="100"
                    preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 100 100"
                    width="100"
                    x="0"
                    xmlns="http://www.w3.org/2000/svg"
                    y="0"
                  >
                    <path
                      d="M30,46V38a20,20,0,0,1,40,0v8a8,8,0,0,1,8,8V74a8,8,0,0,1-8,8H30a8,8,0,0,1-8-8V54A8,8,0,0,1,30,46Zm32-8v8H38V38a12,12,0,0,1,24,0Z"
                      fill-rule="evenodd"
                    ></path>
                  </svg>
                </div>
              </label>
            </div>
            <!-- <div
              class="updatenvx"
              v-if="mode == 'display'"
              v-show="user.niveau == '1'"
            >
              <router-link
                :to="`/updateniveau/${membre.id}`"
                title="Changer le statut de l'utilisateur"
              >
                <img
                  src="https://cdn-icons-png.flaticon.com/512/1634/1634406.png"
                  style="width: 30px"
                  alt=""
                />
              </router-link>
            </div> -->
          </td>
          <td @mouseover="survol()" v-show="user.niveau == '1'">
            <button
              class="deletemembre"
              @click="delete_membres(membre)"
              title="Quitte l'association ?"
            >
              <img
                src="https://cdn-icons-png.flaticon.com/128/216/216658.png"
                alt=""
              />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
 
</template>
<style scoped>
.retourliste{
  margin-left: 10px;
  /* margin-bottom: 35px !important; */
  border-radius: 20px;
  width: 200px;
  border: 5px solid rgb(70, 137, 226);
  background-color: rgb(70, 137, 226);
  color: white ;
  font-weight: bold;
  font-size: 20px;
  height: 70px !important;

}
.updatenvx {
  margin-bottom: 15px;
  margin-left: 155px;
  width: 20px;
}
h1 {
  font-size: large;
}
nav {
  margin-bottom: 1%;
}
.membres {
  /* margin-top: 7%; */
  margin-left: 1%;
  margin-right: 1%;
}
.deletemembre {
  width: 50px;
  border: none;
}
table {
  caption-side: top; /*Permets de changer le titre de position (top: en haut)*/
  border-collapse: collapse; /*permets de coller les bordures du tableau / la valeurs separate permets de dissocier les bordures (valeur par defaut)*/
  width: 100%;
}
td,th /*Toutes les cellules des tableaux...*/ {
  border: 1px solid black;
  text-align: center;
}

caption {
  font-size: 29px;
}
@media screen and (max-width: 1022px) {
  .membres {
   
    transform: rotate(90deg);
   
  }
  .retourliste{
    /* margin-bottom: 15px; */
  }
 
}
</style>
