<script>
import { mapState } from "vuex";
import moment from "moment";

export default {
  data() {
    return {
      mode: false,
      mode: "nodisplay",
      user: [],
      affiches: [],
      comments: [],
      membres: [],
      affiche: [],
      commentaires: [],
      likes: [],
      isUserAbsent: false,
      title: "",
      fileData: "",
      isUserAbsent: false,
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    this.moment = moment;
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    let fetched_membres = await fetch("http://127.0.0.1:8000/api/users");
    this.membres = await fetched_membres.json();
    // console.log(this.membres);
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    let fetched_affiches = await fetch("http://localhost:8000/api/image");
    this.affiches = await fetched_affiches.json();
    console.table(this.affiches);
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    let fetched_commentaires = await fetch(
      "http://localhost:8000/api/commentaires"
    );
    this.commentaires = await fetched_commentaires.json();
    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    for (let i = 0; i < this.affiches.length; i++) {
      const affiche = this.affiches[i];

      let fetched_likes = await fetch(
        "http://localhost:8000/api/like/image/" + affiche.id
      );
      this.likes = await fetched_likes.json();
      const test = this.likes.likes;
      console.table(test);
      function delay(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
      }
      // Créez un tableau des IDs des likes
      const likeIds = test.map((like) => like.user_id);

      console.log(likeIds);
      // Vérifiez si l'ID de l'utilisateur (4) est présent dans le tableau
      this.isUserAbsent = likeIds.includes(4);

      console.log(this.isUserAbsent);
    }

    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }

    // Récupérer les likes pour chaque affiche
    for (let i = 0; i < this.affiches.length; i++) {
      const affiche = this.affiches[i];
      await this.fetchLikesForAffiche(affiche);

      await this.fetchCommentsForAffiche(affiche);
      let fetched_comments = await fetch(
        "http://localhost:8000/api/commentaires/image/" + affiche.id
      );
    }

    function delay(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    }
  },
  methods: {
    async makeRequestsWithBackoff(requestCount, delay) {
      for (let i = 0; i < requestCount; i++) {
        try {
          await makeRequest();
        } catch (error) {
          console.error("Erreur lors de la requête :", error);
        }
        await delay(delay); // Introduire le délai après chaque requête
        delay *= 2; // Augmenter le délai pour la prochaine requête (facultatif)
      }
    },

    async fetchLikesForAffiche(affiche) {
      let fetched_likes = await fetch(
        "http://localhost:8000/api/like/image/" + affiche.id
      );
      let likesData = await fetched_likes.json();
      // console.log(likesData);
      affiche.likes = {
        count: likesData.count || 0, // Utilisez la valeur de likesData.count ou 0 si elle n'est pas définie
      };
      // console.log(affiche);
    },
    async fetchCommentsForAffiche(affiche) {
      let fetched_comments = await fetch(
        "http://localhost:8000/api/commentaires/image/" + affiche.id
      );
      let CommentData = await fetched_comments.json();
      affiche.comment = {
        count: CommentData.count || 0, // Utilisez la valeur de likesData.count ou 0 si elle n'est pas définie
      };
      // console.log(affiche.comment.count);
    },

    logout: function () {
      this.$store.commit("logout");
      this.$router.push("/");
    },
    displayComments: async function (affiche) {
      console.log(affiche.id);

      let fetched_comments = await fetch(
        "http://localhost:8000/api/commentaires/image/" + affiche.id
      );
      this.comments = await fetched_comments.json();
      // console.log(this.comments);
      // console.table(this.comments.length);
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
    Add_comment: function (affiche) {
      console.log(affiche.id);
      console.log(this.user.id);
      console.log(this.commentaire);

      var urlencoded = new URLSearchParams();
      urlencoded.append("user_id", this.user.id);
      urlencoded.append("image_id", affiche.id);
      urlencoded.append("commentaire", this.commentaire);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/commentaires";
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "1000");
    },
    add_support: function () {
      console.log(this.user.id);
      console.log(this.fileData);
      console.log(this.title);
      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var formdata = new FormData();
      formdata.append("image", this.fileData); // Utilisez directement "selectedFile" ici
      formdata.append("user_id", this.user.id);
      formdata.append("title", this.title);
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
    display_prenomlike: function () {
      this.mode = "displayPrenom";
    },
    display_deleteComment: function () {
      this.mode = "display";
    },

    not_display: function () {
      this.mode = "notdisplay";
    },

    delete_commentaire: function (comment) {
      var myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");

      var requestOptions = {
        method: "DELETE",
        headers: myHeaders,

        redirect: "follow",
      };
      let url = "http://localhost:8000/api/commentaires/" + comment.id;
      fetch(url, requestOptions)
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "2000");
    },
    create_like: function (affiche) {
      var urlencoded = new URLSearchParams();
      urlencoded.append("user_id", this.user.id);
      urlencoded.append("image_id", affiche.id);
      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };

      fetch(
        "http://localhost:8000/api/like?user_id=4&image_id=9",
        requestOptions
      )
        .then((response) => response.text())
        .then((result) => console.log(result))
        .catch((error) => console.log("error", error));
      setTimeout(() => {
        this.$router.go(this.$router.currentRoute);
      }, "1000");
    },

    delete_like: function (like) {
      console.log(like.id);
      var requestOptions = {
        method: "DELETE",
        redirect: "follow",
      };
      let url = "http://localhost:8000/api/likes/" + like.id;

      fetch(url, requestOptions)
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
  <router-link :to="`/accueilintra`">
    <button class="button rounded-lg retourcompterendu">Retour</button>
  </router-link>
  <h1>Supports publicitaires</h1>
  <div class="supportpublicitaires">
    <div v-for="affiche in affiches">
      <div class="supportpublicitaire">
        <div class="affichetitle">{{ affiche.title }}:</div>

        <img
          v-bind:src="affiche.url"
          style="
            width: 400px;
            height: 460px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 15px;
          "
        />
        <div class="jaimeCommentaires" @mouseover="not_display()">
          <div class="jaime" style="display: flex">
            <div v-for="like in likes">
              <div
                v-if="like.image_id == affiche.id && like.user_id == user.id"
              >
                <img
                  src="https://cdn-icons-png.flaticon.com/128/1533/1533908.png"
                  class="likes"
                  @click="delete_like(like)"
                  title="Delete"
                  alt="likes"
                />
              </div>
              <div class="membreslike" v-if="mode == 'displayPrenom'">
                <div class="membrelike" v-for="membre in membres">
                  <div v-if="membre.id == like.user_id" class="prenomlike">
                    {{ membre.prenom }}
                  </div>
                </div>
              </div>
            </div>

            <div v-if="isUserAbsent">
              <img
                src="https://cdn-icons-png.flaticon.com/128/1533/1533908.png"
                class="likes"
                title="like"
                @click="create_like(affiche)"
                alt="likes"
              />
            </div>

            <p
              class="nbjaime"
              v-if="affiche.likes"
              @mouseover="display_prenomlike()"
            >
              <!-- {{ affiche.likes.count }} j'aime -->
            </p>
          </div>

          <div class="commentaires">
            <img
              src="https://cdn-icons-png.flaticon.com/128/2190/2190552.png"
              class="comments"
              alt="comments"
            />
            <p
              class="nbcommentaire"
              @click="displayComments(affiche)"
              v-if="affiche.comment"
            >
              {{ affiche.comment.count }} Commentaires
            </p>
          </div>
        </div>
        <hr />
        <br />
        <div v-if="affiche.comment">
          <div v-if="affiche.comment.count == '0'">
            <div class="commentaire">Il n'y a pas encore de commentaires .</div>
          </div>
        </div>
        <div
          v-for="comment in comments.commentaires"
          class="listedescommentaires"
        >
          <div v-if="comment.image_id == affiche.id">
            <div class="commentaire" @mouseover="display_deleteComment">
              <div v-for="membre in membres">
                <div v-if="membre.id == comment.user_id">
                  <div class="delete_comments" v-if="mode == 'display'">
                    <div v-if="comment.user_id == user.id">
                      <img
                        src="https://cdn-icons-png.flaticon.com/128/190/190406.png"
                        class="delete_comment"
                        alt="delete_comments"
                        @click="delete_commentaire(comment)"
                      />
                    </div>
                  </div>
                  <div class="prenomcommentaire">{{ membre.prenom }}</div>
                </div>
              </div>
              <div class="commentairecontent">{{ comment.commentaire }}</div>
              <div class="commentairedate">
                {{ moment(comment.created_at).format("DD/MM/YYYY") }}
              </div>
            </div>
          </div>
        </div>

        <div class="ajoutcommentaire" @mouseover="not_display()">
          <input
            type="text"
            v-model="commentaire"
            placeholder="Ajouter un commentaire "
            class="inputcommentaire"
          />
          <div class="envoiecommentaire" @click="Add_comment(affiche)">
            <img
              src="https://cdn-icons-png.flaticon.com/128/2161/2161491.png"
              class="illustrationenvoiecommentaire"
              alt="illustrationenvoiecommentaires"
            />
          </div>
        </div>

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
  </div>
  <hr />
  <br />
  <div class="uploadaffiche">
    <div class="ajoutdaffiche">
      <input
        v-model="title"
        placeholder="titre"
        class="inputtitle"
        type="text"
      />
      <br />
      <input
        type="file"
        @change="onFileSelected"
        name="image"
        accept=".jpg, .jpeg, .png"
        id=""
      />
      <button @click="add_support()" class="addaffiche">
        Ajouter un nouveau support publicitaire
      </button>
    </div>
  </div>
</template>
<style>
h1 {
  background-color: white;
}
.affiches {
  /* padding-top: 8% !important; */
  background-color: hsla(0, 0%, 84%, 0.3);
}
.inputtitle {
  margin-bottom: 13px;
}
.affichetitle {
  font-size: 30px;
  text-align: center;
}
.membreslike {
  background-color: hsl(0deg 0% 84%);
  opacity: 1;
  position: absolute;
  width: 162px;
  margin-top: 30px;
  left: 91px;
}
.membrelike {
  opacity: 1;
  display: flex;
  margin-top: 3px;

  flex-direction: column;
}
.supportpublicitaires {
  display: flex;

  margin-top: 45px;
  margin-bottom: 45px;
  margin-left: 10px;
  margin-right: 10px;
  flex-wrap: wrap;
  position: relative;
}
.supportpublicitaire {
  margin-left: 15px;
  margin-right: 20px;
  padding-bottom: 15px;
  padding-left: 10px;
  padding-right: 10px;
  border-radius: 3px;
  background-color: white;
}
.addaffiche {
  margin-left: 30px;
}
.ajoutdaffiche {
  margin-left: 24%;
}
.corbeille {
  width: 25px;
}
.deleteimage {
  position: absolute;
  left: 370px;
  top: 60px;
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
.jaime {
  display: flex;
}
.likes {
  width: 35px;
  height: 34px;
  margin-right: 9px;
  padding: 3px;
  border: 1px solid hsla(0, 0%, 84%, 0.3);
  border-radius: 3px;
}
.likes:hover {
  cursor: pointer;
}
.nbjaime {
  margin-top: auto;
  margin-bottom: auto;
}
.nbjaime:hover {
  cursor: pointer;
}
.nbcommentaire {
  margin-top: auto;
  margin-bottom: auto;
}
.nbcommentaire:hover {
  cursor: pointer;
}
.commentaires {
  display: flex;
}
.jaimeCommentaires {
  display: flex;

  height: 41px;
  padding-left: 11px;
  padding-right: 11px;
  justify-content: space-between;

  margin-bottom: 15px;
}
.comments {
  width: 30px;
  height: 34px;
  margin-right: 9px;
  padding: 3px;
  border: 1px solid hsla(0, 0%, 84%, 0.3);
  border-radius: 3px;
}
.prenomcommentaire {
  width: 142px;
}
.commentairecontent {
  text-align: center;
}
.commentairedate {
  text-align: end;
  font-size: 10px;
  color: hsl(0, 1%, 38%);
}
.commentaire {
  background-color: hsla(0, 0%, 84%, 0.3);
  padding-left: 13px;
  padding-right: 13px;
  padding-bottom: 10px;
  border-radius: 15px;
  margin-bottom: 10px;
}
.delete_comments:hover {
  cursor: pointer;
}
.delete_comments {
  position: absolute;
  right: 0px;
  top: 3px;
}
.delete_comment {
  width: 20px;
}
.ajoutcommentaire {
  display: flex;
}
.inputcommentaire {
  border: none;
  background-color: rgba(132, 132, 132, 0.169) !important;
  border-radius: 10px !important ;
  height: 30px !important;
  margin-right: 3px;
}
.envoiecommentaire:hover {
  cursor: pointer;
}
.envoiecommentaire {
  background-color: rgba(132, 132, 132, 0.169);
  width: 35px;
  border-radius: 25px 25px 25px 25px !important;
  padding: 3px;
}

.illustrationenvoiecommentaire {
  width: 25px;
}
.uploadaffiche {
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
