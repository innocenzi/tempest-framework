import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import ui from '@nuxt/ui/vue-plugin'
import renderer from './renderer.vue'

const app = createApp(renderer)
const router = createRouter({
	routes: [],
	history: createWebHistory(),
})

app.use(router)
app.use(ui)
app.mount('#app')
