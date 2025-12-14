<script setup lang="ts">
import type { DebugItem } from '../types'
import ItemException from './item-exception.vue'
import ItemLog from './item-log.vue'
import ItemQuery from './item-query.vue'

defineProps<{
	item: DebugItem
}>()
</script>

<template>
	<div>
		<item-exception v-if="item.type === 'exception'" :key="`${item.id}_exception`" :item />
		<item-log v-if="item.type === 'log'" :key="`${item.id}_log`" :item />
		<item-query v-if="item.type === 'query'" :key="`${item.id}_query`" :item />
	</div>
</template>
