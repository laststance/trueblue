import configureStore from './store/configureStore'
import App from './startup/home'
import ReactOnRails from 'react-on-rails'

const homeStore = configureStore

ReactOnRails.registerStore({homeStore})
ReactOnRails.register({App})
