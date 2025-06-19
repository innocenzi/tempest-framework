<script setup lang="ts">
import type { DebugItem } from '../types'

defineProps<{
	item: DebugItem
}>()
</script>

<template>
	<div>
		{{ item.data }}
	</div>
</template>
