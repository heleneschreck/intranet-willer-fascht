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
      title: "",
      fileData:""
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
    
      var requestOptions = {
        method: "DELETE",
        headers: myHeaders,
   
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

     
      },
      add_support: function(){

console.log(this.user.id);
console.log(this.fileData);
console.log(this.title);
        var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var formdata = new FormData();
      formdata.append("image", this.fileData ); // Utilisez directement "selectedFile" ici
      formdata.append("user_id", this.user.id);
      formdata.append("title", this.title);
      var requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: formdata,
        redirect: "follow",
      }

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
      <div class="affichetitle">{{ affiche.title }}</div>
      <img
        v-bind:src="affiche.url"
        style="
          width: 400px;
          height: 460px;
          margin-left: 23px;
          margin-bottom: 15px;
        "
      />
      <div class="deleteimage" v-if="user.id == affiche.user_id">
        <button
          class="deleteaffiche"
          @click="delete_affiche(affiche)"
          title="Supprimer définitivement"
        >
          <img
            src="https://cdn-icons-png.flaticon.com/512/3182/3182136.png"
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
    <div class="center-content">

      
      <div class="ajoutdaffiche">
        
        <input v-model="title" placeholder="titre" class="inputtitle" type="text" /> <br>
        <input type="file" @change="onFileSelected" name="image" id="" />
        <button @click="add_support()" class="addaffiche">Ajouter un nouveau support publicitaire</button>
          
      </div>
  </div>
  </div>
</template>
<style>
.affiches {
  margin-top: 8% !important;
  margin-left: 1%;
}
.inputtitle {
  margin-bottom: 13px
}
.affichetitle {
  font-size: 20px;
  text-align: center;
}
.supportpublicitaires {
  display: flex;
  margin-top: 20px;
  flex-wrap: wrap;
  position: relative;
}
.addaffiche {
  margin-left: 30px;
}
.ajoutdaffiche{
  margin-left: auto;
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
  display: flex;
  justify-content: center;
  align-items: center;
}
.center-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  /* Ajoutez d'autres styles si nécessaire */
}
</style>
