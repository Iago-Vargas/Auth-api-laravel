import axios from 'axios';

export default defineNuxtPlugin(() => {
    const api = axios.create({
        baseURL: 'http://localhost:8000/api',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    })

    nuxtApp.provide('api', api)
})