<script setup lang="ts">
import { useClipboard } from '@vueuse/core'
import { Temporal } from 'temporal-polyfill'

const $props = defineProps<{
	type: string
	createdAt: string
	copyable?: string
	badges?: Array<{
		color?: 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral'
		content?: string
		tooltip?: string
	}>
}>()

const date = Temporal.Instant
	.from($props.createdAt)
	.toLocaleString(undefined, { timeStyle: 'medium' })

const { copy, copied, isSupported: canCopy } = useClipboard({ source: () => $props.copyable! })
</script>

<template>
	<div class="group relative bg-default px-2 py-2 rounded-lg divide-y divide-default ring ring-default overflow-hidden">
		<div class="mb-2">
			<div v-if="canCopy">
				<UButton
					:ui="{ base: 'top-2 right-2 absolute opacity-0 group-hover:opacity-100 transition-opacity' }"
					variant="ghost"
					size="sm"
					color="neutral"
					:icon="copied ? 'tabler-check' : 'tabler-copy'"
					@click="() => copy()"
				/>
			</div>
			<slot />
		</div>
		<!-- Footer -->
		<div class="flex justify-end gap-2 mt-2">
			<!-- Specific badges -->
			<slot name="badges">
				<template v-for="badge in badges" :key="badge.content">
					<UTooltip v-if="badge.content" :text="badge.tooltip ?? badge.content">
						<UBadge :color="badge.color ?? 'neutral'" variant="outline" :label="badge.content" class="font-mono" />
					</UTooltip>
				</template>
			</slot>
			<!-- Log type -->
			<UBadge color="neutral" variant="outline" :label="type.toLocaleLowerCase()" class="font-mono" />
			<!-- Log time -->
			<UTooltip :text="createdAt">
				<UBadge color="neutral" variant="outline" :label="date" class="font-mono" />
			</UTooltip>
		</div>
	</div>
</template>
