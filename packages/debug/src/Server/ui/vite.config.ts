import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
	plugins: [vue(), tailwindcss()],
	build: {
		assetsDir: '',
		rollupOptions: {
			input: ['./src/main.ts', './src/main.css'],
			output: {
				assetFileNames: '[name][extname]',
				entryFileNames: '[name].js',
			},
		},
	},
})
