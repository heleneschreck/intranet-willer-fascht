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
  },
};
</script>
<template>
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
        <th>Niveau</th>
        <th v-show="user.niveau == '1'">Supprimer</th>
      </thead>
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
          <td @mouseover="display()">
            <div v-if="membre.niveau == 1">Administrateur</div>
            <div v-else>Utilisateur</div>
            <div
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
            </div>
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
<style>
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
  width: 30px;
  border: none;
}
table {
  caption-side: top; /*Permets de changer le titre de position (top: en haut)*/
  border-collapse: collapse; /*permets de coller les bordures du tableau / la valeurs separate permets de dissocier les bordures (valeur par defaut)*/
  width: 100%;
}
td,th /*Toutes les cellules des tableaux...*/ {
  border: 1px solid black;
}

caption {
  font-size: 29px;
}
</style>
