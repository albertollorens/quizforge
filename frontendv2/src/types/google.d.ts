// Google Identity Services types
declare global {
  interface Window {
    google: {
      accounts: {
        id: {
          initialize: (config: {
            client_id: string
            callback: (response: GoogleCredentialResponse) => void
            auto_select?: boolean
            cancel_on_tap_outside?: boolean
          }) => void
          prompt: () => void

          renderButton: (
            parent: HTMLElement,
            options?: {
              theme?: string
              size?: string
              text?: string
              shape?: string
              width?: number
            }
          ) => void

          cancel: () => void
        }
      }
    }
  }
}

export interface GoogleCredentialResponse {
  credential: string
  select_by: string
}