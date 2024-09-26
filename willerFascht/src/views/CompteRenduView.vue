<script setup>
import CompteRendu from "../components/CompteRendu.vue";
import Navbars from '../components/Navbars.vue'
</script>

<template>
  <div class="compterendu">
    <Navbars />
    <CompteRendu />
  </div>
</template>
<style>
.compterendu{
  background-color: hsla(0, 0%, 84%, 0.3);
}

</style>
