export interface LogDebugItemData {
	content: string
}

export interface QueryDebugItemData {
	sql: string
	databaseTag: string
	databaseDialect: 'sqlite' | 'mysql' | 'pgsql'
}

export interface ExceptionDebugItemData {
	message: string
	backtrace: Backtrace
}

interface BaseDebugItem {
	id: string
	created_at: string
	request_id?: string
	backtrace: string
}

interface LogDebugItem extends BaseDebugItem {
	type: 'log'
	data: LogDebugItemData
}

interface QueryDebugItem extends BaseDebugItem {
	type: 'query'
	data: QueryDebugItemData
}

interface ExceptionDebugItem extends BaseDebugItem {
	type: 'exception'
	data: ExceptionDebugItemData
}

export type DebugItem<T extends DebugItemType = DebugItemType> = T extends 'log' ? LogDebugItem
	: T extends 'query' ? QueryDebugItem
	: T extends 'exception' ? ExceptionDebugItem
	: LogDebugItem | QueryDebugItem | ExceptionDebugItem

export type DebugItemType = 'log' | 'query' | 'exception'

export interface UnserializedBacktrace {
	type: string
	data: string
}

export interface Backtrace {
	frames: Frame[]
}

export interface Frame {
	file: string
	line: number
	method?: string
	class?: string
	arguments: string[]
	vendor: boolean
}
