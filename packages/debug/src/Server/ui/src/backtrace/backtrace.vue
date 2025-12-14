<script setup lang="ts">
import { type Backtrace } from '../types'

defineProps<{
	backtrace: Backtrace
}>()
</script>

<template>
	<ul>
		<li v-for="(frame, i) in backtrace.frames" :key="`${frame.file}#${i}`">
			<span v-text="frame.class + ':' + frame.method" />
		</li>
	</ul>
</template>
