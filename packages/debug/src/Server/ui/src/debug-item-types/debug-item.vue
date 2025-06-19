<script setup lang="ts">
import type { DebugItem } from '../types'
import ItemLog from './item-log.vue'

defineProps<{
	item: DebugItem
}>()
</script>

<template>
	<div class="flex flex-col rounded-md border border-gray-700 p-4">
		<item-log v-if="item.type === 'log'" :item />
		<span class="text-gray-600" v-text="new Date(item.created_at)" />
	</div>
</template>
