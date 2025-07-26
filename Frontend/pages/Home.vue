<script setup>
import { ref } from 'vue'
import ModalLogin from '../components/ModalLogin.vue'
import ModalRegistro from '../components/ModalRegistro.vue'

const mostrarModalRegistro = ref(false)
const mostrarModal = ref(false)
</script>


<template>
  <div>
    <h1>Bem-vindo à Home!</h1>
  </div>

  <div>
    <button @click="mostrarModal = true">Login</button>
    <ModalLogin :show="mostrarModal" @close="mostrarModal =false" />
  </div>
  
  <div>
    <button @click="mostrarModalRegistro = true">Registro</button>
    <ModalRegistro :show="mostrarModalRegistro" @close="mostrarModalRegistro =false"/> 
  </div>
  <div>

  </div>
</template>

<style scoped>

</style>