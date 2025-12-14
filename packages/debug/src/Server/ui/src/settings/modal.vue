<script setup lang="ts">
import type { FormSubmitEvent } from '@nuxt/ui'
import { type } from 'arktype'
import { reactive, useTemplateRef } from 'vue'
import { settings } from './settings'

const $emit = defineEmits<{
	close: []
}>()

const form = useTemplateRef('form')

const schema = type({
	pollInterval: type.number.atLeast(500),
	ascending: 'boolean',
})

const state = reactive<typeof schema.inferIn>({
	pollInterval: settings.value.pollInterval,
	ascending: settings.value.order === 'asc',
})

async function submit(event: FormSubmitEvent<typeof schema.inferOut>) {
	settings.value.pollInterval = event.data.pollInterval
	settings.value.order = event.data.ascending ? 'asc' : 'desc'

	$emit('close')
}
</script>

<template>
	<UModal close title="Settings">
		<template #body>
			<UForm :schema :state @submit="submit" ref="form" class="flex flex-col gap-6">
				<!-- Poll interval -->
				<UFormField
					label="Poll interval"
					description="Control how often the debug interface checks for new debug entries, in milliseconds."
					name="pollInterval"
				>
					<UInput type="number" icon="tabler:clock" v-model="state.pollInterval" class="mt-1" required />
				</UFormField>
				<!-- Display order -->
				<UFormField name="ascending">
					<USwitch
						v-model="state.ascending"
						class="mt-1"
						label="Display in chronological order"
						description="Control whether debug items are displayed in chronological or reverse order."
					/>
				</UFormField>
			</UForm>
		</template>
		<template #footer>
			<div class="flex gap-2">
				<UButton label="Save" @click="form?.submit" />
			</div>
		</template>
	</UModal>
</template>
