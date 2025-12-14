<script setup lang="ts">
import { highlight } from '../highlight'
import type { DebugItem } from '../types'
import DebugItemComponent from './debug-item-wrapper.vue'

defineProps<{
	item: DebugItem<'log'>
}>()
</script>

<template>
	<debug-item-component :copyable="item.data.content" :created-at="item.created_at" type="Log">
		<div class="p-2 overflow-auto">
			<pre v-html="highlight(JSON.stringify(JSON.parse(item.data.content), undefined, 2), 'json')" />
		</div>
	</debug-item-component>
</template>
