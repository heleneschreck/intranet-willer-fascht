<script>
import { mapState } from "vuex";

export default {
  data() {
    return {
      enabled: true,
      dragging: false,
      mode: false,
      user: [],
      mode: "nodisplay",
      affiches: [],
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

    let fetched_affiches = await fetch("http://localhost:8000/api/image");
    this.affiches = await fetched_affiches.json();
    console.table(this.affiches);
  },
  methods: {
    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    delete_affiche: function (affiche) {
      console.log(affiche.id);
      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      // var formdata = new FormData();
      // formdata.append(
      //   "image",
      //   fileInput.files[0],
      //   "/C:/Users/helen/Desktop/270575913_1397258274028263_2860223166769289827_n.jpg"
      // );

      var requestOptions = {
        method: "DELETE",
        headers: myHeaders,
        // body: formdata,
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/image/" + affiche.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    onFileSelected(event) {
      // Récupérer le fichier sélectionné par l'utilisateur
      const selectedFile = event.target.files[0];

      // Mettre à jour les données ou faire ce que vous voulez avec le fichier
      // par exemple, vous pouvez l'assigner à une variable du modèle
      this.fileData = selectedFile;

      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var formdata = new FormData();
      formdata.append("image", selectedFile); // Utilisez directement "selectedFile" ici

      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: formdata,
        redirect: "follow",
      };

      fetch("http://localhost:8000/api/image", requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
        setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
        
    },
  },
};
</script>
<template>
  <h1>Supports publicitaires</h1>
  <div class="supportpublicitaires">
    <div v-for="affiche in affiches">
      <img
        v-bind:src="affiche.url"
        style="
          width: 400px;
          height: 460px;
          margin-left: 23px;
          margin-bottom: 15px;
        "
      />
      <div class="deleteimage">
        <button
          class="deleteaffiche"
          @click="delete_affiche(affiche)"
          title="Supprimer définitivement"
        >
          <img
            src="https://cdn-icons-png.flaticon.com/128/216/216658.png"
            class="corbeille"
            alt="deleteimage"
          />
        </button>
      </div>
    </div>
  </div>
  <hr />
  <br />
  <div class="uploadaffiche">
    <input type="file" @change="onFileSelected" name="image" id="" />
  </div>
</template>
<style>
.affiches {
  margin-top: 8% !important;
  margin-left: 1%;
}
.supportpublicitaires {
  display: flex;
  margin-top: 20px;
  flex-wrap: wrap;
  position: relative;
}
.corbeille {
  width: 25px;
}
.deleteimage {
  position: absolute;
  left: 392px;
  top: 5px;
  background-color: hsla(0, 0%, 84%, 0.3);
  /* height: 31px; */
  border-radius: 5px;
  /* align-content: center; */
  padding: 1px;
}
.deleteaffiche {
  border: none;
  height: auto;
}
.uploadaffiche {
  margin-left: 34%;
}
</style>
