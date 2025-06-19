<script setup lang="ts">
import { useFavicon, useLocalStorage, useTimeoutPoll, useTitle } from '@vueuse/core'
import { computed, ref } from 'vue'
import type { DebugItem } from './types'
import DebugItemComponent from './debug-item-types/debug-item.vue'
import logo from './tempest-logo.svg'

useFavicon(logo)
useTitle('Debug')

const poll = useLocalStorage('poll-interval', 1000)
const items = ref<Map<number, DebugItem>>(new Map())
const latest = computed(() => [...items.value.values()].at(-1)?.id)

const { isActive, pause, resume } = useTimeoutPoll(async () => {
	const params = new URLSearchParams()
	if (latest.value) {
		params.set('last-seen-id', latest.value)
	}

	const result = await fetch(`/__debug/latest?${params}`)

	if (result.ok) {
		const value = await result.json() as any[]
		value.forEach((item) => items.value.set(item.id, item))
	}
}, poll, { immediateCallback: true })
</script>

<template>
	<u-app>
		<main class="max-w-screen flex min-h-screen grow flex-col overflow-hidden bg-gray-950 p-8 text-gray-100">
			<div>
				poll interval:
				<input v-model="poll" type="number" /> /
				<button @click="isActive ? pause() : resume()">
					{{ isActive ? 'pause' : 'resume' }}
				</button>
			</div>
			<br />
			<div class="flex flex-col gap-y-2">
				<debug-item-component v-for="[id, item] in items" :key="id" :item />
			</div>
		</main>
	</u-app>
</template>
