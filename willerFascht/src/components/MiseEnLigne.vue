<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      mode: false,
      // Assuming you want to use the mode as a string, you should choose either 'false' or 'bold'
      // The current code has two conflicting values for mode.
      // I've corrected it to use 'false', but you may want to adjust it accordingly.
      // mode: "false",
      title: "", // Add this line if 'title' is used in the template
      content: "", // Add this line if 'content' is used in the template
      color: null,
      lettre: null,
      italique: null,
      currentFontSize: null, //
    };
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {
    this.user = JSON.parse(localStorage.getItem("user") || "[]");
  },
  methods: {
    gras() {
      // console.log(document.querySelector("textarea"));
      document.querySelector("textarea").style.fontWeight = "bold";
      var p = document.querySelector("textarea").style;
      // console.log(p);
      this.lettre = "bold";
      console.log(this.weight);
      this.mode = "bold";
    },
    weight() {
      console.log(document.querySelector("textarea"));
      document.querySelector("textarea").style.fontWeight = "normal";
      var p = document.querySelector("textarea").style;
      console.log(p);
      this.lettre = "normal";
      this.mode = "false";
    },
    italic() {
      console.log(document.querySelector("textarea"));
      const p = (document.querySelector("textarea").style.fontStyle = "italic");
      const element = document.querySelector("textarea").value;
      this.italique = "italic";
      this.mode = "normal";
      console.log(element);
    },
    normal() {
      console.log(document.querySelector("textarea"));
      const p = (document.querySelector("textarea").style.fontStyle = "normal");
      const element = document.querySelector("textarea").value;
      this.italique = "normal";
      this.mode = "false";
      console.log(element);
    },

    choosecolor() {
      console.log(this.color);
      const textarea = document.querySelector("textarea");
      textarea.style.color = this.color;
    },

    augmenter() {
      var currentFontSize = parseFloat(
        document.querySelector("textarea").style.fontSize
      );
      console.log(currentFontSize);

      // Incrémentation de la taille de la police
      currentFontSize += 1;

      // Appliquer la nouvelle taille de police à l'élément textarea
      document.querySelector("textarea").style.fontSize =
        currentFontSize + "px";
      console.log(currentFontSize);
      this.newcurrentFontSize = this.currentFontSize.toString() + "px";

      document.querySelector("textarea").fontSize = this.newcurrentFontSize;
    },
    diminuer() {
      var currentFontSize = parseFloat(
        document.querySelector("textarea").style.fontSize
      );
      console.log(currentFontSize);

      currentFontSize -= 1;
      // Appliquer la nouvelle taille de police à l'élément textarea
      document.querySelector("textarea").style.fontSize =
        currentFontSize + "px";
      console.log(currentFontSize);
      this.newcurrentFontSize = this.currentFontSize.toString() + "px";

      document.querySelector("textarea").fontSize = this.newcurrentFontSize;
      console.log(this.newcurrentFontSize);
      console.log(this.currentFontSize);
    },

    addPost() {
      this.taille = document.querySelector("textarea").style.fontSize;
      console.log(this.title);
      console.log(this.content);
      console.log(this.italique);
      console.log(this.color);
      console.log(this.taille);
      console.log(this.user.id);
      if (this.color) {
        console.log(this.color);
      } else {
        console.log("il n y a pas de couleur");
        this.color = "rgb(0,0,0)";
        console.log(this.color);
      }

      if (this.lettre) {
        console.log(this.lettre);
      } else {
        console.log("le texte n'a pas de style gras");
        this.lettre = "normal";
      }
      console.log(this.lettre);

      if (this.italique) {
        console.log(this.italique);
      } else {
        console.log("le texte n'a pas de style italique");
        this.italique = "normal";
      }
      console.log(this.italique);

      var urlencoded = new URLSearchParams();
      urlencoded.append("title", this.title);
      urlencoded.append("content", this.content);
      urlencoded.append("color", this.color);
      urlencoded.append("size", this.taille);
      urlencoded.append("weight", this.lettre);
      urlencoded.append("italique", this.italique);
      urlencoded.append("user_id", this.user.id);

      var requestOptions = {
        method: "POST",
        body: urlencoded,
        redirect: "follow",
      };

      let url = "http://localhost:8000/api/articles";

      fetch(url, requestOptions)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json(); // Parse the response body as JSON
        })
        .then((data) => {
          // Assuming the server returns the created post's ID in the response
          const createdPostId = data.id;
          console.log("ID of the created post:", createdPostId);
          setTimeout(() => {
        this.$router.push({ path: "/illustrations/"+createdPostId});
      }, "2000");
        })
        .catch((error) => console.error("Error:", error));
    },
  },
};
</script>

<template>
  <router-link :to="`/accueilintra`">
    <button class="button rounded-lg retourtodo">Retour</button>
  </router-link>
  <h1>Mise en ligne :</h1>

  <div id="form">
    <input
      v-model="title"
      type="text"
      name="title"
      id="title"
      placeholder="title"
    />
    <br />
    <div class="options">
      <button @click="weight()" v-if="mode == 'bold'" class="gras">
        <img
          src="https://cdn-icons-png.flaticon.com/128/5456/5456895.png"
          class="optiontext"
        />
      </button>

      <button @click="gras()" v-else class="gras">
        <img
          src="https://cdn-icons-png.flaticon.com/512/114/114304.png"
          class="optiontext"
        />
      </button>
      <button @click="normal()" v-if="mode == 'normal'" class="italic">
        <img
          src="https://cdn-icons-png.flaticon.com/128/5456/5456769.png"
          class="optiontext"
        />
      </button>
      <button @click="italic()" v-else class="italic">
        <img
          src="https://cdn-icons-png.flaticon.com/512/142/142351.png"
          class="optiontext"
        />
      </button>
      <button class="souligner">
        <input
          type="color"
          @input="choosecolor()"
          v-model="color"
          class="optiontext colorText"
        />
      </button>

      <button @click="augmenter()" class="augmenter">
        <img
          src="https://cdn-icons-png.flaticon.com/512/1617/1617523.png"
          class="optiontext"
        />
      </button>
      <button @click="diminuer()" class="diminuer">
        <img
          src="https://cdn-icons-png.flaticon.com/512/690/690338.png"
          class="optiontext"
        />
      </button>
    </div>

    <textarea
      name="content"
      v-model="content"
      placeholder="Post"
      id="content"
      cols="30"
      rows="5"
      style="font-size: 15px"
    >
    </textarea>
    <br />
    <!-- </div> -->
    <button @click="addPost()" class="button btn-block validerarticle">
      Suivant
      <img
        src="https://cdn-icons-png.flaticon.com/128/3588/3588232.png"
        style="width: 55px; float: right; margin-left: 10px"
        alt=""
      />
    </button>
  </div>
</template>
<style>
.miseenligne {
  /* padding-top: 8% !important; */
  background-color: hsla(0, 0%, 84%, 0.3);
}

label {
  margin-right: 3%;
}
.url {
  font-size: 61%;
}
h1 {
  text-align: center;
  margin-top: 3% !important;
  margin-bottom: 3% !important;
  font-size: 27px !important;
  font-weight: 600 !important;
}

#form {
  margin-left: 17%;
  font-size: 27px;
  line-height: 195%;
}
textarea {
  border: 1px solid;
  width: 71%;
  padding: 1%;
}
#title {
  border: 3px solid;
  padding-left: 1%;
  margin-bottom: 2%;
}
.options {
  border: 1px solid;
  width: 71%;
  display: flex;
  justify-content: space-around;
  height: 66px;
  /* padding-top: 1%; */
  padding-bottom: 1%;
}
.gras {
  padding: 5px !important;
  height: 45px !important;
}
.italic {
  padding: 5px !important;
  height: 45px !important;
}
.souligner {
  padding: 5px !important;
  height: 45px !important;
}
.augmenter {
  padding: 5px !important;
  height: 45px !important;
}
.diminuer {
  padding: 5px !important;
  height: 45px !important;
}
.optiontext {
  width: 35px;
}
.colorText {
  position: relative;
  bottom: 10px;
}
.validerarticle {
  background-color: green;
  color: white;
  font-weight: bold;
  /* width: 15%; */
  margin-left: 30%;
}
</style>
