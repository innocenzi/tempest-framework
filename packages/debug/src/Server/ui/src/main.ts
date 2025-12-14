import ui from '@nuxt/ui/vue-plugin'
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import renderer from './renderer.vue'
import './main.css'

const app = createApp(renderer)
const router = createRouter({
	routes: [],
	history: createWebHistory(),
})

app.use(router)
app.use(ui)
app.mount('#app')
