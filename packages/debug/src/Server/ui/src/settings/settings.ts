import { useLocalStorage } from '@vueuse/core'
import Modal from './modal.vue'

const overlay = useOverlay()

export const settingsModal = overlay.create(Modal)

export interface Settings {
	pollInterval: number
	order: 'asc' | 'desc'
}

export const settings = useLocalStorage<Settings>('tempest-debug-settings', {
	pollInterval: 2_000,
	order: 'asc',
})
