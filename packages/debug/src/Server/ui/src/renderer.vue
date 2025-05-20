<script setup lang="ts">
import { useFavicon, useTitle } from '@vueuse/core'
import { onMounted, ref } from 'vue'
import logo from './tempest-logo.svg'

useFavicon(logo)
useTitle('Debug')

const connected = ref(false)
const items = ref<any>([])

onMounted(() => {
	const stream = new EventSource('/__debug/stream')
	stream.addEventListener('message', (event) => {
		console.log(event)
		items.value.push(JSON.parse(event.data))
	})
	stream.addEventListener('update', (event) => {
		console.log(event)
		items.value.push(JSON.parse(event.data))
	})
	stream.addEventListener('connected', () => {
		console.log('connected to debug server')
	})
	stream.addEventListener('error', () => {
		console.log('could not open stream')
	})
})
</script>

<template>
	<main class="max-w-screen flex min-h-screen grow flex-col overflow-hidden bg-gray-950 text-gray-100">
		{{ connected ? 'connected' : 'waiting...' }}
		<br />
		<div class="flex flex-col">
			<pre v-for="(item, i) in items" :key="i">{{ item }}</pre>
		</div>
	</main>
</template>
