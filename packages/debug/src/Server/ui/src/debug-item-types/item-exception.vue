<script setup lang="ts">
import type { DebugItem } from '../types'
import DebugItemComponent from './debug-item-wrapper.vue'

const props = defineProps<{
	item: DebugItem<'exception'>
}>()
</script>

<template>
	<DebugItemComponent :copyable="item.data.message" :created-at="item.created_at" type="Exception">
		<div class="p-2 overflow-auto">
			<pre v-text="item.data.message" />
			<UCollapsible class="flex flex-col gap-2 mt-2 w-full">
				<UButton class="w-48" label="See trace" color="neutral" variant="subtle" trailing-icon="i-lucide-chevron-down" block />
				<template #content>
					<pre v-text="item.data.backtrace" />
				</template>
			</UCollapsible>
		</div>
	</DebugItemComponent>
</template>
