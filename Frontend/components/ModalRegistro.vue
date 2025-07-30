<script setup>
import { ref } from 'vue'

const props = defineProps({
    show: Boolean
})
const emit = defineEmits(['close'])

const name = ref('')
const email = ref('')
const password = ref('')
const confirma_senha = ref('')
const mostrarSenha = ref(false)
const mostrarConfirma = ref(false)



async function registro() {
  if (password.value !== confirma_senha.value) {
    alert('As senhas não coincidem!')
    return
  }

  try {
    const data = await $fetch('http://localhost:8000/api/register', {
      method: 'POST',
      body: {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: confirma_senha.value
      },
      headers: {
        'Content-Type': 'application/json'
      }
    })

    localStorage.setItem('token', data.token)

    alert('Usuário registrado com sucesso!')
    emit('close')
  } catch (error) {
    console.error('Erro ao registrar:', error)
    alert('Erro ao registrar!')
  }
}


</script>

<template>
  <div v-if="show" class="modal-overlay">
    <div class="modal-content">
      <h2 class="modal-title">Registro</h2>

      <div class="input-group">
        <span class="input-icon">🧑</span>
        <input type="text" placeholder="Nome" v-model="name" class="modal-input"/>
      </div>

      <div class="input-group">
        <span class="input-icon">📧</span>
        <input type="email" placeholder="E-Mail" v-model="email" class="modal-input"/>
      </div>

      <div class="input-group">
        <span class="input-icon">🔒</span>
        <input :type="mostrarSenha ? 'text' : 'password'"
               placeholder="Senha"
               v-model="password"
               class="modal-input"/>
        <button class="eye-button" @click="mostrarSenha = !mostrarSenha">
          {{ mostrarSenha ? '🙈' : '👁️' }}
        </button>
      </div>

      <div class="input-group">
        <span class="input-icon">🔒</span>
        <input :type="mostrarConfirma ? 'text' : 'password'"
               placeholder="Confirma Senha"
               v-model="confirma_senha"
               class="modal-input"/>
        <button class="eye-button" @click="mostrarConfirma = !mostrarConfirma">
          {{ mostrarConfirma ? '🙈' : '👁️' }}
        </button>
      </div>

      <div class="modal-buttons">
        <button @click="registro" class="modal-button primary">Registrar</button>
        <button @click="$emit('close')" class="modal-button secondary">Fechar</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(5px); 
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.modal-content{
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 16px;
    padding: 2rem;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(10px);
    color: #fff;
    text-align: center;
}
.modal-title{
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
}
.input-group{
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    margin: 0.6rem 0;
    padding: 0.5rem;
    position: relative;
}
.input-icon{
    margin-left: 0.5rem;
    font-size: 1.2rem;
    color: #fff;
}
.modal-input{
    background: transparent;
    border: none;
    outline: none;
    color: #fff;
    padding: 0.5rem;
    flex: 1;
    font-size: 1rem;
}
.eye-button{
    background: none;
    border: none;
    font-size: 1.1rem;
    cursor: pointer;
    margin-right: 0.5rem;
    color: white;
}
.modal-buttons{
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}
.modal-button{
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}
.modal-button.primary {
  background-color: #4f46e5;
  color: white;
}
.modal-button.primary:hover {
  background-color: #4338ca;
}

.modal-button.secondary {
  background-color: transparent;
  color: white;
  border: 1px solid white;
}
.modal-button.secondary:hover {
  background-color: rgba(255, 255, 255, 0.2);
}
</style>