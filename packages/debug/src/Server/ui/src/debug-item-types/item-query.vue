<script setup lang="ts">
import { format } from 'sql-formatter'
import { computed } from 'vue'
import { highlight } from '../highlight'
import type { DebugItem } from '../types'
import DebugItemComponent from './debug-item-wrapper.vue'

const $props = defineProps<{
	item: DebugItem<'query'>
}>()

const formattedSql = computed(() => format($props.item.data.sql))
const highlighted = computed(() => highlight(formattedSql.value, 'sql'))
</script>

<template>
	<debug-item-component
		type="Query"
		:copyable="formattedSql"
		:created-at="item.created_at"
		:badges="[
			{ content: item.data.databaseDialect ?? 'Unknown' },
			{ content: item.data.databaseTag },
		]"
	>
		<div class="p-2 overflow-auto">
			<pre v-html="highlighted" />
		</div>
	</debug-item-component>
</template>
