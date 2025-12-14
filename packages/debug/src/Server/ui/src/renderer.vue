<script setup lang="ts">
import { useFavicon, useTimeoutPoll, useTitle } from '@vueuse/core'
import { Motion } from 'motion-v'
import { computed, ref, watch } from 'vue'
import DebugItemComponent from './debug-item-types/debug-item.vue'
import { settings, settingsModal } from './settings/settings'
import logo from './tempest-logo.svg'
import type { DebugItem } from './types'

useFavicon(logo)
useTitle('Debug')

const items = ref<Map<number, DebugItem>>(new Map())
const latest = computed(() => [...items.value.values()].at(-1)?.id)

async function fetchLastItems() {
	const params = new URLSearchParams()
	params.set('ascending', settings.value.order === 'asc' ? 'true' : 'false')

	if (latest.value) {
		params.set('last-seen-id', latest.value)
	}

	const result = await fetch(`/__debug/latest?${params}`)

	if (result.ok) {
		const value = await result.json() as any[]
		value.forEach((item) => items.value.set(item.id, item))
	}
}

async function clear() {
	const token = await cookieStore.get({ name: 'XSRF-TOKEN' })

	if (!token?.value) {
		return
	}

	await fetch(`/__debug/clear`, {
		method: 'post',
		credentials: 'same-origin',
		headers: {
			'x-xsrf-token': token.value,
		},
	})

	items.value = new Map()
}

watch(() => settings.value.order, async () => {
	items.value = new Map()
	await fetchLastItems()
})

const { isActive, pause, resume } = useTimeoutPoll(fetchLastItems, () => settings.value.pollInterval, { immediateCallback: true })
</script>

<template>
	<u-app>
		<main class="flex flex-col bg-neutral-950 p-8 max-w-screen min-h-screen overflow-hidden grow">
			<!-- Header -->
			<div class="flex justify-between items-center">
				<span class="font-medium text-toned text-sm">
					Tempest debug
				</span>
				<!-- Actions -->
				<div class="flex justify-end items-center gap-2">
					<UTooltip text="Clear logs">
						<UButton icon="tabler:x" @click="clear()" color="error" />
					</UTooltip>
					<UTooltip text="Open settings">
						<UButton icon="tabler:settings" @click="settingsModal.open()" color="secondary" />
					</UTooltip>
					<UTooltip :text="isActive ? 'Stop polling' : 'Resume polling'">
						<UButton
							:icon="isActive ? 'tabler:pause' : 'tabler:play'"
							@click="isActive ? pause() : resume()"
							:color="isActive ? 'secondary' : 'error'"
						/>
					</UTooltip>
				</div>
			</div>
			<!-- Debug content -->
			<div class="flex flex-col gap-y-2 mt-4">
				<Motion
					v-for="(item, i) in [...items.values()].slice(0, 100)"
					:variants="{ hidden: { opacity: 0, x: -5 }, visible: { opacity: 100, x: 0 } }"
					animate="visible"
					exit="hidden"
					layout
					:transition="{ delay: i * 0.01 }"
					:key="item.id"
					initial="hidden"
					as-child
				>
					<debug-item-component :key="item.id" :item />
				</Motion>
			</div>
		</main>
	</u-app>
</template>
