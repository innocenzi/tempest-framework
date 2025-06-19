export interface DebugItem<T = any> {
	id: string
	created_at: string
	request_id?: string
	type: DebugItemType
	data: T
	backtrace: Backtrace[]
}

export type DebugItemType = 'log' | 'query' | 'exception'

export interface Backtrace {
	file: string
	lineNumber: number
}
