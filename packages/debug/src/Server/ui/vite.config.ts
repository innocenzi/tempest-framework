import ui from '@nuxt/ui/vite'
import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'
import { viteSingleFile } from 'vite-plugin-singlefile'

export default defineConfig({
	plugins: [
		vue(),
		tailwindcss(),
		ui({
			ui: {
				colors: {
					primary: 'blue',
					neutral: 'zinc',
				},
				input: {
					defaultVariants: {
						variant: 'soft',
					},
				},
				button: {
					slots: {
						base: 'not-disabled:cursor-pointer',
					},
					defaultVariants: {
						variant: 'soft',
					},
				},
			},
		}),
		viteSingleFile(),
	],
	build: {
		rollupOptions: {
			input: ['./src/main.ts'],
			output: {
				inlineDynamicImports: true,
				assetFileNames: '[name][extname]',
				entryFileNames: '[name].js',
			},
		},
	},
})
