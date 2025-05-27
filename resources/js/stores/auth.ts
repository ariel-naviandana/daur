import { defineStore } from 'pinia'
import { useAuthApi } from '@/composables/useAuthApi'
import { User } from '@/interfaces/User'

const SESSION_DURATION_HOURS = 4
const SESSION_DURATION_MS = SESSION_DURATION_HOURS * 60 * 60 * 1000
const CHECK_INTERVAL_MS = 60 * 1000

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null as User | null,
        sessionExpiresAt: null as number | null,
        intervalId: null as number | null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
        userId: (state) => state.user?.id || null,
        isSessionExpired: (state) => {
            if (!state.sessionExpiresAt) return true
            return Date.now() >= state.sessionExpiresAt
        },
    },

    actions: {
        setUser(user: User) {
            this.user = user
            this.sessionExpiresAt = Date.now() + SESSION_DURATION_MS
            localStorage.setItem('auth.user', JSON.stringify(user))
            localStorage.setItem('auth.expiresAt', this.sessionExpiresAt.toString())
            this.startSessionCheck()
        },

        clearUser() {
            this.user = null
            this.sessionExpiresAt = null
            localStorage.removeItem('auth.user')
            localStorage.removeItem('auth.expiresAt')
            this.stopSessionCheck()
        },

        loadFromLocalStorage() {
            const storedUser = localStorage.getItem('auth.user')
            const storedExpiresAt = localStorage.getItem('auth.expiresAt')

            if (storedUser && storedExpiresAt) {
                const expiresAt = parseInt(storedExpiresAt, 10)
                if (Date.now() < expiresAt) {
                    this.user = JSON.parse(storedUser)
                    this.sessionExpiresAt = expiresAt
                    this.startSessionCheck()
                } else {
                    this.clearUser()
                }
            }
        },

        async login(credentials: { email: string; password: string }) {
            const { login } = useAuthApi()
            const user = await login(credentials)
            if (user) {
                this.setUser(user)
            }
            return user
        },

        async logout() {
            const { logout } = useAuthApi()
            const success = await logout()
            if (success) {
                this.clearUser()
            }
            return success
        },

        async fetchCurrentUser() {
            if (!this.isSessionExpired) {
                const { getCurrentUser } = useAuthApi()
                const user = await getCurrentUser()
                if (user) {
                    this.setUser(user)
                    return user
                }
            }
            this.clearUser()
            return null
        },

        async checkSession() {
            if (this.isSessionExpired) {
                await this.logout()
                window.location.href = '/login'
                return false
            }
            return true
        },

        startSessionCheck() {
            if (!this.intervalId) {
                this.intervalId = window.setInterval(() => {
                    this.checkSession()
                }, CHECK_INTERVAL_MS)
            }
        },

        stopSessionCheck() {
            if (this.intervalId) {
                window.clearInterval(this.intervalId)
                this.intervalId = null
            }
        },
    },
})
