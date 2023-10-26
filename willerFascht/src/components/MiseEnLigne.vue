<script>
import { mapState } from "vuex";
export default {
  Data() {
    return {};
  },
  computed: {
    ...mapState(["userInfos"]),
  },
  async created() {},
  methods: {
    gras() {
      console.log(document.querySelector("textarea"));
      document.querySelector("textarea").style.fontWeight = "bold";
      var p = document.querySelector("textarea").style;
      console.log(p);
    },
    italic() {
      console.log(document.querySelector("textarea"));
      const p = (document.querySelector("textarea").style.fontStyle = "italic");
      const element = document.querySelector("textarea").value;
      console.log(element);
    },
    souligner() {
      console.log(document.querySelector("textarea"));
      document.querySelector("textarea").style.textDecoration = "underline";
      var p = document.querySelector("textarea").style;
      console.log(p);
    },
    augmenter() {
      console.log(document.querySelector("textarea").style);
    },
    diminuer() {
      console.log(document.querySelector("textarea"));
      currentFontSize = currentFontSize - 1;

      newcurrentFontSize = currentFontSize.toString() + "px";

      document.body.style.fontSize = newcurrentFontSize;
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
      <button @click="gras()" class="gras">
        <img
          src="https://cdn-icons-png.flaticon.com/512/114/114304.png"
          class="optiontext"
        />
      </button>
      <button @click="italic()" class="italic">
        <img
          src="https://cdn-icons-png.flaticon.com/512/142/142351.png"
          class="optiontext"
        />
      </button>
      <button @click="souligner()" class="souligner">
        <img
          src="https://cdn-icons-png.flaticon.com/512/2087/2087924.png"
          class="optiontext"
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

      <div class="image-upload"></div>
      <input
        type="file"
        
        class="url"
        name="url"
        id="url"
        
        
        pattern="https://.*"
        size="30"
      />
    </div>

    <textarea
      name="content"
      v-model="content"
      placeholder="Post"
      id="content"
      cols="30"
      rows="5"
    >
    </textarea>
    <br />
    <!-- </div> -->
    <button @click="addPost()" class="button btn-block">Ajouter un post</button>
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
</style>
