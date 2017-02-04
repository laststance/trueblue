import configureStore from './store/configureStore'
import App from './startup/index'
import ReactOnRails from 'react-on-rails'

const indexStore = configureStore

ReactOnRails.registerStore({indexStore})
ReactOnRails.register({App})
